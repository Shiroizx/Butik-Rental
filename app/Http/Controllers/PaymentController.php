<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        
        $payment->update([
            'status_bayar' => 'lunas',
            'tanggal_bayar' => now() // Update payment date to now when confirming
        ]);

        return back()->with('success', 'Status pembayaran berhasil diperbarui menjadi Lunas.');
    }
}
