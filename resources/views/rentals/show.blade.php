@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('rentals.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Sewa
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Detail Transaksi #{{ $rental->kode_transaksi }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Informasi lengkap penyewaan.</p>
            </div>
            <div>
                @if($rental->status == 'aktif')
                    <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-sm font-semibold text-yellow-800">Sedang Dipinjam</span>
                @else
                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-800">Selesai / Dikembalikan</span>
                @endif
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Nama Pelanggan</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $rental->customer->nama }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Kontak</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $rental->customer->no_telepon }} / {{ $rental->customer->email }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Tanggal Pinjam</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ \Carbon\Carbon::parse($rental->tanggal_pinjam)->format('d F Y') }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Rencana Kembali</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ \Carbon\Carbon::parse($rental->tanggal_kembali)->format('d F Y') }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Dicatat Oleh</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $rental->employee->nama }} (Admin/Staf)</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Deposit (Jaminan)</dt>
                    <dd class="mt-1 text-sm font-semibold text-indigo-600 sm:mt-0 sm:col-span-2">Rp {{ number_format($rental->deposit, 0, ',', '.') }}</dd>
                </div>
                @if($rental->catatan)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Catatan Deposit</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $rental->catatan }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Items Table -->
    <div class="mt-8">
        <h4 class="text-lg font-medium text-gray-900 mb-4">Barang yang Disewa</h4>
        <div class="flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Barang</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kategori</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ukuran</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Harga Sewa</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($rental->details as $detail)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $detail->cloth->nama_baju }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $detail->cloth->category->nama_kategori }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $detail->clothesSize->ukuran }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total Biaya Sewa:</td>
                                    <td class="px-3 py-4 text-sm font-bold text-indigo-600">Rp {{ number_format($rental->total_biaya, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Payments Section -->
    @if($rental->payments && $rental->payments->count() > 0)
    <div class="mt-8">
        <h4 class="text-lg font-medium text-gray-900 mb-4">Riwayat Pembayaran</h4>
        <div class="flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Tanggal</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Jumlah</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Metode</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Keterangan</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($rental->payments as $payment)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900 sm:pl-6">{{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d/m/Y') }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 capitalize">{{ str_replace('_', ' ', $payment->metode_bayar) }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        @if($payment->status_bayar == 'lunas')
                                            <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">Lunas</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">{{ ucfirst($payment->status_bayar) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500">{{ $payment->catatan ?? '-' }}</td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        @if($payment->status_bayar != 'lunas')
                                            <form action="{{ route('payments.updateStatus', $payment->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin melunasi pembayaran ini?')">
                                                @csrf
                                                <button type="submit" class="text-indigo-600 hover:text-indigo-900">Lunasi</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if($rental->returns)
        <!-- Return Info if exists -->
        <div class="mt-8 bg-green-50 overflow-hidden shadow sm:rounded-lg border border-green-200">
             <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-green-900">Informasi Pengembalian</h3>
            </div>
            <div class="border-t border-green-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-green-200">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-green-700">Tanggal Dikembalikan</dt>
                        <dd class="mt-1 text-sm text-green-900 sm:mt-0 sm:col-span-2">{{ \Carbon\Carbon::parse($rental->returns->tanggal_kembali_aktual)->format('d F Y') }}</dd>
                    </div>
                     <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-green-700">Kondisi</dt>
                        <dd class="mt-1 text-sm text-green-900 sm:mt-0 sm:col-span-2 capitalize">{{ str_replace('_', ' ', $rental->returns->kondisi_baju) }}</dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-green-700">Total Denda</dt>
                        <dd class="mt-1 text-sm text-green-900 sm:mt-0 sm:col-span-2">
                            Rp {{ number_format($rental->returns->fine->jumlah_denda ?? 0, 0, ',', '.') }}
                            @if(optional($rental->returns->fine)->deskripsi)
                                <br>
                                <span class="text-xs text-green-600">{{ $rental->returns->fine->deskripsi }}</span>
                            @else
                                <br>
                                <span class="text-xs text-green-600">Tidak ada denda</span>
                            @endif
                        </dd>
                    </div>
                    <!-- New Financial Breakdown Section -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-700">Rincian Keuangan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                             <div class="border rounded-md overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-500">Deposit Awal</td>
                                            <td class="px-4 py-2 text-sm font-medium text-gray-900 text-right">Rp {{ number_format($rental->deposit, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-500">Total Denda</td>
                                            <td class="px-4 py-2 text-sm font-medium text-red-600 text-right">- Rp {{ number_format($rental->returns->fine->jumlah_denda ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                        <!-- Calculation Result -->
                                        @php
                                            $deposit = $rental->deposit;
                                            $denda = $rental->returns->fine->jumlah_denda ?? 0;
                                            $selisih = $deposit - $denda;
                                        @endphp
                                        <tr class="bg-gray-50">
                                            <td class="px-4 py-2 text-sm font-bold text-gray-900">
                                                @if($selisih >= 0)
                                                    Sisa Deposit (Dikembalikan ke Customer)
                                                @else
                                                    Kekurangan (Customer Harus Bayar)
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-sm font-bold {{ $selisih >= 0 ? 'text-green-600' : 'text-red-600' }} text-right">
                                                Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </dd>
                    </div>

                    <!-- Final Note -->
                     <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-green-700">Catatan Penyelesaian</dt>
                        <dd class="mt-1 text-sm text-green-900 sm:mt-0 sm:col-span-2">
                            {{ $rental->catatan ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    @endif

</div>
@endsection
