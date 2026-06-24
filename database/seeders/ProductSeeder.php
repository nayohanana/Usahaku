<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'name' => 'Sampoerna Mild 12',
                'barcode' => '8991234567890',
                'purchase_price' => 25000,
                'selling_price' => 28500,
                'stock' => 50,
                'min_stock' => 10,
                'description' => 'Rokok kretek mild',
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Djarum Super',
                'barcode' => '8991234567891',
                'purchase_price' => 19000,
                'selling_price' => 22500,
                'stock' => 45,
                'min_stock' => 10,
                'description' => 'Rokok kretek klasik',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Marlboro Red',
                'barcode' => '8991234567892',
                'purchase_price' => 31000,
                'selling_price' => 35000,
                'stock' => 30,
                'min_stock' => 8,
                'description' => 'Rokok putih internasional',
                'is_active' => true,
            ],
            [
                'category_id' => 3,
                'name' => 'Esse Change',
                'barcode' => '8991234567893',
                'purchase_price' => 22000,
                'selling_price' => 26000,
                'stock' => 25,
                'min_stock' => 5,
                'description' => 'Rokok menthol dengan switch',
                'is_active' => true,
            ],
            [
                'category_id' => 4,
                'name' => 'LA Bold',
                'barcode' => '8991234567894',
                'purchase_price' => 16000,
                'selling_price' => 19500,
                'stock' => 15,
                'min_stock' => 5,
                'description' => 'Rokok lokal bold',
                'is_active' => true,
            ],
            [
                'category_id' => 4,
                'name' => 'Vivo Mild',
                'barcode' => '8991234567895',
                'purchase_price' => 18000,
                'selling_price' => 21000,
                'stock' => 20,
                'min_stock' => 5,
                'description' => 'Rokok lokal mild',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Philip Morris',
                'barcode' => '8991234567896',
                'purchase_price' => 34000,
                'selling_price' => 38000,
                'stock' => 10,
                'min_stock' => 5,
                'description' => 'Rokok putih premium',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}