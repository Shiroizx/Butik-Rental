@extends('layouts.app')

@section('title', 'Proses Pengembalian')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('rentals.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Batal / Kembali
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Proses Pengembalian Barang</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">ID Transaksi: #{{ $rental->id }} | Pelanggan: {{ $rental->customer->nama }}</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
             <form action="{{ route('returns.store') }}" method="POST">
                @csrf
                <input type="hidden" name="rental_id" value="{{ $rental->id }}">

                <div class="grid grid-cols-1 gap-y-6">
                    
                    <!-- Rental Info Summary -->
                    <div class="bg-gray-50 p-4 rounded-md text-sm">
                        <p><strong>Tanggal Pinjam:</strong> {{ \Carbon\Carbon::parse($rental->tanggal_pinjam)->format('d F Y') }}</p>
                        <p><strong>Jadwal Kembali:</strong> {{ \Carbon\Carbon::parse($rental->tanggal_kembali)->format('d F Y') }}</p>
                        <p><strong>Total Barang:</strong> {{ $rental->details->count() }} Pcs</p>
                    </div>

                    <!-- Deposit Information -->
                    <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                        <h4 class="text-sm font-semibold text-green-800 mb-2">
                            <i data-lucide="shield-check" class="w-4 h-4 inline mr-1"></i>
                            Informasi Deposit
                        </h4>
                        <p class="text-sm text-green-700">Deposit yang sudah dibayarkan: <strong class="text-green-900">Rp {{ number_format($rental->deposit, 0, ',', '.') }}</strong></p>
                        <p class="text-xs text-green-600 mt-1">Deposit akan dikembalikan jika tidak ada denda. Jika ada denda, deposit akan dipotong terlebih dahulu.</p>
                    </div>

                    <!-- Actual Return Date -->
                    <div>
                        <label for="tanggal_dikembalikan" class="block text-sm font-medium text-gray-700">Tanggal Dikembalikan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_dikembalikan" id="tanggal_dikembalikan" value="{{ $today }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p class="mt-1 text-xs text-gray-500" id="late-info">Denda keterlambatan akan dihitung otomatis jika melewati tanggal jadwal kembali.</p>
                    </div>

                    <!-- Condition -->
                    <div>
                        <label for="kondisi" class="block text-sm font-medium text-gray-700">Kondisi Barang</label>
                        <select id="kondisi" name="kondisi" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="baik">Baik / Normal</option>
                            <option value="rusak_ringan">Rusak Ringan (Jahit/Kancing)</option>
                            <option value="rusak_berat">Rusak Berat (Robek/Lunturable)</option>
                            <option value="kotor">Kotor (Perlu Laundry)</option>
                            <option value="hilang">Hilang (Ganti Rugi Penuh)</option>
                        </select>
                    </div>

                    <!-- Damaged Fine -->
                    <div id="denda-wrapper">
                        <label for="denda_kerusakan" class="block text-sm font-medium text-gray-700">Denda Tambahan (Kerusakan / Hilang)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="denda_kerusakan" id="denda_kerusakan" value="0" min="0" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Isi nominal denda jika ada kerusakan atau barang hilang.</p>
                    </div>

                    <!-- Payment Method (Conditional) -->
                     <div id="payment-wrapper" style="display: none;">
                        <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran <span class="text-red-500" id="payment-required">*</span></label>
                        <select id="metode_pembayaran" name="metode_pembayaran" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">-- Pilih Metode --</option>
                            <option value="cash">Cash</option>
                            <option value="transfer_bank">Transfer Bank</option>
                            <option value="e-wallet">E-Wallet</option>
                            <option value="kartu_kredit">Kartu Kredit</option>
                            <option value="kartu_debit">Kartu Debit</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500" id="payment-note"></p>
                    </div>

                    <!-- Summary Box -->
                    <div id="summary-box" class="bg-gray-50 border border-gray-300 rounded-md p-4">
                        <h5 class="text-sm font-semibold text-gray-700 mb-2">Ringkasan Pembayaran</h5>
                        <div class="text-sm space-y-1">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Deposit Dibayar:</span>
                                <span class="font-medium">Rp {{ number_format($rental->deposit, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Denda:</span>
                                <span class="font-medium" id="total-denda-display">Rp 0</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between text-base font-bold">
                                <span id="final-label">Dikembalikan ke Customer:</span>
                                <span id="final-amount" class="text-green-600">Rp {{ number_format($rental->deposit, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Simpan & Selesaikan Transaksi
                        </button>
                    </div>

                </div>
             </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kondisiSelect = document.getElementById('kondisi');
        const dendaWrapper = document.getElementById('denda-wrapper');
        const paymentWrapper = document.getElementById('payment-wrapper');
        const tanggalKembaliInput = document.getElementById('tanggal_dikembalikan');
        const dendaInput = document.getElementById('denda_kerusakan');
        const paymentInput = document.getElementById('metode_pembayaran');
        const paymentNote = document.getElementById('payment-note');
        const paymentRequired = document.getElementById('payment-required');
        const totalDendaDisplay = document.getElementById('total-denda-display');
        const finalLabel = document.getElementById('final-label');
        const finalAmount = document.getElementById('final-amount');
        
        // Rental data from server
        const jadwalKembali = new Date('{{ $rental->tanggal_kembali }}');
        const deposit = {{ $rental->deposit }};
        const lateFeePerDay = 50000;

        function calculateAndUpdate() {
            const kondisi = kondisiSelect.value;
            
            // Compare dates without time
            const tglKembaliDate = new Date(tanggalKembaliInput.value);
            tglKembaliDate.setHours(0,0,0,0);
            
            const jadwalKembaliDate = new Date('{{ $rental->tanggal_kembali }}');
            jadwalKembaliDate.setHours(0,0,0,0);
            
            // Calculate late days
            let daysLate = 0;
            if (tglKembaliDate > jadwalKembaliDate) {
                daysLate = Math.floor((tglKembaliDate - jadwalKembaliDate) / (1000 * 60 * 60 * 24));
            }

            // Calculate Late Fee
            const totalLateFee = daysLate * lateFeePerDay;

            // Get Damage Fee
            const damageFee = parseFloat(dendaInput.value) || 0;

            // Total Fines
            const totalFines = totalLateFee + damageFee;

            // Final Amount (fines - deposit)
            const final = totalFines - deposit;

            // Update display
            totalDendaDisplay.textContent = 'Rp ' + totalFines.toLocaleString('id-ID');

            // Determine UI states
            if (kondisi !== 'baik' || daysLate > 0) {
                dendaWrapper.style.display = 'block';
            } else {
                dendaWrapper.style.display = 'none';
                dendaInput.value = 0;
            }

            // Payment logic
            if (final > 0) {
                // Customer owes additional money
                paymentWrapper.style.display = 'block';
                paymentRequired.style.display = 'inline';
                paymentInput.setAttribute('required', 'required');
                paymentNote.textContent = 'Customer harus membayar kekurangan sebesar Rp ' + final.toLocaleString('id-ID');
                
                finalLabel.textContent = 'Customer Bayar Tambahan:';
                finalAmount.textContent = 'Rp ' + final.toLocaleString('id-ID');
                finalAmount.className = 'text-red-600 font-bold';
            } else if (final < 0) {
                // Customer gets refund
                paymentWrapper.style.display = 'none';
                paymentRequired.style.display = 'none';
                paymentInput.removeAttribute('required');
                paymentInput.value = '';
                
                const refund = Math.abs(final);
                finalLabel.textContent = 'Dikembalikan ke Customer:';
                finalAmount.textContent = 'Rp ' + refund.toLocaleString('id-ID');
                finalAmount.className = 'text-green-600 font-bold';
            } else {
                // Exact match
                paymentWrapper.style.display = 'none';
                paymentRequired.style.display = 'none';
                paymentInput.removeAttribute('required');
                paymentInput.value = '';
                
                finalLabel.textContent = 'Status:';
                finalAmount.textContent = 'Deposit habis untuk denda';
                finalAmount.className = 'text-gray-600 font-bold';
            }
        }

        kondisiSelect.addEventListener('change', calculateAndUpdate);
        tanggalKembaliInput.addEventListener('change', calculateAndUpdate);
        dendaInput.addEventListener('input', calculateAndUpdate);

        // Initial calculation
        calculateAndUpdate();
    });
</script>
@endsection
