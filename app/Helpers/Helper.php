<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use App\Models\Currencies;
use Session;
use Carbon\Carbon;

class Helper
{
    public static function amountFormatting($amt = 0, $curr = ''){
        $negVal = '';
        if($amt < 0){
            $negVal = '-';
            $amt *= -1;
        }
        if($curr == ''){
	        $curr = ($currency = Currencies::first()) ? $currency->code : 'INR ';
	    }
        return (is_numeric($amt)) ? $negVal.$curr.number_format((float)$amt,2,'.',',') : $curr;
    }

    public static function formatDate($dt,$pt){
        if($dt != ''){
            return (date('Y-m-d') == date('Y-m-d',strtotime($dt)))? 'Today' : ((date('Y-m-d',strtotime('-1 day')) == date('Y-m-d',strtotime($dt)))? 'Yesterday' : ($pt != ''? date($pt,strtotime($dt)): $dt));
        }
    }

    public static function setBack($request)
    {
        Session::put('get_back', $request->headers->get('referer'));
    }

    public static function getBack()
    {
        return Session::get('get_back');
    }

    public static function pullBack()
    {
        return Session::pull('get_back');
    }

    public static function forgetBack()
    {
        Session::forget('get_back');
    }

    public static function removeSpecialChars($str) {
        $res = str_replace( array( '\'', '"', ',' , ';', '<', '>', '[', ']', '{', '}', '~', '`', '%', '^', '*', '(', ')', '\\', '-', '_', ';', ':', '.', '/' ), '', $str);
        return $res;
    }

    public static function formattedDate($dt, $format = 'Y-m-d H:i:s') { // YYYY-MM-DD H:M:S
        $dt = explode(' ',str_replace('"','',$dt));
        $date = explode('-',$dt[0]);
        $time = explode(':',$dt[1]);
        $dateTime = Carbon::create($date[0],$date[1],$date[2],$time[0],$time[1],$time[2]);
        return $dateTime->toDateTimeString();
    }
}
