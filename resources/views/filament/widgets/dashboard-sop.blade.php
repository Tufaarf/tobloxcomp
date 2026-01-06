<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Header Judul --}}
        <div class="flex items-center gap-x-3 mb-4">
            {{-- Ikon --}}
            <x-filament::icon
                icon="heroicon-o-book-open"
                class="w-8 h-8 text-primary-500"
            />
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                SOP & Panduan Dashboard
            </h2>
        </div>

        {{-- Isi Konten --}}
        <div class="text-sm text-white-600 dark:text-gray-300">
            <p class="mb-4">
                Selamat datang di halaman Administrator. Silakan ikuti petunjuk di bawah ini untuk pengelolaan data:
            </p>

            <div class="space-y-4">
                {{-- SOP List --}}
                <div class="space-y-3">
                    <h3 class="font-semibold text-white-900 dark:text-white mb-4">Prosedur Operasional Standar:</h3>

                    <ul class="list-disc list-inside space-y-2 text-white-700 dark:text-gray-300">
                        <li><strong>Verifikasi Data User:</strong> Periksa identitas dan akun game pengguna sebelum memproses top-up</li>
                        <li><strong>Pilih Nominal Top-Up:</strong> Tentukan jumlah Robux atau currency yang akan ditambahkan sesuai permintaan</li>
                        <li><strong>Konfirmasi Payment Method:</strong> Validasi metode pembayaran yang dipilih (Transfer Bank, E-wallet, dll)</li>
                        <li><strong>Proses Transaksi:</strong> Eksekusi transaksi top-up melalui sistem payment gateway yang terdaftar</li>
                        <li><strong>Update Status Order:</strong> Ubah status order menjadi "Completed" atau "Processing" sesuai hasil transaksi</li>
                        <li><strong>Notifikasi ke User:</strong> Kirimkan konfirmasi top-up via email atau notifikasi in-app</li>
                        <li><strong>Log Transaksi:</strong> Catat semua aktivitas top-up untuk audit trail dan laporan keuangan</li>
                        <li><strong>Monitoring Server:</strong> Pastikan server dan sistem berjalan normal untuk menghindari gangguan layanan</li>
                        <li><strong>Handle Keluhan:</strong> Respons dan selesaikan keluhan customer dengan cepat dan profesional</li>
                        <li><strong>Laporan Harian:</strong> Buat laporan penjualan dan transaksi top-up setiap hari</li>
                    </ul>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
