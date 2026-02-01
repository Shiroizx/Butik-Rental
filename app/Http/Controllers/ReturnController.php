<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Returns;
use App\Models\Fine;
use App\Models\Payment;
use App\Models\ClothesSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $rentalId = $request->query('rental_id');
        $rental = Rental::with('customer', 'details')->findOrFail($rentalId);
        $today = Carbon::now()->format('Y-m-d');

        return view('returns.create', compact('rental', 'today'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'tanggal_dikembalikan' => 'required|date',
            'kondisi' => 'required|string',
            'denda_kerusakan' => 'nullable|numeric|min:0',
            'metode_pembayaran' => 'nullable|string|in:cash,transfer_bank,e-wallet,kartu_kredit,kartu_debit',
        ]);

        DB::beginTransaction();

        try {
            $rental = Rental::with('details')->findOrFail($request->rental_id);

            // 1. Calculate Late Fee
            $tglKembaliJadwal = Carbon::parse($rental->tanggal_kembali);
            $tglDikembalikan = Carbon::parse($request->tanggal_dikembalikan);
            
            $daysLate = 0;
            if ($tglDikembalikan->gt($tglKembaliJadwal)) {
                $daysLate = $tglDikembalikan->diffInDays($tglKembaliJadwal);
            }

            $lateFeePerDay = 50000; 
            $totalLateFee = $daysLate * $lateFeePerDay;

            // 2. Calculate Damage Fee
            $damagedFee = $request->denda_kerusakan ?? 0;
            $totalFine = $totalLateFee + $damagedFee;

            // 3. Create Return Record
            $firstDetailId = $rental->details->first()->id;

            $return = Returns::create([
                'rental_id' => $rental->id,
                'rental_detail_id' => $firstDetailId, 
                'tanggal_kembali_aktual' => $request->tanggal_dikembalikan,
                'kondisi_baju' => $request->kondisi,
                'diterima_oleh' => \Illuminate\Support\Facades\Auth::id(),
                'catatan_kondisi' => "Telat: $daysLate hari. Kondisi: " . $request->kondisi,
            ]);

            // 4. Calculate Final Amount (Fines - Deposit)
            $deposit = $rental->deposit;
            $finalAmount = $totalFine - $deposit;

            // 5. Create Fine Record if applicable
            if ($totalFine > 0) {
                // Determine fine type
                $fineType = 'lainnya';
                if ($daysLate > 0 && $damagedFee == 0) $fineType = 'keterlambatan';
                if ($daysLate == 0 && $damagedFee > 0) $fineType = 'kerusakan';
                if ($request->kondisi == 'hilang') $fineType = 'kehilangan';

                Fine::create([
                    'rental_id' => $rental->id,
                    'return_id' => $return->id,
                    'jenis_denda' => $fineType,
                    'jumlah_denda' => $totalFine, 
                    'deskripsi' => "Telat $daysLate hari. Kondisi: " . $request->kondisi . ". Denda kerusakan: Rp " . number_format($damagedFee, 0, ',', '.'),
                    'status_denda' => 'lunas',
                    'tanggal_bayar_denda' => Carbon::now(),
                ]);
            }

            // 6. Handle Deposit Logic
            if ($finalAmount > 0) {
                // Scenario 3: Customer owes additional money (fines > deposit)
                if (!$request->metode_pembayaran) {
                    throw new \Exception('Metode pembayaran wajib diisi karena ada kekurangan pembayaran sebesar Rp ' . number_format($finalAmount, 0, ',', '.'));
                }

                Payment::create([
                    'rental_id' => $rental->id,
                    'jumlah_bayar' => $finalAmount,
                    'metode_bayar' => $request->metode_pembayaran,
                    'status_bayar' => 'lunas',
                    'tanggal_bayar' => Carbon::now(),
                    'catatan' => "Pembayaran kekurangan denda. Total denda: Rp " . number_format($totalFine, 0, ',', '.') . " - Deposit: Rp " . number_format($deposit, 0, ',', '.'),
                ]);

                $rental->update([
                    'catatan' => "Deposit Rp " . number_format($deposit, 0, ',', '.') . " digunakan untuk denda. Customer membayar kekurangan: Rp " . number_format($finalAmount, 0, ',', '.'),
                ]);
            } elseif ($finalAmount < 0) {
                // Scenario 2 or 1: Customer receives refund (fines < deposit or no fines)
                $refundAmount = abs($finalAmount);
                $rental->update([
                    'catatan' => "Deposit dikembalikan: Rp " . number_format($refundAmount, 0, ',', '.') . " (dari total deposit Rp " . number_format($deposit, 0, ',', '.') . " - denda Rp " . number_format($totalFine, 0, ',', '.') . ")",
                ]);
            } else {
                // Exact match: fines = deposit
                $rental->update([
                    'catatan' => "Deposit Rp " . number_format($deposit, 0, ',', '.') . " digunakan seluruhnya untuk pembayaran denda.",
                ]);
            }

            // 7. Restore Stock
            if ($request->kondisi !== 'hilang') {
                foreach ($rental->details as $detail) {
                    ClothesSize::where('id', $detail->clothes_size_id)->increment('stok');
                }
            }

            // 8. Update Rental Status
            $rental->update(['status' => 'selesai']);

            DB::commit();
            
            // Return message based on scenario
            if ($finalAmount > 0) {
                $message = "Pengembalian berhasil. Customer membayar kekurangan: Rp " . number_format($finalAmount, 0, ',', '.');
            } elseif ($finalAmount < 0) {
                $message = "Pengembalian berhasil. Kembalikan deposit: Rp " . number_format(abs($finalAmount), 0, ',', '.');
            } else {
                $message = "Pengembalian berhasil. Deposit digunakan seluruhnya untuk denda.";
            }
            
            return redirect()->route('rentals.show', $rental->id)->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
}
