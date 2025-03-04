<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 15; $i++) {
            $data[] = [
                'supplier_id' => rand(1, 3),
                'barang_id' => $i,
                'user_id' => rand(1, 3),
                'stok_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'stok_jumlah' => rand(10, 100),
            ];
        }
        DB::table('t_stok')->insert($data);
    }
}
