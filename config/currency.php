<?php

return [
    'default' => 'USD',
    'currencies' => [
        'USD' => [
            'name' => 'US Dollar',
            'symbol' => '$',
            'rate' => 1.0,
            'format' => '$%s',
        ],
        'IDR' => [
            'name' => 'Indonesian Rupiah',
            'symbol' => 'Rp',
            'rate' => 15500, // Example rate
            'format' => 'Rp %s',
        ],
        'EUR' => [
            'name' => 'Euro',
            'symbol' => '€',
            'rate' => 0.92, // Example rate
            'format' => '€%s',
        ],
        'JPY' => [
            'name' => 'Japanese Yen',
            'symbol' => '¥',
            'rate' => 150, // Example rate
            'format' => '¥%s',
        ],
    ],
];
