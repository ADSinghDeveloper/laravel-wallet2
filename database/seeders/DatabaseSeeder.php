<?php

use Illuminate\Database\Seeder;
use Database\Seeders\ColorsSeeder;
use Database\Seeders\CurrenciesSeeder;
use Database\Seeders\IconsSeeder;
use Database\Seeders\PaymentModesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(ColorsSeeder::class);
        $this->call(CurrenciesSeeder::class);
        $this->call(IconsSeeder::class);
        $this->call(PaymentModesSeeder::class);
    }
}
