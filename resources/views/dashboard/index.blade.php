@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="mt-1 text-sm text-gray-600">Selamat datang kembali, {{ Auth::user()->nama }}!</p>
</div>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Card 1: Rentals -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="shopping-bag" class="h-6 w-6 text-indigo-400"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Penyewaan</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $totalRentals }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('rentals.index') }}" class="font-medium text-indigo-600 hover:text-indigo-900">Lihat semua transaksi</a>
            </div>
        </div>
    </div>

    <!-- Card 2: Customers -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="users" class="h-6 w-6 text-green-400"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Pelanggan</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $totalCustomers }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('customers.index') }}" class="font-medium text-indigo-600 hover:text-indigo-900">Lihat pelanggan</a>
            </div>
        </div>
    </div>

    <!-- Card 3: Inventory -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="shirt" class="h-6 w-6 text-blue-400"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Inventaris</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $totalInventory }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('clothes.index') }}" class="font-medium text-indigo-600 hover:text-indigo-900">Lihat inventaris</a>
            </div>
        </div>
    </div>

    <!-- Card 4: Overdue -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="alert-triangle" class="h-6 w-6 text-red-400"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Pengembalian Terlambat</dt>
                        <dd class="text-3xl font-semibold text-red-600">{{ $overdueReturns }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('rentals.index') }}" class="font-medium text-indigo-600 hover:text-indigo-900">Proses pengembalian</a>
            </div>
        </div>
    </div>
    </div>

    <!-- Charts Section -->
    <div class="mt-8 grid grid-cols-1 gap-5 lg:grid-cols-2">
        <!-- Revenue Chart -->
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Pendapatan Bulanan</h3>
            <div class="relative h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Categories Chart -->
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Kategori Terlaris</h3>
            <div class="relative h-64 flex justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2">
        <!-- Rental Status Chart -->
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Status Penyewaan</h3>
            <div class="relative h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Payment Methods Chart -->
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Metode Pembayaran</h3>
            <div class="relative h-64 flex justify-center">
                <canvas id="paymentChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Shared Colors
        const goldColor = '#D4AF37';
        const darkGold = '#B5952F';
        const ivory = '#F9F9F7';
        const slate = '#707070';
        
        // 1. Revenue Chart (Line)
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: @json($incomeLabels), // ['January', 'February', ...]
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: @json($incomeData),
                    borderColor: '#D4AF37',
                    backgroundColor: 'rgba(212, 175, 55, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(112, 112, 112, 0.1)' },
                        ticks: { color: '#707070' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#707070' }
                    }
                }
            }
        });

        // 2. Category Chart (Doughnut)
        const ctxCategory = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctxCategory, {
            type: 'doughnut',
            data: {
                labels: @json($categoryLabels),
                datasets: [{
                    data: @json($categoryData),
                    backgroundColor: [
                        '#D4AF37', // Gold
                        '#1A1A1B', // Charcoal
                        '#707070', // Slate
                        '#F9F9F7', // Ivory (Bordered)
                        '#B5952F'  // Dark Gold
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { color: '#707070' }
                    }
                }
            }
        });

        // 3. Rental Status Chart (Bar)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'bar',
            data: {
                labels: @json($statusLabels),
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: @json($statusData),
                    backgroundColor: [
                        '#D4AF37', // Gold
                        '#1A1A1B', // Charcoal
                        '#707070', // Slate
                        '#B5952F'  // Dark Gold
                    ],
                    borderColor: '#D4AF37',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(112, 112, 112, 0.1)' },
                        ticks: { color: '#707070', stepSize: 1 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#707070' }
                    }
                }
            }
        });

        // 4. Payment Methods Chart (Pie)
        const ctxPayment = document.getElementById('paymentChart').getContext('2d');
        new Chart(ctxPayment, {
            type: 'pie',
            data: {
                labels: @json($paymentLabels),
                datasets: [{
                    data: @json($paymentData),
                    backgroundColor: [
                        '#D4AF37', // Gold
                        '#1A1A1B', // Charcoal
                        '#707070', // Slate
                        '#F9F9F7', // Ivory
                        '#B5952F'  // Dark Gold
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#707070', padding: 15 }
                    }
                }
            }
        });
    });
</script>
@endsection
