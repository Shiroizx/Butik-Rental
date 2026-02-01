@extends('layouts.app')

@section('title', 'Kelola Stok')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-2xl font-semibold text-gray-900">Kelola Stok: {{ $cloth->nama_baju }}</h1>
        <p class="mt-2 text-sm text-gray-700">Warna: {{ $cloth->warna }} | Kategori: {{ $cloth->category->nama_kategori }}</p>
    </div>
    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <a href="{{ route('clothes.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
            Kembali
        </a>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
    <!-- List of Sizes -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Stok yang Tersedia</h3>
        </div>
        <div class="border-t border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Stok</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sizes as $size)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $size->ukuran }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $size->stok }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('clothes-sizes.destroy', $size) }}" method="POST" onsubmit="return confirm('Hapus ukuran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="clothes_id" value="{{ $cloth->id }}">
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data stok. Tambahkan di formulir samping.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Update Form -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah / Update Stok</h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500">
                <p>Masukkan ukuran (S, M, L, XL, dll) dan jumlah stok yang ingin ditambahkan.</p>
            </div>
            <form class="mt-5" action="{{ route('clothes-sizes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="clothes_id" value="{{ $cloth->id }}">
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="ukuran" class="block text-sm font-medium text-gray-700">Ukuran</label>
                        <div class="mt-1">
                            <input type="text" name="ukuran" id="ukuran" required class="uppercase shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Contoh: L">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="stok" class="block text-sm font-medium text-gray-700">Jumlah Stok</label>
                        <div class="mt-1">
                            <input type="number" name="stok" id="stok" required min="1" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                        Simpan Stok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
