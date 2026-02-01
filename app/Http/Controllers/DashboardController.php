<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Customer;
use App\Models\Cloth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRentals = Rental::count();
        $totalCustomers = Customer::count();
        $totalInventory = Cloth::count();
        
        // Example logic for overdue (rentals not returned past due date)
        $overdueReturns = Rental::where('status', 'aktif')
            ->where('tanggal_kembali', '<', now())
            ->count();

        return view('dashboard.index', compact(
            'totalRentals',
            'totalCustomers',
            'totalInventory',
            'overdueReturns'
        ));
    }
}
