<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\RentalDetail;
use App\Models\Customer;
use App\Models\Cloth;
use App\Models\ClothesSize;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rentals = Rental::with('customer', 'employee')->latest()->paginate(10);
        return view('rentals.index', compact('rentals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $clothes = Cloth::with(['sizes', 'category'])->whereHas('sizes', function($q) {
            $q->where('stok', '>', 0);
        })->get();

        return view('rentals.create', compact('customers', 'clothes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'deposit' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.cloth_id' => 'required|exists:clothes,id',
            'items.*.clothes_size_id' => 'required|exists:clothes_sizes,id',
            'metode_pembayaran' => 'required|string|in:cash,transfer_bank,e-wallet,kartu_kredit,kartu_debit',
        ]);

        DB::beginTransaction();

        try {
            $totalBiaya = 0;
            
            // 1. Process Items and Calculate Total
            $itemsData = [];
            
            foreach ($request->items as $item) {
                // Check Stock
                $size = ClothesSize::where('id', $item['clothes_size_id'])
                                   ->where('clothes_id', $item['cloth_id'])
                                   ->lockForUpdate()
                                   ->first();

                if (!$size || $size->stok < 1) {
                    throw new \Exception("Stok habis untuk baju salah satu item yang dipilih.");
                }

                // Get Price
                $cloth = Cloth::find($item['cloth_id']);
                $price = $cloth->harga_sewa; 
                $totalBiaya += $price;

                $itemsData[] = [
                    'size' => $size,
                    'cloth' => $cloth,
                    'price' => $price
                ];
            }

            // 2. Use Deposit from Request
            $deposit = $request->deposit;

            // 3. Create Rental Header
            $rental = Rental::create([
                'kode_transaksi' => 'TRX-' . time() . '-' . Str::random(4),
                'customer_id' => $request->customer_id,
                'employee_id' => Auth::id(),
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'total_biaya' => $totalBiaya,
                'deposit' => $deposit,
                'status' => 'aktif',
            ]);

            // 4. Create Rental Details and Decrement Stock
            foreach ($itemsData as $itemData) {
                $itemData['size']->decrement('stok');
                
                RentalDetail::create([
                    'rental_id' => $rental->id,
                    'clothes_id' => $itemData['cloth']->id,
                    'clothes_size_id' => $itemData['size']->id,
                    'harga_satuan' => $itemData['price'],
                    'subtotal' => $itemData['price'], 
                    'jumlah' => 1
                ]);
            }

            // 5. Create Payment Record for Rental Fee + Deposit
            $totalBayarInitial = $totalBiaya + $deposit;

            Payment::create([
                'rental_id' => $rental->id,
                'jumlah_bayar' => $totalBayarInitial,
                'metode_bayar' => $request->metode_pembayaran,
                'status_bayar' => 'pending',
                'tanggal_bayar' => null,
                'catatan' => "Pembayaran Awal: Biaya Sewa (Rp " . number_format($totalBiaya, 0, ',', '.') . ") + Deposit (Rp " . number_format($deposit, 0, ',', '.') . ")",
            ]);

            DB::commit();
            return redirect()->route('rentals.show', $rental->id)->with('success', 'Transaksi sewa berhasil dibuat. Total biaya: Rp ' . number_format($totalBiaya, 0, ',', '.') . ' + Deposit: Rp ' . number_format($deposit, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rental $rental)
    {
        // Load relationships: returns (for fine), payments, details, etc.
        // Assuming 'returns' relation exists in Rental model.
        // If fine is attached to returns, we load 'returns.fine'
        $rental->load(['details.cloth.category', 'details.clothesSize', 'customer', 'employee', 'returns.fine', 'payments']);
        
        return view('rentals.show', compact('rental'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rental $rental)
    {
        return redirect()->route('rentals.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rental $rental)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rental $rental)
    {
         if ($rental->status == 'aktif') {
             return back()->with('error', 'Tidak bisa menghapus transaksi yang sedang aktif. Harap selesaikan pengembalian terlebih dahulu.');
         }
         $rental->delete();
         return redirect()->route('rentals.index')->with('success', 'Riwayat transaksi berhasil dihapus.');
    }
}
