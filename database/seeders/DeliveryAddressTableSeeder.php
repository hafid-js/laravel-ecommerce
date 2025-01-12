<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeliveryAddress;

class DeliveryAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deliveryAddressRecords = [
            [
                'id' => 1,
                'user_id' => 1,
                'name' => 'Widya Nursita',
                'address' => 'Jl.Utan Kayu No 56',
                'city' => 'Jakarta Timur',
                'state' => 'DKI Jakarta',
                'country' => 'Indonesia',
                'pincode' => '13260',
                'mobile' => '082322875286',
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'name' => 'Riris Rahayu',
                'address' => 'Jl.Kutoarjo No 3',
                'city' => 'Purworejo',
                'state' => 'Jawa Tengah',
                'country' => 'Indonesia',
                'pincode' => '54261',
                'mobile' => '082322875211',
            ]
        ];
        DeliveryAddress::insert($deliveryAddressRecords);
    }
}
