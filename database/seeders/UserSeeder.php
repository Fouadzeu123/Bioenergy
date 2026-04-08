<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'username' => 'AdminBioEnergy',
            'country_code'=>237,
            'phone' => '600000000',
            'role'=>'admin',
            'invited_by' => null,
            'invitation_code' => 'ADMIN001',
            'password' => Hash::make('admin123'),
        ]);

        User::create([
            'username' => 'TestUser',
            'country_code'=>237,
            'phone' => '699999999',
            'account_balance'=> 500,
            'invited_by' => 1, // parrainé par Admin
            'invitation_code' => 'TEST001',
            'password' => Hash::make('test123'),
        ]);
    }
}