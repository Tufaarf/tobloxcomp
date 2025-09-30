<?php

return [
    // Harga dasar Robux
    'price_per_50' => 7000,

    // Metode bayar (hardcode di config)
    'methods' => [
        'qris' => [
            'name'   => 'QRIS',
            'fee'    => 3.0,               // %
            'type'   => 'image',           // image = pakai gambar QR
            'target' => 'payments/qris.png', // path di storage/public (lihat catatan storage:link)
        ],
        'gopay' => [
            'name'   => 'GoPay',
            'fee'    => 2.0,
            'type'   => 'text',            // text = tampilkan nomor/akun
            'target' => '0812-3456-7890 a/n Toblox ID', // nomor tujuan
        ],
        'seabank' => [
            'name'   => 'SeaBank',
            'fee'    => 1.5,
            'type'   => 'text',
            'target' => '9010 1234 5678 9012 a/n Toblox ID', // no. rekening
        ],
    ],
];
