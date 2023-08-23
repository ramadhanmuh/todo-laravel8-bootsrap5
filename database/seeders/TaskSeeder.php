<?php

namespace Database\Seeders;

use DateTime;
use DateTimeZone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = DB::table('users')->where('role', '=', 'User')
                                    ->first();

        $startTime = new DateTime('2023-01-01 00:00:00');

        $startTime->setTimezone(new DateTimeZone('Asia/Jakarta'));

        $startTime = intval(strtotime($startTime->format('Y-m-d H:i:s')));

        $endTime = new DateTime('2023-12-31 00:00:00');

        $endTime->setTimezone(new DateTimeZone('Asia/Jakarta'));

        $endTime = intval(strtotime($endTime->format('Y-m-d H:i:s')));

        for ($i=0; $i < 10000; $i++) { 
            $start_time = rand(1, 1693000000);

            $end_time = rand(1, 1700000000);

            while ($start_time > $end_time) {
                $end_time = rand(1, 1700000000);
            }
            
            DB::table('tasks')->insert([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'title' => Str::random(10),
                'description' => Str::random(500),
                'start_time' => $start_time,
                'end_time' => $end_time,
                'created_at' => rand($startTime, $endTime)
            ]);
        }
    }
}
