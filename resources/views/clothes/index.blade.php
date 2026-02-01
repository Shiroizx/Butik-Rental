@extends('layouts.app')

@section('title', 'Inventaris Baju')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-2xl font-semibold text-gray-900">Inventaris Baju</h1>
        <p class="mt-2 text-sm text-gray-700">Daftar koleksi baju dan manajemen stok.</p>
    </div>
    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <a href="{{ route('clothes.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
            Tambah Baju
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
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Info Produk</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kategori</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Warna</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Harga Sewa</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Stok (Size)</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($clothes as $cloth)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        @if($cloth->gambar)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $cloth->gambar) }}" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i data-lucide="image" class="h-5 w-5 text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $cloth->nama_baju }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $cloth->category->nama_kategori }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $cloth->warna }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-medium">Rp {{ number_format($cloth->harga_sewa, 0, ',', '.') }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">
                                @if($cloth->sizes->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($cloth->sizes as $size)
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                {{ $size->ukuran }}: {{ $size->stok }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-red-500 text-xs italic">Kosong</span>
                                @endif
                                <div class="mt-2">
                                    <a href="{{ route('clothes-sizes.index', ['clothes_id' => $cloth->id]) }}" class="text-xs font-semibold text-green-600 hover:text-green-800 bg-green-50 px-2 py-1 rounded border border-green-200">
                                        + Kelola Stok
                                    </a>
                                </div>
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('clothes.edit', $cloth) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Ubah</a>
                                <form action="{{ route('clothes.destroy', $cloth) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data baju ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $clothes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
