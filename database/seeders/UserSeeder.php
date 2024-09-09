<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' =>'Admin',
            'last_name' =>'Admin',
            'email' =>'admin@gmail.com',
            'password' =>'12345678',
            'role' =>'admin'
        ]);
        User::create([
            'first_name' =>'Manager',
            'last_name' =>'Manager',
            'email' =>'manager@gmail.com',
            'password' =>'manager123',
            'role' =>'manager'
        ]);
    }
}
