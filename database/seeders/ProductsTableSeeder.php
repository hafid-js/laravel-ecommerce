<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productsRecords =
        [
            [
            'id' => 1,
            'category_id' => 8,
            'brand_id' => 0,
            'product_name' => 'Blue T-Shirt',
            'product_code' => 'BT001',
            'product_color' => 'Dark Blue',
            'family_color' => 'Blue',
            'group_code' => 'TSHIRT000',
            'product_price' => 150000,
            'product_discount' => '10',
            'discount_type' => 'product',
            'final_price' => 135000,
            'product_weight' => 500,
            'product_video' => '',
            'description' => 'Test Product',
            'wash_care' => '',
            'keywords' => '',
            'fabric' => '',
            'pattern' => '',
            'fit' => '',
            'occassion' => '',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'is_featured' => 'Yes',
            'status' => 1
        ],
        [
            'id' => 2,
            'category_id' => 8,
            'brand_id' => 0,
            'product_name' => 'RED T-Shirt',
            'product_code' => 'RT001',
            'product_color' => 'Red',
            'family_color' => 'Red',
            'group_code' => 'TSHIRT000',
            'product_price' => 100000,
            'product_discount' => '10',
            'discount_type' => 'product',
            'final_price' => 100000,
            'product_weight' => 400,
            'product_video' => '',
            'description' => 'Test Product',
            'wash_care' => '',
            'keywords' => '',
            'fabric' => '',
            'pattern' => '',
            'fit' => '',
            'occassion' => '',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'is_featured' => 'No',
            'status' => 1
        ]
        ];

        Product::insert($productsRecords);
    }
}
