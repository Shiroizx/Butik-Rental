<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Cloth;
use App\Models\ClothesSize;

class AdditionalInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create Categories (Safe: firstOrCreate)
        $categories = [
            [
                'nama' => 'Tas Pesta',
                'deskripsi' => 'Tas pesta mewah dan elegan untuk berbagai acara.'
            ],
            [
                'nama' => 'Sepatu Formal',
                'deskripsi' => 'Sepatu pantofel dan heels untuk acara formal.'
            ],
            [
                'nama' => 'Jam Tangan Mewah',
                'deskripsi' => 'Jam tangan branded untuk pelengkap penampilan.'
            ],
            [
                'nama' => 'Aksesoris',
                'deskripsi' => 'Kalung, gelang, dan aksesoris lainnya.'
            ],
            [
                'nama' => 'Gaun Malam',
                'deskripsi' => 'Gaun malam elegan untuk pesta.'
            ],
        ];

        foreach ($categories as $cat) {
            $category = Category::firstOrCreate(
                ['nama_kategori' => $cat['nama']],
                ['deskripsi' => $cat['deskripsi']]
            );

            // 2. Create Dummy Items for each category
            $this->createDummyItems($category);
        }
    }

    private function createDummyItems($category)
    {
        $items = [];

        switch ($category->nama_kategori) {
            case 'Tas Pesta':
                $items = [
                    ['nama' => 'Clutch Gold Glitter', 'warna' => 'Emas', 'harga' => 75000, 'deskripsi' => 'Tas tangan pesta warna emas glamour.'],
                    ['nama' => 'Handbag Kulit Hitam', 'warna' => 'Hitam', 'harga' => 100000, 'deskripsi' => 'Tas kulit asli elegan.'],
                ];
                break;
            case 'Sepatu Formal':
                $items = [
                    ['nama' => 'Pantofel Kulit Oxford', 'warna' => 'Coklat', 'harga' => 120000, 'deskripsi' => 'Sepatu pantofel kulit sapi asli.'],
                    ['nama' => 'High Heels Stiletto Red', 'warna' => 'Merah', 'harga' => 90000, 'deskripsi' => 'Heels 7cm warna merah memukau.'],
                ];
                break;
            case 'Jam Tangan Mewah':
                $items = [
                    ['nama' => 'Rolex KW Super', 'warna' => 'Silver', 'harga' => 200000, 'deskripsi' => 'Replika kualitas tinggi, terlihat asli.'],
                    ['nama' => 'Fossil Leather Strap', 'warna' => 'Coklat Tua', 'harga' => 150000, 'deskripsi' => 'Jam tangan kulit vintage.'],
                ];
                break;
            case 'Aksesoris':
                $items = [
                    ['nama' => 'Kalung Mutiara', 'warna' => 'Putih', 'harga' => 50000, 'deskripsi' => 'Kalung mutiara sintetis cantik.'],
                    ['nama' => 'Bross Berlian Imitasi', 'warna' => 'Silver', 'harga' => 25000, 'deskripsi' => 'Bross cantik untuk hijab atau jas.'],
                ];
                break;
            case 'Gaun Malam':
                $items = [
                    ['nama' => 'Gaun Mermaid Emerald', 'warna' => 'Hijau Emerald', 'harga' => 350000, 'deskripsi' => 'Gaun potongan mermaid yang anggun.'],
                    ['nama' => 'Black Dress Off-Shoulder', 'warna' => 'Hitam', 'harga' => 300000, 'deskripsi' => 'Gaun hitam simple tapi berkelas.'],
                ];
                break;
        }

        foreach ($items as $item) {
            $cloth = Cloth::firstOrCreate(
                ['nama_baju' => $item['nama']], // Check by name to avoid duplicates
                [
                    'category_id' => $category->id,
                    'warna' => $item['warna'],
                    'harga_sewa' => $item['harga'],
                    'deskripsi' => $item['deskripsi'],
                    'is_available' => true,
                    // No image for dummy
                ]
            );

            // 3. Create Sizes
            if ($cloth->wasRecentlyCreated) {
                // Only add stocks if item was just created (to avoid adding duplicate stock)
                $sizes = ['S', 'M', 'L', 'XL'];
                if ($category->nama_kategori == 'Sepatu Formal') {
                    $sizes = ['38', '39', '40', '41', '42'];
                }
                if ($category->nama_kategori == 'Tas Pesta' || $category->nama_kategori == 'Jam Tangan Mewah' || $category->nama_kategori == 'Aksesoris') {
                    $sizes = ['All Size'];
                }

                foreach ($sizes as $size) {
                    ClothesSize::create([
                        'clothes_id' => $cloth->id,
                        'ukuran' => $size,
                        'stok' => rand(2, 5) // Random stock 2-5
                    ]);
                }
            }
        }
    }
}
