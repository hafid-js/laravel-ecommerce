<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('123456');
        $adminRecords = [
            ['id' => 1,
            'name' => 'Admin',
            'type' => 'admin',
            'mobile' => 9800000000,
            'email' => 'admin@gmail.com',
            'password' => $password,
            'image' => '',
            'status' => 1],
            ['id' => 2,
            'name' =>'Amit',
            'type' => 'subadmin',
            'mobile' => 9700000000,
            'email' => 'amit@gmail.com',
            'password' => $password,
            'image' => '',
            'status' => 1],
            ['id' => 3,
            'name' =>'Widya Nursita',
            'type' => 'subadmin',
            'mobile' => 9900000000,
            'email' => 'widya@gmail.com',
            'password' => $password,
            'image' => '',
            'status' => 1]
        ];
        Admin::insert($adminRecords);
    }
}
