<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Customer;
use App\Models\Cloth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            ->where('tanggal_kembali', '<', now())
            ->count();

        // 1. Chart Data: Monthly Income (Last 6 Months)
        $monthlyIncome = Rental::select(
            DB::raw('sum(total_biaya) as revenue'),
            DB::raw("DATE_FORMAT(tanggal_pinjam, '%Y-%m') as month_year"),
            DB::raw('MONTH(tanggal_pinjam) as month')
        )
        ->whereYear('tanggal_pinjam', date('Y'))
        ->groupBy('month_year', 'month')
        ->orderBy('month_year', 'asc')
        ->limit(6)
        ->get();

        $incomeLabels = [];
        $incomeData = [];
        foreach ($monthlyIncome as $data) {
            $incomeLabels[] = Carbon::createFromFormat('!m', $data->month)->format('F');
            $incomeData[] = $data->revenue;
        }

        // 2. Chart Data: Top Categories
        $topCategories = DB::table('rental_details')
            ->join('clothes', 'rental_details.clothes_id', '=', 'clothes.id')
            ->join('categories', 'clothes.category_id', '=', 'categories.id')
            ->select('categories.nama_kategori', DB::raw('count(*) as total'))
            ->groupBy('categories.nama_kategori')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        $categoryLabels = $topCategories->pluck('nama_kategori');
        $categoryData = $topCategories->pluck('total');

        // 3. Chart Data: Rental Status Distribution
        $statusDistribution = Rental::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        
        $statusLabels = $statusDistribution->pluck('status')->map(function($status) {
            return ucfirst($status);
        });
        $statusData = $statusDistribution->pluck('total');

        // 4. Chart Data: Payment Methods
        $paymentMethods = DB::table('payments')
            ->select('metode_bayar', DB::raw('count(*) as total'))
            ->groupBy('metode_bayar')
            ->orderByDesc('total')
            ->get();
        
        $paymentLabels = $paymentMethods->pluck('metode_bayar')->map(function($method) {
            return str_replace('_', ' ', ucfirst($method));
        });
        $paymentData = $paymentMethods->pluck('total');

        return view('dashboard.index', compact(
            'totalRentals',
            'totalCustomers',
            'totalInventory',
            'overdueReturns',
            'incomeLabels',
            'incomeData',
            'categoryLabels',
            'categoryData',
            'statusLabels',
            'statusData',
            'paymentLabels',
            'paymentData'
        ));
    }
}
