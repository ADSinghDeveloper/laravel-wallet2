<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accounts;
use App\Models\Transactions;
use App\Models\Filters;
use Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $accounts = Accounts::user()->orderBy('sequence','asc')->get();
        $currencyAmount = Accounts::currencyAmount();

        $filters = Filters::user()->where('aids', '!=', '')->orderBy('sequence','asc')->get();
        $dashboardFilters = [];

        foreach ($filters as $filter) {
    		$tempArr = [];
        	foreach (json_decode($filter->aids, true) as $aid) {
        		foreach ($accounts as $account) {
        			if($account->id == $aid){
        				$tempArr2 = [];
        				$tempArr2['filterName'] = $filter->name;
        				$tempArr2['accName'] = $account->name;
        				$tempArr2['accColor'] = $account->color->code;
        				$tempArr2['accCurr'] = $account->currency->code;

		        		$filterValues = array_merge(['aids' => [$aid]],json_decode($filter->value,true));
		                $tempArr2['filterAmount'] = Transactions::totalAmount($filterValues);
        				$tempArr[$aid] =$tempArr2; 
        			}
        		}
        	}
        	$dashboardFilters[$filter->id] = $tempArr;
        }
        $accounts = Accounts::user()->where('status','1')->orderBy('sequence','asc')->get();
        $transactions = Transactions::user()->orderBy('date_time','desc')->orderBy('id','desc')->take(5)->get();

        return view('dashboard.home', [
            'accounts' => $accounts,
            'transactions' => $transactions,
            'currencyAmount' => $currencyAmount,
            'dashboardFilters' => $dashboardFilters
        ]);
    }
}
