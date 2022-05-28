<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorsSeeder extends Seeder
{
    /**
     * Run the Colors seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = explode(',','#4284F4,#9c27b0,#d93025,#1973e8,#1d8e3e,#fac737,#fa7b17,#464646,#aa00ff,#880e4f,#311b92,#006064,#1b5e20,#C51162,#d84315,#4e342e,#ff9e22,#753113');
        foreach($colors as $color){
        	DB::table('colors')->insert([
	            'code' => $color,
	        ]);
        }
    }
}
