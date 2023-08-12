<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => Str::uuid(),
                'name' => Str::random(10),
                'username' => 'owner',
                'email' => 'owner@gmail.com',
                'email_verified_at' => time(),
                'password' => Hash::make('owner'),
                'role' => 'Owner',
                'created_at' => time()
            ],
            [
                'id' => Str::uuid(),
                'name' => Str::random(10),
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => time(),
                'password' => Hash::make('admin'),
                'role' => 'Administrator',
                'created_at' => time()
            ],
            [
                'id' => Str::uuid(),
                'name' => Str::random(10),
                'username' => 'user',
                'email' => 'user@gmail.com',
                'email_verified_at' => time(),
                'password' => Hash::make('user'),
                'role' => 'User',
                'created_at' => time()
            ],
        ]);
    }
}
