<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 3; $i++) {
            $data[] = [
                'supplier_kode' => 'SUP' . $i,
                'supplier_nama' => 'Supplier ' . $i,
                'supplier_alamat' => 'Alamat Supplier ' . $i,
            ];
        }
        DB::table('m_supplier')->insert($data);
    }
}
