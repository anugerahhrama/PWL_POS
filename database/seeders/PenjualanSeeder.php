<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'user_id' => rand(1, 3),
                'pembeli' => 'Pembeli ' . $i,
                'penjualan_kode' => 'PNJ' . $i,
                'penjualan_tanggal' => Carbon::now()->subDays(rand(1, 30)),
            ];
        }
        DB::table('t_penjualan')->insert($data);
    }
}
