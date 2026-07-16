<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Voucher;


class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Voucher::create([
            'name' => 'Voucher Giảm Giá 20%',
            'quantity' => 100,
            'value' => 20.00, // Giá trị % 20
            'used' => 0,
            'code' => 'VOUCHER20',
            'start_date' => now(),
            'end_date' => now()->addMonths(1),
        ]);

        Voucher::create([
            'name' => 'Voucher Giảm Giá 100.000 VNĐ',
            'quantity' => 50,
            'value' => 100000.00, // Giá trị tiền 100000 VNĐ
            'used' => 0,
            'code' => 'VOUCHER100K',
            'start_date' => now(),
            'end_date' => now()->addMonths(1),
        ]);

        Voucher::create([
            'name' => 'Voucher Giảm Giá 500.000 VNĐ',
            'quantity' => 30,
            'value' => 500000.00, // Giá trị tiền 500000 VNĐ
            'used' => 0,
            'code' => 'VOUCHER500K',
            'start_date' => now(),
            'end_date' => now()->addMonths(1),
        ]);
    }
}