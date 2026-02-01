@extends('layouts.app')

@section('title', 'Catat Sewa Baru')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Catat Transaksi Baru</h3>
                <p class="mt-1 text-sm text-gray-600">Pilih pelanggan dan barang yang akan disewa.</p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="{{ route('rentals.store') }}" method="POST" id="rentalForm">
                @csrf
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        
                        <!-- Customer Selection -->
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-4">
                                <label for="customer_id" class="block text-sm font-medium text-gray-700">Pilih Pelanggan</label>
                                <select id="customer_id" name="customer_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">-- Cari Pelanggan --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->nama }} ({{ $customer->no_telepon }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700">Tanggal Pinjam</label>
                                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" value="{{ date('Y-m-d') }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700">Tanggal Kembali</label>
                                <input type="date" name="tanggal_kembali" id="tanggal_kembali" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <hr class="border-gray-200 mt-6 mb-6">

                        <!-- Item Selection Section -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Pilih Barang Sewaan</h4>
                            <div id="items-container" class="mt-4 space-y-4">
                                <!-- Dynamic Item Row -->
                                <div class="item-row grid grid-cols-12 gap-x-4">
                                    <div class="col-span-5">
                                        <label class="block text-xs font-medium text-gray-500">Baju</label>
                                        <select name="items[0][cloth_id]" class="cloth-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" onchange="loadSizes(this, 0)">
                                            <option value="">-- Pilih Baju --</option>
                                            @foreach($clothes->groupBy(fn($item) => $item->category->nama_kategori ?? 'Lainnya') as $categoryName => $groupedClothes)
                                                <optgroup label="{{ $categoryName }}">
                                                    @foreach($groupedClothes as $cloth)
                                                        <option value="{{ $cloth->id }}" data-price="{{ $cloth->harga_sewa }}">{{ $cloth->nama_baju }} - Rp {{ number_format($cloth->harga_sewa, 0, ',', '.') }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-xs font-medium text-gray-500">Ukuran</label>
                                        <select name="items[0][clothes_size_id]" class="size-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" disabled>
                                            <option value="">Pilih Ukuran</option>
                                        </select>
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-xs font-medium text-gray-500">Harga</label>
                                        <input type="text" readonly class="price-display mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-50 text-gray-500" placeholder="Rp 0">
                                    </div>
                                    <div class="col-span-1 flex items-end">
                                        <button type="button" onclick="removeItem(this)" class="mb-1 text-red-600 hover:text-red-900">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="button" onclick="addItem()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Barang Lain
                                </button>
                            </div>
                        </div>

                        <hr class="border-gray-200 mt-6 mb-6">

                        <!-- Payment Section -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Informasi Pembayaran</h4>
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-4">
                                <p class="text-sm text-blue-800 font-medium mb-2">
                                    <i data-lucide="info" class="w-4 h-4 inline mr-1"></i> 
                                    Perhatian:
                                </p>
                                <ul class="text-sm text-blue-700 space-y-1 ml-5 list-disc">
                                    <li>Customer harus membayar <strong>Biaya Sewa</strong> + <strong>Deposit (Jaminan)</strong></li>
                                    <li>Deposit = 50% dari total biaya sewa (minimum Rp 100.000)</li>
                                    <li>Deposit akan dikembalikan saat pengembalian jika tidak ada denda</li>
                                </ul>
                            </div>

                            <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <label for="deposit" class="block text-sm font-medium text-gray-700">Nominal Deposit (Jaminan) <span class="text-red-500">*</span></label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="deposit" id="deposit" min="0" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full !pl-12 sm:text-sm border-gray-300 rounded-md" placeholder="0">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Otomatis dihitung 50% dari total sewa (min Rp 100.000). Bisa diubah manual.</p>
                                </div>

                                <div>
                                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran <span class="text-red-500">*</span></label>
                                    <select id="metode_pembayaran" name="metode_pembayaran" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">-- Pilih Metode --</option>
                                        <option value="cash">Cash</option>
                                        <option value="transfer_bank">Transfer Bank</option>
                                        <option value="e-wallet">E-Wallet (OVO, GoPay, Dana)</option>
                                        <option value="kartu_kredit">Kartu Kredit</option>
                                        <option value="kartu_debit">Kartu Debit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4 bg-gray-100 p-4 rounded-md flex justify-between items-center">
                                <span class="text-gray-700 font-medium">Total yang Harus Dibayar Customer:</span>
                                <span class="text-xl font-bold text-indigo-700" id="total-payment-display">Rp 0</span>
                            </div>
                        </div>

                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <a href="{{ route('rentals.index') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">Batal</a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Buat Transaksi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let itemCount = 1;
    const clothesData = @json($clothes);

    function addItem() {
        const container = document.getElementById('items-container');
        const index = itemCount++;
        const row = document.createElement('div');
        row.className = 'item-row grid grid-cols-12 gap-x-4 mt-4';
        
        let options = '<option value="">-- Pilih Baju --</option>';
        
        // Group by Category
        const grouped = {};
        clothesData.forEach(cloth => {
            const catName = cloth.category ? cloth.category.nama_kategori : 'Lainnya';
            if (!grouped[catName]) grouped[catName] = [];
            grouped[catName].push(cloth);
        });

        // Generate Optgroups
        for (const [category, items] of Object.entries(grouped)) {
            options += `<optgroup label="${category}">`;
            items.forEach(cloth => {
                options += `<option value="${cloth.id}" data-price="${cloth.harga_sewa}">${cloth.nama_baju} - Rp ${new Intl.NumberFormat('id-ID').format(cloth.harga_sewa)}</option>`;
            });
            options += `</optgroup>`;
        }

        row.innerHTML = `
            <div class="col-span-5">
                <select name="items[${index}][cloth_id]" class="cloth-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" onchange="loadSizes(this, ${index})">
                    ${options}
                </select>
            </div>
            <div class="col-span-3">
                <select name="items[${index}][clothes_size_id]" class="size-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" disabled>
                    <option value="">Pilih Ukuran</option>
                </select>
            </div>
            <div class="col-span-3">
                <input type="text" readonly class="price-display mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-50 text-gray-500" placeholder="Rp 0" data-raw-price="0">
            </div>
            <div class="col-span-1 flex items-end">
                <button type="button" onclick="removeItem(this)" class="mb-1 text-red-600 hover:text-red-900">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        lucide.createIcons();
        calculateTotals();
    }

    function removeItem(btn) {
        const row = btn.closest('.item-row');
        if(document.querySelectorAll('.item-row').length > 1) {
            row.remove();
            calculateTotals();
        } else {
            alert('Minimal harus ada satu barang.');
        }
    }

    function loadSizes(select, index) {
        const clothId = select.value;
        const row = select.closest('.item-row');
        const sizeSelect = row.querySelector('.size-select');
        const priceDisplay = row.querySelector('.price-display');
        
        // Update price
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        priceDisplay.value = price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(price) : '';
        priceDisplay.setAttribute('data-raw-price', price || 0);

        // Update sizes
        sizeSelect.innerHTML = '<option value="">Pilih Ukuran</option>';
        sizeSelect.disabled = true;

        if (clothId) {
            const cloth = clothesData.find(c => c.id == clothId);
            if (cloth && cloth.sizes) {
                cloth.sizes.forEach(size => {
                    if(size.stok > 0) {
                        sizeSelect.innerHTML += `<option value="${size.id}">${size.ukuran} (Sisa: ${size.stok})</option>`;
                    }
                });
                sizeSelect.disabled = false;
            }
        }
        calculateTotals();
    }

    function calculateTotals() {
        let totalRental = 0;
        document.querySelectorAll('.price-display').forEach(input => {
            totalRental += parseFloat(input.getAttribute('data-raw-price')) || 0;
        });

        // Calculate default deposit (50% min 100k)
        let defaultDeposit = Math.max(totalRental * 0.5, 100000);
        
        // Update deposit input placeholder and value if not manually edited yet?
        // Actually, let's just update the value if it's currently 0 or matches the old calculation
        // Or simpler: Just update it always unless user focuses? Let's just update it.
        const depositInput = document.getElementById('deposit');
        
        // Only update if user hasn't manually interacted? Hard to track.
        // Let's update it and let user change it.
        // If we want to be smarter, we can track 'dirty' state.
        // For now, let's just update it to guide the user.
        if (!depositInput.dataset.dirty) {
            depositInput.value = defaultDeposit;
        }

        const currentDeposit = parseFloat(depositInput.value) || 0;
        const totalPayment = totalRental + currentDeposit;

        document.getElementById('total-payment-display').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPayment);
    }

    document.getElementById('deposit').addEventListener('input', function() {
        this.dataset.dirty = "true";
        calculateTotals(); // Re-calculate total payment (rental + new deposit)
    });

    // Initial calculation on page load
    document.addEventListener('DOMContentLoaded', function() {
        calculateTotals();
    });
</script>
@endsection
