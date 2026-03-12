<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Category;
use App\Models\Destination;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define your new package templates here.
        // COPY THIS TEMPLATE TO GEMINI FOR DATA GENERATION.
        $packages = [
            // PAKET 4: Osaka Culinary
            [
                'title'         => 'Osaka Autumn Food Tour',
                'slug'          => 'osaka-autumn-food-tour',
                'description'   => 'Rasakan pengalaman kuliner tak terlupakan di "Dapur Jepang". Nikmati street food Dotonbori dan pelajari sejarah lokal di sekitar Kastil Osaka.',
                'location_text' => 'Osaka',
                'duration_days' => 3,
                'group_size'    => 'Max 8',
                'language'      => 'English',
                'is_guided'     => true,
                'base_price'    => 950.00,
                
                'type'          => 'activity', 
                'season'        => 'Autumn',
                'is_trending'   => true,
                
                'destination_slug' => 'osaka',
                'categories'    => ['Foodie', 'Culinary', 'History'],
                
                'images'        => [
                    [
                        'image_path' => null, // Dummy Dotonbori
                        'is_primary' => true
                    ]
                ],
                
                'itineraries'   => [
                    [
                        'day_number'  => 1,
                        'title'       => 'Dotonbori Night Walk',
                        'description' => 'Bertemu di Namba, menikmati Takoyaki dan Okonomiyaki otentik sambil menyusuri sungai Dotonbori.',
                        'image_path'  => null,
                    ],
                    [
                        'day_number'  => 2,
                        'title'       => 'Kuromon Market & Osaka Castle',
                        'description' => 'Sarapan seafood segar di Pasar Kuromon, lalu mempelajari sejarah Jepang di Kastil Osaka pada sore harinya.',
                    ],
                    [
                        'day_number'  => 3,
                        'title'       => 'Shinsekai Experience',
                        'description' => 'Merasakan atmosfir retro Osaka di Shinsekai sambil mencicipi Kushikatsu sebelum tur selesai.',
                    ]
                ],
                
                'schedules'     => [
                    [
                        'start_date'      => '2024-10-10',
                        'end_date'        => '2024-10-12',
                        'price'           => 950.00,
                        'quota'           => 8,
                        'available_seats' => 0,
                        'status'          => 'completed', // Simulasi trip yang sudah lewat/selesai
                    ],
                    [
                        'start_date'      => '2024-11-05',
                        'end_date'        => '2024-11-07',
                        'price'           => 950.00,
                        'quota'           => 8,
                        'available_seats' => 4,
                        'status'          => 'available', 
                    ]
                ],
                
                'inclusions'    => [
                    ['item' => 'Voucher Makan Malam di Dotonbori', 'is_included' => true],
                    ['item' => 'Pemandu Kuliner Lokal', 'is_included' => true],
                    ['item' => 'Asuransi Perjalanan', 'is_included' => false],
                ]
            ],
            // PAKET 5: Hokkaido Winter
            [
                'title'         => 'Hokkaido Winter Snow Festival',
                'slug'          => 'hokkaido-winter-snow-festival',
                'description'   => 'Bergabunglah dalam open trip epik menyusuri festival salju Sapporo yang ikonik. Nikmati keindahan kanal Otaru yang membeku dan rasakan kehangatan onsen di tengah hamparan salju.',
                'location_text' => 'Sapporo • Otaru • Niseko',
                'duration_days' => 6,
                'group_size'    => 'Max 20',
                'language'      => 'Indonesian',
                'is_guided'     => true,
                'base_price'    => 2100.00,
                
                'type'          => 'open', 
                'season'        => 'Winter',
                'is_trending'   => true,
                
                'destination_slug' => 'hokkaido',
                'categories'    => ['Nature', 'Onsen', 'Culinary'],
                
                'images'        => [
                    [
                        'image_path' => null,
                        'is_primary' => true
                    ],
                    [
                        'image_path' => null,
                        'is_primary' => false
                    ]
                ],
                
                'itineraries'   => [
                    [
                        'day_number'  => 1,
                        'title'       => 'Kedatangan di New Chitose',
                        'description' => 'Bertemu dengan Tour Leader di bandara, transfer ke hotel di Sapporo, dan makan malam penyambutan dengan menu Genghis Khan.',
                        'image_path'  => null,
                    ],
                    [
                        'day_number'  => 2,
                        'title'       => 'Sapporo Snow Festival',
                        'description' => 'Menghabiskan seharian penuh menikmati karya seni pahatan es raksasa di Odori Park dan Susukino.',
                    ],
                    [
                        'day_number'  => 3,
                        'title'       => 'Otaru Canal Tour',
                        'description' => 'Perjalanan ke kota pelabuhan Otaru, mengunjungi museum kotak musik, dan berfoto di sepanjang kanal bersalju.',
                        'image_path'  => null,
                    ],
                    [
                        'day_number'  => 4,
                        'title'       => 'Niseko Ski Resort',
                        'description' => 'Bermain salju atau mencoba ski pemula di Niseko, dilanjutkan dengan bersantai di Onsen lokal.',
                    ],
                    [
                        'day_number'  => 5,
                        'title'       => 'Sapporo City Tour & Shopping',
                        'description' => 'Waktu bebas berbelanja suvenir di Tanukikoji Shopping Arcade dan mencicipi Shiroi Koibito Park.',
                    ],
                    [
                        'day_number'  => 6,
                        'title'       => 'Sayonara Hokkaido',
                        'description' => 'Check-out hotel dan transfer kembali ke bandara New Chitose untuk penerbangan pulang.',
                    ]
                ],
                
                'schedules'     => [
                    [
                        'start_date'      => '2026-12-15',
                        'end_date'        => '2026-12-20',
                        'price'           => 2100.00,
                        'quota'           => 20,
                        'available_seats' => 12,
                        'status'          => 'available', 
                    ],
                    [
                        'start_date'      => '2027-02-05',
                        'end_date'        => '2027-02-10',
                        'price'           => 2250.00,
                        'quota'           => 20,
                        'available_seats' => 0,
                        'status'          => 'full', 
                    ]
                ],
                
                'inclusions'    => [
                    ['item' => 'Akomodasi Hotel Bintang 3/4', 'is_included' => true],
                    ['item' => 'Private Bus selama tur', 'is_included' => true],
                    ['item' => 'Sewa Peralatan Ski', 'is_included' => false],
                ]
            ],

            // PAKET 6: Kyushu Autumn
            [
                'title'         => 'Kyushu Volcano & Onsen Explorer',
                'slug'          => 'kyushu-volcano-onsen-explorer',
                'description'   => 'Eksplorasi keindahan musim gugur di pulau selatan Jepang. Open trip ini menawarkan kombinasi pemandangan alam vulkanis yang dramatis dan desa onsen tradisional.',
                'location_text' => 'Fukuoka • Beppu • Kumamoto',
                'duration_days' => 5,
                'group_size'    => 'Max 15',
                'language'      => 'English',
                'is_guided'     => true,
                'base_price'    => 1600.00,
                
                'type'          => 'open', 
                'season'        => 'Autumn',
                'is_trending'   => false,
                
                'destination_slug' => 'kyushu',
                'categories'    => ['Nature', 'Onsen', 'Foodie'],
                
                'images'        => [
                    [
                        'image_path' => null,
                        'is_primary' => true
                    ]
                ],
                
                'itineraries'   => [
                    [
                        'day_number'  => 1,
                        'title'       => 'Fukuoka Yatai Night',
                        'description' => 'Tiba di Fukuoka, check-in, dan langsung menikmati kuliner malam di Yatai (kedai makanan jalanan) khas Hakata.',
                    ],
                    [
                        'day_number'  => 2,
                        'title'       => 'Beppu Hells (Jigoku Meguri)',
                        'description' => 'Mengunjungi 7 mata air panas spektakuler "Hells of Beppu" dan mencoba mandi pasir panas.',
                        'image_path'  => null,
                    ],
                    [
                        'day_number'  => 3,
                        'title'       => 'Mt. Aso Caldera',
                        'description' => 'Menyaksikan kaldera gunung berapi aktif terbesar di Jepang dan padang rumput Kusasenri yang memukau di musim gugur.',
                    ],
                    [
                        'day_number'  => 4,
                        'title'       => 'Kumamoto Castle',
                        'description' => 'Menjelajahi Kastil Kumamoto yang bersejarah dan berinteraksi dengan maskot lokal, Kumamon.',
                    ],
                    [
                        'day_number'  => 5,
                        'title'       => 'Kembali ke Fukuoka & Kepulangan',
                        'description' => 'Perjalanan kembali ke Fukuoka untuk penerbangan pulang ke negara asal.',
                    ]
                ],
                
                'schedules'     => [
                    [
                        'start_date'      => '2026-11-10',
                        'end_date'        => '2026-11-14',
                        'price'           => 1600.00,
                        'quota'           => 15,
                        'available_seats' => 5,
                        'status'          => 'available', 
                    ]
                ],
                
                'inclusions'    => [
                    ['item' => 'JR Kyushu Rail Pass 3 Hari', 'is_included' => true],
                    ['item' => 'Tiket Masuk Beppu Hells', 'is_included' => true],
                    ['item' => 'Makan Malam Bebas di Fukuoka', 'is_included' => false],
                ]
            ],

            // PAKET 7: Honshu Spring
            [
                'title'         => 'Honshu Classic Golden Route',
                'slug'          => 'honshu-classic-golden-route',
                'description'   => 'Rute klasik paling populer untuk pertama kali ke Jepang. Bergabung bersama grup dari berbagai negara melintasi Tokyo hingga Osaka di bawah rindangnya bunga Sakura.',
                'location_text' => 'Tokyo • Mt. Fuji • Kyoto • Osaka',
                'duration_days' => 7,
                'group_size'    => 'Max 25',
                'language'      => 'English',
                'is_guided'     => true,
                'base_price'    => 2450.00,
                
                'type'          => 'open', 
                'season'        => 'Spring',
                'is_trending'   => true,
                
                'destination_slug' => 'honshu',
                'categories'    => ['Cultural', 'Shrines', 'History', 'Shopping'],
                
                'images'        => [
                    [
                        'image_path' => null,
                        'is_primary' => true
                    ]
                ],
                
                'itineraries'   => [
                    [
                        'day_number'  => 1,
                        'title'       => 'Konnichiwa Tokyo',
                        'description' => 'Kedatangan di Narita/Haneda, berkumpul di hotel pusat Tokyo, free program.',
                    ],
                    [
                        'day_number'  => 2,
                        'title'       => 'Asakusa & Shibuya',
                        'description' => 'Mengunjungi kuil Senso-ji, Tokyo Skytree, dan menyeberangi Shibuya Crossing yang terkenal.',
                        'image_path'  => null,
                    ],
                    [
                        'day_number'  => 3,
                        'title'       => 'Mt. Fuji & Hakone',
                        'description' => 'Perjalanan ke 5th Station Gunung Fuji (jika cuaca mengizinkan) dan menaiki kapal bajak laut di Danau Ashi.',
                    ],
                    [
                        'day_number'  => 4,
                        'title'       => 'Bullet Train ke Kyoto',
                        'description' => 'Merasakan cepatnya kereta Shinkansen menuju Kyoto, dilanjutkan dengan kunjungan ke Kinkaku-ji (Kuil Emas).',
                    ],
                    [
                        'day_number'  => 5,
                        'title'       => 'Kyoto Heritage Tour',
                        'description' => 'Berjalan santai melintasi gerbang Fushimi Inari dan menjelajahi distrik Gion.',
                    ],
                    [
                        'day_number'  => 6,
                        'title'       => 'Nara Park & Osaka',
                        'description' => 'Bermain dengan rusa di Nara pada pagi hari, lalu menghabiskan malam berbelanja di Dotonbori, Osaka.',
                        'image_path'  => null,
                    ],
                    [
                        'day_number'  => 7,
                        'title'       => 'Kepulangan dari Kansai',
                        'description' => 'Waktu bebas sebelum diantar ke Kansai International Airport (KIX).',
                    ]
                ],
                
                'schedules'     => [
                    [
                        'start_date'      => '2027-04-01',
                        'end_date'        => '2027-04-07',
                        'price'           => 2600.00, // Harga Sakura Season
                        'quota'           => 25,
                        'available_seats' => 4,
                        'status'          => 'available', 
                    ]
                ],
                
                'inclusions'    => [
                    ['item' => 'Tiket Shinkansen Tokyo-Kyoto', 'is_included' => true],
                    ['item' => 'Tiket Masuk Semua Objek Wisata', 'is_included' => true],
                    ['item' => 'Visa Jepang', 'is_included' => false],
                ]
            ],
            // PAKET 8: Osaka Summer
            [
                'title'         => 'Osaka Summer Festival & Universal Studios',
                'slug'          => 'osaka-summer-festival-adventure',
                'description'   => 'Nikmati semaraknya musim panas di Jepang! Bergabung dalam kemeriahan festival musim panas (Matsuri) yang penuh warna, pesta kembang api, dan keseruan seharian penuh di Universal Studios Japan.',
                'location_text' => 'Osaka • Kyoto',
                'duration_days' => 5,
                'group_size'    => 'Max 16',
                'language'      => 'Indonesian',
                'is_guided'     => true,
                'base_price'    => 1750.00,
                
                'type'          => 'open', 
                'season'        => 'Summer',
                'is_trending'   => true,
                
                'destination_slug' => 'osaka',
                'categories'    => ['Cultural', 'Foodie', 'Shopping'],
                
                'images'        => [
                    [
                        'image_path' => null,
                        'is_primary' => true
                    ],
                    [
                        'image_path' => null,
                        'is_primary' => false
                    ]
                ],
                
                'itineraries'   => [
                    [
                        'day_number'  => 1,
                        'title'       => 'Kedatangan & Umeda Sky Building',
                        'description' => 'Tiba di Bandara Kansai, check-in hotel di area Namba, dan menikmati pemandangan kota Osaka dari Umeda Sky Building saat hoàng hôn.',
                    ],
                    [
                        'day_number'  => 2,
                        'title'       => 'Universal Studios Japan (USJ)',
                        'description' => 'Bermain seharian penuh di USJ, termasuk mengeksplorasi Super Nintendo World dan The Wizarding World of Harry Potter.',
                        'image_path'  => null,
                    ],
                    [
                        'day_number'  => 3,
                        'title'       => 'Kyoto Day Trip & Yukata Experience',
                        'description' => 'Perjalanan singkat ke Kyoto, menyewa Yukata (pakaian musim panas tradisional), dan berjalan-jalan di kuil Fushimi Inari.',
                    ],
                    [
                        'day_number'  => 4,
                        'title'       => 'Tenjin Matsuri & Dotonbori',
                        'description' => 'Menyaksikan salah satu festival musim panas terbesar di Jepang (jika jadwal sesuai) dan wisata kuliner malam di Dotonbori.',
                    ],
                    [
                        'day_number'  => 5,
                        'title'       => 'Sayonara Kansai',
                        'description' => 'Waktu bebas untuk membeli oleh-oleh di Shinsaibashi sebelum transfer ke bandara untuk kepulangan.',
                    ]
                ],
                
                'schedules'     => [
                    [
                        'start_date'      => '2026-07-20',
                        'end_date'        => '2026-07-24',
                        'price'           => 1850.00, // Harga peak season USJ
                        'quota'           => 16,
                        'available_seats' => 6,
                        'status'          => 'available', 
                    ]
                ],
                
                'inclusions'    => [
                    ['item' => 'Tiket Masuk Universal Studios Japan (1-Day Studio Pass)', 'is_included' => true],
                    ['item' => 'Sewa Yukata di Kyoto', 'is_included' => true],
                    ['item' => 'Tiket Universal Express Pass', 'is_included' => false],
                ]
            ],

            // PAKET 9: Tokyo & Nikko Autumn
            [
                'title'         => 'Tokyo & Nikko Autumn Splendor',
                'slug'          => 'tokyo-nikko-autumn-splendor',
                'description'   => 'Rasakan magisnya perubahan warna daun musim gugur (Momiji). Kontras antara gemerlap metropolis Tokyo dengan ketenangan kuil warisan dunia di lereng gunung Nikko.',
                'location_text' => 'Tokyo • Nikko',
                'duration_days' => 6,
                'group_size'    => 'Max 20',
                'language'      => 'English',
                'is_guided'     => true,
                'base_price'    => 1950.00,
                
                'type'          => 'open', 
                'season'        => 'Autumn',
                'is_trending'   => true,
                
                'destination_slug' => 'tokyo',
                'categories'    => ['Nature', 'History', 'Cultural'],
                
                'images'        => [
                    [
                        'image_path' => null,
                        'is_primary' => true
                    ]
                ],
                
                'itineraries'   => [
                    [
                        'day_number'  => 1,
                        'title'       => 'Tiba di Tokyo',
                        'description' => 'Bertemu di bandara Haneda/Narita, transfer ke hotel di area Shinjuku. Acara bebas pengenalan lingkungan hotel.',
                    ],
                    [
                        'day_number'  => 2,
                        'title'       => 'Shinjuku Gyoen & Meiji Shrine',
                        'description' => 'Menikmati taman Shinjuku Gyoen yang dihiasi warna kemerahan musim gugur, lalu berjalan ke Kuil Meiji dan Takeshita Street.',
                        'image_path'  => null,
                    ],
                    [
                        'day_number'  => 3,
                        'title'       => 'Eksplorasi Nikko (Toshogu Shrine)',
                        'description' => 'Perjalanan menggunakan kereta menuju Nikko, mengeksplorasi Kuil Toshogu yang megah dan jembatan merah Shinkyo.',
                    ],
                    [
                        'day_number'  => 4,
                        'title'       => 'Danau Chuzenji & Air Terjun Kegon',
                        'description' => 'Melanjutkan wisata alam di Nikko, melihat kemegahan Air Terjun Kegon dan keindahan Danau Chuzenji dari dek observasi.',
                    ],
                    [
                        'day_number'  => 5,
                        'title'       => 'Akihabara & Odaiba',
                        'description' => 'Kembali ke Tokyo, berbelanja elektronik/anime di Akihabara, dan melihat patung Gundam raksasa di Odaiba.',
                    ],
                    [
                        'day_number'  => 6,
                        'title'       => 'Kepulangan',
                        'description' => 'Check-out hotel dan menuju bandara untuk penerbangan kembali.',
                    ]
                ],
                
                'schedules'     => [
                    [
                        'start_date'      => '2026-11-15',
                        'end_date'        => '2026-11-20',
                        'price'           => 1950.00,
                        'quota'           => 20,
                        'available_seats' => 0,
                        'status'          => 'full', 
                    ],
                    [
                        'start_date'      => '2026-11-22',
                        'end_date'        => '2026-11-27',
                        'price'           => 1950.00,
                        'quota'           => 20,
                        'available_seats' => 10,
                        'status'          => 'available', 
                    ]
                ],
                
                'inclusions'    => [
                    ['item' => 'Nikko Pass All Area', 'is_included' => true],
                    ['item' => 'Akomodasi Hotel Bintang 3 di Tokyo', 'is_included' => true],
                    ['item' => 'Makan Siang selama di Tokyo', 'is_included' => false],
                ]
            ],

            // PAKET 11: Tokyo Winter Illuminations
            [
                'title'         => 'Tokyo Winter Illuminations & DisneySea',
                'slug'          => 'tokyo-winter-illuminations',
                'description'   => 'Nikmati sisi romantis Tokyo di musim dingin. Berjalan di bawah jutaan lampu LED di Shibuya Blue Cave dan Roppongi Hills, serta nikmati keajaiban Tokyo DisneySea.',
                'location_text' => 'Tokyo • Chiba',
                'duration_days' => 5,
                'group_size'    => 'Max 15',
                'language'      => 'Indonesian',
                'is_guided'     => true,
                'base_price'    => 1650.00,
                
                'type'          => 'open', 
                'season'        => 'Winter',
                'is_trending'   => true,
                
                'destination_slug' => 'tokyo',
                'categories'    => ['Shopping', 'Cultural', 'Nature'],
                
                'images'        => [
                    [
                        'image_path' => null,
                        'is_primary' => true
                    ],
                    [
                        'image_path' => null,
                        'is_primary' => false
                    ]
                ],
                
                'itineraries'   => [
                    [
                        'day_number'  => 1,
                        'title'       => 'Kedatangan & Roppongi Hills',
                        'description' => 'Tiba di Tokyo, check-in hotel, dan langsung menikmati pemandangan iluminasi malam spektakuler di Roppongi Hills Keyakizaka.',
                    ],
                    [
                        'day_number'  => 2,
                        'title'       => 'Tokyo DisneySea Magic',
                        'description' => 'Bermain seharian penuh di Tokyo DisneySea, menikmati wahana dan parade kembang api musim dingin.',
                        'image_path'  => null,
                    ],
                    [
                        'day_number'  => 3,
                        'title'       => 'Shibuya Blue Cave & Omotesando',
                        'description' => 'Belanja di area Omotesando pada siang hari dan berjalan menyusuri iluminasi Blue Cave di Shibuya pada malam harinya.',
                    ],
                    [
                        'day_number'  => 4,
                        'title'       => 'Asakusa & Tokyo Skytree',
                        'description' => 'Mengunjungi Kuil Senso-ji, berburu jajanan tradisional, dan naik ke dek observasi Tokyo Skytree.',
                    ],
                    [
                        'day_number'  => 5,
                        'title'       => 'Sayonara Tokyo',
                        'description' => 'Waktu bebas di pagi hari sebelum transfer menggunakan Limousine Bus ke bandara.',
                    ]
                ],
                
                'schedules'     => [
                    [
                        'start_date'      => '2026-12-22',
                        'end_date'        => '2026-12-26',
                        'price'           => 1850.00, // Harga peak season libur akhir tahun
                        'quota'           => 15,
                        'available_seats' => 2,
                        'status'          => 'available', 
                    ],
                    [
                        'start_date'      => '2027-01-10',
                        'end_date'        => '2027-01-14',
                        'price'           => 1650.00,
                        'quota'           => 15,
                        'available_seats' => 15,
                        'status'          => 'available', 
                    ]
                ],
                
                'inclusions'    => [
                    ['item' => 'Tiket Tokyo DisneySea (1-Day Pass)', 'is_included' => true],
                    ['item' => 'Tiket Tokyo Skytree', 'is_included' => true],
                    ['item' => 'Makan Siang dan Malam', 'is_included' => false],
                ]
            ],

            // PAKET 12: Kyushu Spring Blossoms & Trains
            [
                'title'         => 'Kyushu Sakura & Scenic Train',
                'slug'          => 'kyushu-sakura-scenic-train',
                'description'   => 'Eksplorasi pulau Kyushu dengan kereta wisata Yufuin no Mori yang elegan. Nikmati mekarnya bunga sakura di reruntuhan Kastil Kumamoto dan tenangnya desa Yufuin.',
                'location_text' => 'Fukuoka • Yufuin • Kumamoto',
                'duration_days' => 5,
                'group_size'    => 'Max 12',
                'language'      => 'English',
                'is_guided'     => true,
                'base_price'    => 1850.00,
                
                'type'          => 'open', 
                'season'        => 'Spring',
                'is_trending'   => false,
                
                'destination_slug' => 'kyushu',
                'categories'    => ['Nature', 'Rail Pass', 'History', 'Onsen'],
                
                'images'        => [
                    [
                        'image_path' => null,
                        'is_primary' => true
                    ]
                ],
                
                'itineraries'   => [
                    [
                        'day_number'  => 1,
                        'title'       => 'Hakata Welcome',
                        'description' => 'Tiba di stasiun Hakata, Fukuoka. Berjalan-jalan di Ohori Park untuk melihat bunga sakura.',
                    ],
                    [
                        'day_number'  => 2,
                        'title'       => 'Yufuin no Mori Journey',
                        'description' => 'Menaiki kereta wisata eksklusif "Yufuin no Mori" menuju desa pegunungan Yufuin, mengunjungi Danau Kinrin.',
                        'image_path'  => null,
                    ],
                    [
                        'day_number'  => 3,
                        'title'       => 'Kumamoto Castle Sakura',
                        'description' => 'Menuju Kumamoto dengan Shinkansen, menikmati festival sakura di pelataran Kastil Kumamoto.',
                    ],
                    [
                        'day_number'  => 4,
                        'title'       => 'Dazaifu Tenmangu',
                        'description' => 'Kembali ke Fukuoka, mengunjungi kuil Dazaifu Tenmangu yang terkenal dengan bunga plum dan sejarahnya.',
                    ],
                    [
                        'day_number'  => 5,
                        'title'       => 'Kepulangan',
                        'description' => 'Transfer dari hotel ke Fukuoka Airport untuk penerbangan pulang.',
                    ]
                ],
                
                'schedules'     => [
                    [
                        'start_date'      => '2027-03-25',
                        'end_date'        => '2027-03-29',
                        'price'           => 1950.00,
                        'quota'           => 12,
                        'available_seats' => 0,
                        'status'          => 'full', 
                    ]
                ],
                
                'inclusions'    => [
                    ['item' => 'JR Kyushu Rail Pass 5 Hari', 'is_included' => true],
                    ['item' => 'Tiket Kereta Yufuin no Mori (Reserved Seat)', 'is_included' => true],
                    ['item' => 'Pengeluaran Pribadi', 'is_included' => false],
                ]
            ],

            // PAKET 13: Kyoto Autumn Heritage
            [
                'title'         => 'Kyoto Autumn Night Illumination',
                'slug'          => 'kyoto-autumn-night-illumination',
                'description'   => 'Saksikan magisnya Kyoto saat daun-daun berubah merah. Paket ini mencakup akses eksklusif untuk melihat iluminasi malam musim gugur di kuil-kuil bersejarah.',
                'location_text' => 'Kyoto • Uji',
                'duration_days' => 4,
                'group_size'    => 'Max 10',
                'language'      => 'Indonesian',
                'is_guided'     => true,
                'base_price'    => 1450.00,
                
                'type'          => 'open', 
                'season'        => 'Autumn',
                'is_trending'   => true,
                
                'destination_slug' => 'kyoto',
                'categories'    => ['Shrines', 'Nature', 'Cultural'],
                
                'images'        => [
                    [
                        'image_path' => null,
                        'is_primary' => true
                    ]
                ],
                
                'itineraries'   => [
                    [
                        'day_number'  => 1,
                        'title'       => 'Kiyomizu-dera Night View',
                        'description' => 'Tiba di Kyoto sore hari, makan malam, dan langsung menuju Kiyomizu-dera untuk melihat iluminasi daun musim gugur yang menakjubkan.',
                    ],
                    [
                        'day_number'  => 2,
                        'title'       => 'Arashiyama & Sagano Train',
                        'description' => 'Menaiki Sagano Romantic Train melintasi lembah berdaun merah, dilanjutkan berjalan di hutan bambu Arashiyama.',
                        'image_path'  => null,
                    ],
                    [
                        'day_number'  => 3,
                        'title'       => 'Uji Matcha Tour & Byodo-in',
                        'description' => 'Eksplorasi kota Uji yang tenang, melihat kuil Byodo-in (yang ada di koin 10 Yen), dan mencicipi teh Matcha otentik.',
                    ],
                    [
                        'day_number'  => 4,
                        'title'       => 'Nishiki Market & Kepulangan',
                        'description' => 'Wisata kuliner dan belanja oleh-oleh di Nishiki Market sebelum menuju Stasiun Kyoto untuk pulang.',
                    ]
                ],
                
                'schedules'     => [
                    [
                        'start_date'      => '2026-11-18',
                        'end_date'        => '2026-11-21',
                        'price'           => 1450.00,
                        'quota'           => 10,
                        'available_seats' => 4,
                        'status'          => 'available', 
                    ],
                    [
                        'start_date'      => '2026-11-25',
                        'end_date'        => '2026-11-28',
                        'price'           => 1450.00,
                        'quota'           => 10,
                        'available_seats' => 0,
                        'status'          => 'completed', 
                    ]
                ],
                'inclusions'    => [
                    ['item' => 'Tiket Sagano Romantic Train', 'is_included' => true],
                    ['item' => 'Tiket Masuk Iluminasi Malam Kiyomizu-dera', 'is_included' => true],
                    ['item' => 'Akomodasi Hotel', 'is_included' => true],
                ]
            ],

            // PAKET 14: Tokyo Anime Activity (1 Hari)
            [
                'title'         => 'Tokyo Akihabara Anime & Maid Cafe Experience',
                'slug'          => 'tokyo-akihabara-anime-experience',
                'description'   => 'Tur jalan kaki setengah hari menyelami budaya otaku di pusat Akihabara. Termasuk kunjungan ke toko retro tersembunyi dan pengalaman makan siang di Maid Cafe otentik.',
                'location_text' => 'Tokyo',
                'duration_days' => 1,
                'group_size'    => 'Max 8',
                'language'      => 'English',
                'is_guided'     => true,
                'base_price'    => 120.00,
                
                'type'          => 'activity', 
                'season'        => 'Summer',
                'is_trending'   => true,
                
                'destination_slug' => 'tokyo',
                'categories'    => ['Anime', 'Shopping', 'Cultural'],
                
                'images'        => [
                    [
                        'image_path' => null,
                        'is_primary' => true
                    ]
                ],
                
                'itineraries'   => [
                    [
                        'day_number'  => 1,
                        'title'       => 'Akihabara Guided Walk & Maid Cafe',
                        'description' => 'Bertemu di Stasiun Akihabara jam 10 pagi, tur toko anime dan figur langka, diakhiri dengan makan siang interaktif di Maid Cafe hingga jam 3 sore.',
                        'image_path'  => null,
                    ]
                ],
                
                'schedules'     => [
                    [
                        'start_date'      => '2026-06-15',
                        'end_date'        => '2026-06-15',
                        'price'           => 120.00,
                        'quota'           => 8,
                        'available_seats' => 2,
                        'status'          => 'available', 
                    ],
                    [
                        'start_date'      => '2026-06-20',
                        'end_date'        => '2026-06-20',
                        'price'           => 120.00,
                        'quota'           => 8,
                        'available_seats' => 8,
                        'status'          => 'available', 
                    ]
                ],
                
                'inclusions'    => [
                    ['item' => 'Pemandu Otaku Lokal berbahasa Inggris', 'is_included' => true],
                    ['item' => 'Paket Makan Siang + Foto di Maid Cafe', 'is_included' => true],
                    ['item' => 'Biaya Belanja Pribadi', 'is_included' => false],
                ]
            ],

            // PAKET 15: Kyoto Cultural Activity (1 Hari)
            [
                'title'         => 'Kyoto Traditional Tea Ceremony & Kimono',
                'slug'          => 'kyoto-tea-ceremony-kimono',
                'description'   => 'Rasakan keanggunan budaya tradisional Jepang. Kenakan kimono sutra premium dan pelajari filosofi zen melalui upacara minum teh di dalam rumah kayu (Machiya) kuno.',
                'location_text' => 'Kyoto',
                'duration_days' => 1,
                'group_size'    => 'Max 4',
                'language'      => 'Indonesian',
                'is_guided'     => true,
                'base_price'    => 185.50,
                
                'type'          => 'activity', 
                'season'        => 'Spring',
                'is_trending'   => false,
                
                'destination_slug' => 'kyoto',
                'categories'    => ['Cultural', 'History'],
                
                'images'        => [
                    [
                        'image_path' => null,
                        'is_primary' => true
                    ]
                ],
                
                'itineraries'   => [
                    [
                        'day_number'  => 1,
                        'title'       => 'Transformasi Kimono & Matcha Experience',
                        'description' => 'Fitting kimono di pagi hari, berjalan-jalan santai untuk sesi foto di sekitar Gion, lalu mengikuti upacara minum teh secara privat selama 90 menit.',
                    ]
                ],
                
                'schedules'     => [
                    [
                        'start_date'      => '2026-04-10',
                        'end_date'        => '2026-04-10',
                        'price'           => 185.50,
                        'quota'           => 4,
                        'available_seats' => 0,
                        'status'          => 'full', 
                    ],
                    [
                        'start_date'      => '2026-04-12',
                        'end_date'        => '2026-04-12',
                        'price'           => 185.50,
                        'quota'           => 4,
                        'available_seats' => 4,
                        'status'          => 'available', 
                    ]
                ],
                
                'inclusions'    => [
                    ['item' => 'Sewa Kimono Lengkap & Hair-do', 'is_included' => true],
                    ['item' => 'Sesi Upacara Minum Teh & Wagashi (Kue Tradisional)', 'is_included' => true],
                    ['item' => 'Fotografer Profesional', 'is_included' => false],
                ]
            ],

            // PAKET 16: Hokkaido Winter Sports Activity (2 Hari)
            [
                'title'         => 'Hokkaido Snowmobile & Ice Fishing Adventure',
                'slug'          => 'hokkaido-snowmobile-ice-fishing',
                'description'   => 'Petualangan musim dingin memacu adrenalin! Mengendarai snowmobile melintasi padang salju yang belum terjamah dan mencoba sensasi memancing ikan wakasagi di atas danau beku.',
                'location_text' => 'Sapporo • Shinshinotsu',
                'duration_days' => 2,
                'group_size'    => 'Max 10',
                'language'      => 'English',
                'is_guided'     => true,
                'base_price'    => 350.00,
                
                'type'          => 'activity', 
                'season'        => 'Winter',
                'is_trending'   => true,
                
                'destination_slug' => 'hokkaido',
                'categories'    => ['Nature', 'Sports', 'Onsen'],
                
                'images'        => [
                    [
                        'image_path' => null,
                        'is_primary' => true
                    ]
                ],
                
                'itineraries'   => [
                    [
                        'day_number'  => 1,
                        'title'       => 'Snowmobile Forest Trail',
                        'description' => 'Penjemputan dari Sapporo menuju area pegunungan, briefing keselamatan, dan berkendara snowmobile sejauh 15km menembus hutan bersalju.',
                    ],
                    [
                        'day_number'  => 2,
                        'title'       => 'Ice Fishing & Hot Springs',
                        'description' => 'Memancing di tenda atas danau beku Shinshinotsu. Ikan tangkapan akan digoreng tempura untuk makan siang, dilanjutkan dengan berendam di onsen terdekat.',
                        'image_path'  => null,
                    ]
                ],
                
                'schedules'     => [
                    [
                        'start_date'      => '2026-12-28',
                        'end_date'        => '2026-12-29',
                        'price'           => 380.00, // Harga libur akhir tahun
                        'quota'           => 10,
                        'available_seats' => 2,
                        'status'          => 'available', 
                    ],
                    [
                        'start_date'      => '2027-01-15',
                        'end_date'        => '2027-01-16',
                        'price'           => 350.00,
                        'quota'           => 10,
                        'available_seats' => 10,
                        'status'          => 'available', 
                    ]
                ],
                
                'inclusions'    => [
                    ['item' => 'Sewa Snowmobile & Peralatan Memancing', 'is_included' => true],
                    ['item' => 'Pakaian Dingin, Sepatu Boots, dan Helm', 'is_included' => true],
                    ['item' => 'Akomodasi Menginap', 'is_included' => false],
                ]
            ]
        ];

        foreach ($packages as $data) {
            // Extract relations safely
            $categories   = $data['categories'] ?? [];
            $images       = $data['images'] ?? [];
            $itineraries  = $data['itineraries'] ?? [];
            $schedules    = $data['schedules'] ?? [];
            $inclusions   = $data['inclusions'] ?? [];
            $destSlug     = $data['destination_slug'] ?? null;
            
            // Remove relations from base data array
            unset(
                $data['categories'], 
                $data['images'], 
                $data['itineraries'], 
                $data['schedules'], 
                $data['inclusions'],
                $data['destination_slug']
            );

            // Handle destination link dynamically if string is provided
            if ($destSlug) {
                $dest = \App\Models\Destination::where('slug', $destSlug)->first();
                $data['destination_id'] = $dest ? $dest->id : null;
            }

            // Find primary image and set as virtual attribute for HasMedia
            $primaryImg = collect($images)->where('is_primary', true)->first();
            if ($primaryImg && !empty($primaryImg['image_path'])) {
                $data['primary_image'] = $primaryImg['image_path'];
            }

            // Create or Update Package base data
            $package = Package::updateOrCreate(
                ['slug' => $data['slug']], 
                $data
            );

            // Handle Gallery Images (Non-primary)
            $galleryImages = collect($images)->where('is_primary', false);
            if ($galleryImages->isNotEmpty()) {
                $mediaIds = [];
                foreach ($galleryImages as $img) {
                    $path = $img['image_path'];
                    if (!empty($path)) {
                        $media = \App\Models\MediaAsset::firstOrCreate(
                            ['url' => $path], // Use URL for external unsplash images
                            ['public_id' => $path, 'status' => 'permanent']
                        );
                        $mediaIds[$media->id] = ['collection_name' => 'gallery'];
                    }
                }
                if (!empty($mediaIds)) {
                    $package->media()->syncWithoutDetaching($mediaIds);
                }
            }

            // Sync Categories
            if (!empty($categories)) {
                $categoryIds = Category::whereIn('name', $categories)->pluck('id');
                $package->relatedCategories()->sync($categoryIds);
            }

            // Sync Itineraries (Refresh)
            if (!empty($itineraries)) {
                $package->itineraries()->delete();
                $package->itineraries()->createMany($itineraries);
            }

            // Sync Trip Schedules (Refresh)
            if (!empty($schedules)) {
                $package->tripSchedules()->delete();
                $package->tripSchedules()->createMany($schedules);
            }
            
            // Sync Inclusions (Refresh)
            if (!empty($inclusions)) {
                $package->inclusions()->delete();
                $package->inclusions()->createMany($inclusions);
            }
        }
    }
}

