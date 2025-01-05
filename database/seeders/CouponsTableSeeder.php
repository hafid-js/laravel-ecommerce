<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $couponRecords = [
            [
                'id' => 1,
                'coupon_option' => 'Manual',
                'coupon_code' => 'JNRFREE05',
                'categories' => '1,2,3,4,5,9,10,11,12,13',
                'brands' => '3,4',
                'users' => '',
                'coupon_type' => 'Single',
                'amount_type' => 'Percentage',
                'amount' => '10',
                'expiry_date' => '2025-01-31',
                'status' => 1
            ],
            [
                'id' => 2,
                'coupon_option' => 'Manual',
                'coupon_code' => 'JNRFREE06',
                'categories' => '1,2,3,4,5,9,10,11,12,13',
                'brands' => '3,4',
                'users' => 'neni@gmail.com',
                'coupon_type' => 'Single',
                'amount_type' => 'Percentage',
                'amount' => '20',
                'expiry_date' => '2025-01-31',
                'status' => 1
            ],
            [
                'id' => 3,
                'coupon_option' => 'Automatic',
                'coupon_code' => 'JNRFREE07',
                'categories' => '1,2,3,4,5,9,10,11,12,13',
                'brands' => '3,4',
                'users' => '',
                'coupon_type' => 'Multiple',
                'amount_type' => 'Fixed',
                'amount' => '100',
                'expiry_date' => '2025-01-31',
                'status' => 1
            ],
        ];

        Coupon::insert($couponRecords);
    }
}
