<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentModes;

class PaymentModesController extends Controller
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
     * Show the all paymentmodes for the given user.
     *
     */
    public function index()
    {
        $paymentmodes = PaymentModes::all();
        return view('paymentmode.all', ['paymentmodes' => $paymentmodes]);
    }
    
    /**
     * View paymentmode
     * @param int $id
     */
    public function add($id = null)
    {
		$paymentmode = PaymentModes::find($id);
    	return view('paymentmode.add', ['paymentmode' => $paymentmode]);
    }

    /**
     * Add/Update paymentmode
     * @param int $id
     */
    public function save(Request $request)
    {
    	$request->flash();
        $this->validate($request, [
		    'name' => 'required|string|min:2|max:40',
		]);

        if ($request->has(['name'])) {

            $id = $request->cid;

            if($id != ''){
                $paymentmode = PaymentModes::find($id);
                $msg = 'updated';
            }else{
                $paymentmode = new PaymentModes;
                $msg = 'added';
            }

            $paymentmode->name = $request->name;
            $paymentmode->save();

            return redirect()->route('paymentmodes')->with('success', $request->name . ' payment mode '.$msg.' successfully!');
    	}else{
	        return redirect()->route('paymentmode_add')->with('warning', 'Error: Something missing in fields!');
    	}
    }

    /**
     * Delete Payment Mode
     * @param int $id
     */
    public function del($id)
    {
        if($id != ''){
			$paymentmode = PaymentModes::find($id);
            if($paymentmode){
                $paymentmode->delete();
                $msg = $paymentmode->name . ' paymentmode deleted successfully!';
                $msgType = 'success';
            }else{
                $msg = 'Nothing to delete in paymentmode!';
                $msgType = 'warning';
            }
            return redirect()->route('paymentmodes')->with($msgType, $msg);
        }else{
            return redirect()->route('home');
        }
    }
}
