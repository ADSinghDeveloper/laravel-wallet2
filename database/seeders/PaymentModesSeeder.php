<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentModesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentModes = array('Net Banking','Cash','Debit Card','Credit Card');
        foreach($paymentModes as $paymentMode){
            DB::table('payment_modes')->insert([
                'name' => $paymentMode,
            ]);
        }
    }
}
