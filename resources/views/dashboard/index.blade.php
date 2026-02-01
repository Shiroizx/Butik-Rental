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
@endsection
