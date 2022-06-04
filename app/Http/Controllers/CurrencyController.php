<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currencies;

class CurrencyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','checkRootUser']);
    }

    /**
     * Show the all accounts for the given user.
     *
     */
    public function index()
    {
        $currencies = Currencies::all();
        return view('currency.all', ['currencies' => $currencies]);
    }
    
    /**
     * View account
     * @param int $id
     */
    public function add($id = null)
    {
		$currency = Currencies::find($id);
    	return view('currency.add', ['currency' => $currency]);
    }

    /**
     * Add/Update account
     * @param int $id
     */
    public function save(Request $request)
    {
    	$request->flash();
        $this->validate($request, [
		    'name' => 'required|string|min:2|max:20',
		    'code' => 'required|string',
		]);

        if ($request->has(['name', 'code'])) {

            $id = $request->cid;

            if($id != ''){
                $currency = Currencies::find($id);
                $msg = 'updated';
            }else{
                $currency = new Currencies;
                $msg = 'added';
            }

            $currency->name = $request->name;
            $currency->code = $request->code;
            $currency->save();

            return redirect()->route('currencies')->with('success', $request->name . ' currency '.$msg.' successfully!');
    	}else{
	        return redirect()->route('currency_add')->with('warning', 'Error: Something missing in fields!');
    	}
    }

    /**
     * Delete Currency
     * @param int $id
     */
    public function del($id)
    {
        if($id != ''){
            $msgType = "error";
			if($currency = Currencies::find($id)){
                $currency->delete();
                $msgType = "success";
                $msg = $currency->name . ' currency deleted successfully!';
            }else{
                $msg = 'Nothing to delete in currency!';
            }
            return redirect()->route('currencies')->with($msgType, $msg);
        }else{
            return redirect()->route('home');
        }
    }
}
