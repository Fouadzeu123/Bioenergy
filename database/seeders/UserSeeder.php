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

            'country_code'=>237,
            'phone' => '691051864',
            'role'=>'admin',
            'invited_by' => null,
            'invitation_code' => 'HTAD3210',
            'password' => Hash::make('Boris#@2000#'),
        ]);

    }
}
