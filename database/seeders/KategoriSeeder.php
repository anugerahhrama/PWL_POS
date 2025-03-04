<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 5; $i++) {
            $data[] = [
                'kategori_kode' => 'KAT' . $i,
                'kategori_nama' => 'Kategori ' . $i,
            ];
        }
        DB::table('m_kategori')->insert($data);
    }
}
