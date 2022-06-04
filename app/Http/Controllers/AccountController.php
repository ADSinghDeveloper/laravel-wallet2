<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accounts;
use App\Models\Currencies;
use App\Models\Colors;
use App\Models\Transactions;
use Auth;
use Helper;

class AccountController extends Controller
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
     * Show the all accounts for the given user.
     *
     */
    public function index()
    {
        $accounts = Accounts::user()->with('color','currency')->orderBy('sequence','asc')->get();
        return view('account.all', ['accounts' => $accounts]);
    }

    /**
     * Show the all accounts for the given user.
     *
     */
    public function balanceUpdate()
    {
        self::updateAccountsBalance();
        return redirect()->route('accounts')->with('success', 'Accounts total updated successfully!');
    }

    /**
     * Update amount of accounts for the given user.
     *
     */
    static public function updateAccountsBalance(){
        $accounts = Accounts::user()->get();
        $accountsAmount = Transactions::totalAmount(['grand_total']);
        foreach ($accounts as $account) {
            if(array_key_exists($account->id, $accountsAmount)){
                $accountUpdate = Accounts::user()->find($account->id);
                $accountUpdate->amount = $accountsAmount[$account->id];
                $accountUpdate->save();
            }
        }
    }
    
    /**
     * Add/Edit account
     * @param int $id
     */
    public function add(Request $request, $id = null)
    {
        $account = Accounts::user()->with('color','currency')->find($id);
        $currencies = Currencies::all();
        $colors = Colors::all();

        Helper::setBack($request);

        return view('account.add',['account' => $account, 'currencies' => $currencies, 'colors' => $colors]);
    }

    /**
     * Add/Edit to Save account
     * @param int $id
     */
    public function save(Request $request)
    {
        $request->flash();
        $this->validate($request, [
            'name' => 'required|string|min:2|max:20',
            'currency_id' => 'required_with:currency.id|string',
            'color_id' => 'required_with:colors.id|string',
        ]);

        if ($request->has(['name', 'currency_id', 'color_id'])) {

            $id = $request->aid;

            if($id != ''){
                $account = Accounts::user()->find($id);
                $msg = 'updated';
            }else{
                $account = new Accounts;
                $msg = 'added';
            }

            $account->name = $request->name;
            $account->sequence = $request->sequence;
            $account->currency_id = $request->currency_id;
            $account->color_id = $request->color_id;
            $account->user_id = Auth::id();
            $account->status = ($request->status == 1) ? $request->status : "0";
            $account->save();

            return redirect()->to(Helper::getBack())->with('success', $account->name . ' account '.$msg.' successfully!');
    	}else{
	        return redirect()->route('account_add')->with('warning', 'Something missing!');
    	}
    }

    /**
     * Delete account
     * @param int $id
     */
    public function del(Request $request)
    {
        $msgType = 'error';
        if ($request->has(['aid'])) {
            if($account = Accounts::user()->where('id',$request->aid)->first()){
                $transactions = Transactions::user()->where('account_id',$account->id)->get();
                $transactionsCount = count($transactions);
                if($transactionsCount > 0){
                    return redirect()->route('accounts')->with('warning', $account->name . " account has ".$transactionsCount." transaction(s). Please delete the transaction(s) first and try again.");
                }else{
                    $account->delete();
                    $msg = $account->name . ' account deleted successfully!';
                    $msgType = 'success';
                }
            }else{
                $msg = 'Account not found!';
            }
        }else{
            $msg = 'Nothing to delete in accounts!';
        }
        return redirect()->route('accounts')->with($msgType, $msg);
    }
}
