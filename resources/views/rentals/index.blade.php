@extends('layouts.app')

@section('title', 'Transaksi Sewa')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-2xl font-semibold text-gray-900">Transaksi Penyewaan</h1>
        <p class="mt-2 text-sm text-gray-700">Manajemen peminjaman dan pengembalian baju.</p>
    </div>
    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <a href="{{ route('rentals.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
            Catat Sewa Baru
        </a>
    </div>
</div>
<div class="mt-8 flex flex-col">
    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">ID Transaksi</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Pelanggan</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tgl Pinjam / Kembali</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total Biaya</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($rentals as $rental)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">#{{ $rental->kode_transaksi }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <div class="font-medium text-gray-900">{{ $rental->customer->nama }}</div>
                                <div class="text-xs text-gray-400">{{ $rental->customer->no_telepon }}</div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <div><span class="text-xs text-gray-400">Pinjam:</span> {{ \Carbon\Carbon::parse($rental->tanggal_pinjam)->format('d M Y') }}</div>
                                <div><span class="text-xs text-gray-400">Kembali:</span> {{ \Carbon\Carbon::parse($rental->tanggal_kembali)->format('d M Y') }}</div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-bold">
                                Rp {{ number_format($rental->total_biaya, 0, ',', '.') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                @if($rental->status == 'aktif')
                                    <span class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">
                                        Sedang Dipinjam
                                    </span>
                                    @if(\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($rental->tanggal_kembali)))
                                        <span class="block mt-1 text-xs text-red-500 font-bold">Terlambat!</span>
                                    @endif
                                @else
                                    <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                        Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('rentals.show', $rental) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Detail</a>
                                @if($rental->status == 'aktif')
                                    <a href="{{ route('returns.create', ['rental_id' => $rental->id]) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Proses Kembali
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $rentals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
