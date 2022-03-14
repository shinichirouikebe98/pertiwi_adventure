<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([
        //     'name'      => 'Tukang Bakso',
        //     'email'     => 'bakso@gmail.com',
        //     'password'  => Hash::make('password'),
        //     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            
        // ]);
        $admin = User::create([
            'name'      => 'Administrator',
            'email'     => 'super@gmail.com',
            'password'  => Hash::make('password'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            
        ]);
        $admin->assignRole('Admin');

        $user = User::create([
            'name'      => 'User Biasa Aaja',
            'email'     => 'normal@gmail.com',
            'password'  => Hash::make('password'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            
        ]);
        $user->assignRole('User');
    }
}
