<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductsAttribute;

class ProductsAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productAttributeRecords = [
            [
                'id' => 'product_id',
                'size' => 'Small',
                'sku' => 'BT001S',
                'price' => 1000,
                'stock' => 100,
                'status' => 1
            ]
        ];
        ProductsAttribute::insert($productAttributeRecords);
    }
}
