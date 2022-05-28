<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = array('INR:&#8377;','CAD/USD:&#36;','GBP:&#163;','EUR:&#8364;');
        foreach($currencies as $currency){
            $currencyData = explode(":", $currency);
            DB::table('currencies')->insert([
                'name' => $currencyData[0],
                'code' => $currencyData[1],
            ]);
        }
    }
}
