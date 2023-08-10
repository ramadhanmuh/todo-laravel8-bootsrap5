<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('applications')->insert([
            'name' => 'ToDo',
            'tagline' => 'Tempat Menulis Kegiatan',
            'description' => 'Sebuah aplikasi pencatat kegiatan bagi semua orang.',
            'copyright' => 'Copyright 2023',
            'created_at' => time()
        ]);
    }
}
