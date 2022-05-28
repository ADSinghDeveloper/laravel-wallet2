<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IconsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $icons = explode(',','account_balance_wallet,account_balance,all_out,assessment,assignment,book,bookmark,calendar_view_day,card_giftcard,card_membership,card_travel,chrome_reader_mode,credit_card,dashboard,dns,donut_large,donut_small,drag_indicator,eject,euro_symbol,group_work,home,label,line_style,language,motorcycle,offline_bolt,payment,shopping_cart,star,store,swap_horiz,timeline,work,album,equalizer,games,call,control_camera,business,phone,ballot,flight,attach_money,bubble_chart,functions,computer,phone_iphone,security,watch,tv,tablet,adjust,blur_on,brightness_5,camera,flare,grain,filter_vintage,healing,landscape,looks,loupe,tune,category,nature,texture,directions_bike,directions_bus,directions_car,local_bar,local_shipping,restaurant,restaurant_menu,train,ac_unit,all_inclusive,beach_access,casino,child_friendly,spa,pages,school');

        foreach($icons as $icon){
            DB::table('icons')->insert([
                'code' => $icon,
            ]);
        }
    }
}
