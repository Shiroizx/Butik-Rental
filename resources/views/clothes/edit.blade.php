@extends('layouts.app')

@section('title', 'Ubah Baju')

@section('content')
@php // debug removed @endphp
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Ubah Data Baju</h3>
                <p class="mt-1 text-sm text-gray-600">Perbarui informasi produk baju.</p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ url('clothes/' . $cloth->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            
                            <div class="col-span-6">
                                <label for="nama_baju" class="block text-sm font-medium text-gray-700">Nama Baju</label>
                                <input type="text" name="nama_baju" id="nama_baju" value="{{ old('nama_baju', $cloth->nama_baju) }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select id="category_id" name="category_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $cloth->category_id == $category->id ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="harga_sewa" class="block text-sm font-medium text-gray-700">Harga Sewa (per Hari)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="harga_sewa" id="harga_sewa" value="{{ old('harga_sewa', $cloth->harga_sewa) }}" required min="0" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full !pl-12 sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="warna" class="block text-sm font-medium text-gray-700">Warna</label>
                                <input type="text" name="warna" id="warna" value="{{ old('warna', $cloth->warna) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                             <div class="col-span-6">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Detail</label>
                                <textarea name="deskripsi" id="deskripsi" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('deskripsi', $cloth->deskripsi) }}</textarea>
                            </div>

                            <div class="col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Foto Produk (Biarkan kosong jika tidak diganti)</label>
                                @if($cloth->gambar)
                                    <div class="mt-2 mb-2">
                                        <img src="{{ asset('storage/' . $cloth->gambar) }}" alt="Current Image" class="h-24 w-24 object-cover rounded-md">
                                    </div>
                                @endif
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <i data-lucide="image" class="mx-auto h-12 w-12 text-gray-400"></i>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload file baru</span>
                                                <input id="gambar" name="gambar" type="file" class="sr-only" accept="image/*">
                                            </label>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <a href="{{ route('clothes.index') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">Batal</a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
