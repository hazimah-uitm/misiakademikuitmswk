<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('visitors')->insert([
            [
                'response_at'   => Carbon::now()->subDays(3),
                'full_name'     => 'Ali Bin Abu',
                'phone'         => '0123456789',
                'email'         => 'ali@example.com',
                'program_bidang'=> 'Sains Komputer',
                'lokasi'        => 'Kampus Samarahan 2',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'response_at'   => Carbon::now()->subDays(2),
                'full_name'     => 'Siti Binti Ahmad',
                'phone'         => '0198765432',
                'email'         => 'siti@example.com',
                'program_bidang'=> 'Perakaunan, Kewangan',
                'lokasi'        => 'Kampus Mukah',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'response_at'   => Carbon::now()->subDay(),
                'full_name'     => 'John Doe',
                'phone'         => '01111222333',
                'email'         => 'john@example.com',
                'program_bidang'=> 'Kejuruteraan Elektrik',
                'lokasi'        => 'Kampus Kota Samarahan',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ]);
    }
}
