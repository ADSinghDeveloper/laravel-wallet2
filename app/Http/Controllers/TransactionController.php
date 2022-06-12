<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accounts;
use App\Models\Categories;
use App\Models\PaymentModes;
use App\Models\Transactions;
use App\Models\Currencies;
use App\Models\Filters;
use App\Models\User;
use Auth;
use Session;
use Helper;
use Hash;
use Log;
use PDF;

class TransactionController extends Controller
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
    public function index(Request $request, $aid = '')
    {
        $request->flash();
        $account = $filter = $minRecords = '';
        $minRecordsFlag = true;
        $aids = ($request->has(['aids']))? $request->aids : ($aid > 0 ? array($aid) : array());
        $cids = ($request->has(['cids']))? $request->cids : array();
        $mods = ($request->has(['mods']))? $request->mods : array();
        $type = ($request->has(['type']))? $request->type : '';
        $from = ($request->has(['from']))? $request->from : date('Y/m/d', strtotime('-1 week'));
        $till = ($request->has(['till']))? $request->till : date('Y/m/d');
        $search = ($request->has(['search']))? $request->search : '';
        $fid = ($request->has(['filter']))? $request->filter : 0;
        $allTrans = ($request->has(['all_trans']))? $request->all_trans : 0;
        $activeAccountsOnly = ($request->has(['active_accounts_only']))? $request->active_accounts_only : 0;

        if($allTrans){
            $from = $till = '';
        }

        $transactions = Transactions::user();

        $account = new Accounts;
        $account->id = '';
        $account->name = "All";
        $account->color->code = '';
        $account->currency->code = '';

        if(count($aids) > 0){
            $selectedAccounts = Accounts::user()->findOrFail($aids);
            $names = array();
            if(count($selectedAccounts) > 0){
                if(count($selectedAccounts) == 1){
                    $account = $selectedAccounts->first();
                }else{
                    foreach($selectedAccounts as $sAcc){
                        $names[] = $sAcc->name;
                    }
                    $account->name = implode(', ', $names);
                }
            }
            $transactions->whereIn('account_id',$aids);
        }

        if($fid > 0 && $filter = Filters::user()->find($fid)){
            $filterValues = json_decode($filter->value,true);
            $cids = $filterValues['cids'];
            $mods = $filterValues['mods'];
            $type = $filterValues['type'];
            $filter->search = $filterValues['search'];
            $search = $filter->search == '' ? $search : $filter->search;
            $request->flashExcept('instantFilterBtn');
        }

        $filteredCats = $filteredMods = [];

        if(count($cids) > 0){
            $transactions->whereIn('category_id',$cids);
            $filteredCats = Categories::user()->whereIn('id',$cids)->get();
            $minRecordsFlag = false;
        }

        if(count($mods) > 0){
            $transactions->whereIn('paymentmode_id',$mods);
            $filteredMods = PaymentModes::orderBy('name','ASC')->whereIn('id',$mods)->get();
            $minRecordsFlag = false;
        }

        if($type > 0){
            $transactions->where("type",$type);
            $minRecordsFlag = false;
        }

        if($from != '' || $till != ''){
            $transactions->whereBetween("date_time",[$from, date('Y/m/d', strtotime($till. ' + 1 days'))]);
            $minRecordsFlag = false;
        }

        if($search != ''){
            $transactions->where(function ($query) use ($search) {
                $query->Where('title', 'like', '%'.$search.'%')
                      ->orWhere('amount', '=', $search);
            });
            $minRecordsFlag = false;
        }

        $transactions = $transactions->orderBy('date_time','desc')->orderBy('id','desc')->get();
        if(count($transactions) == 0 && $minRecordsFlag){
            $transactions = Transactions::user()->orderBy('date_time','desc')->orderBy('id','desc')->take(5)->get();
            $from = date('Y/m/d', strtotime($transactions->min('date_time')));
            $till = date('Y/m/d', strtotime($transactions->max('date_time')));
            $minRecords = 'Last ';
        }
        if($activeAccountsOnly){
            $transactions = $transactions->reject(function ($transaction) {
                return $transaction->account->status == 0;
            });
        }

        if($allTrans){
            $from = date('Y/m/d', strtotime($transactions->min('date_time')));
            $till = 'Today';
        }

        $params = ['aids'=> $aids, 'cids' => $cids, 'mods' => $mods, 'till' => $till]; //, 'type' => $type
        $grossBalance = Transactions::totalAmount($params);

        $account->amount = $transactions->sum('amount');
        $totalRecords = $transactions->count();
        $accounts = Accounts::user()->orderBy('sequence','asc')->get();
        $categories = Categories::user()->orderBy('sequence','asc')->get();
        $filters = Filters::user()->orderBy('sequence','ASC')->get();
        $paymentModes = PaymentModes::orderBy('name','ASC')->get();
        $notes = Transactions::user()->select('title')->distinct()->where('title', '!=', '')->pluck('title');

        if($request->has(['pdf']) && $request->pdf == 1){
            // ini_set("memory_limit","-1");
            $pdf = PDF::loadView('transaction.print', ['account' => $account, 'accounts' => $accounts, 'categories' => $categories, 'paymentModes' => $paymentModes, 'transactions' => $transactions, 'filteredCats' => $filteredCats, 'filteredMods' => $filteredMods, 'type' => $type, 'search' => $search, 'totalRecords' => $totalRecords, 'from' => $from, 'till' => $till, 'filters' => $filters, 'selectedFilter' => $filter, 'minRecords' => $minRecords, 'grossBalance' => $grossBalance])->setPaper('a4')->setWarnings(false);
            return $pdf->download('wallet_records_'.date('Y-m-d_H-m-s').'.pdf');
        }else{
            return view('transaction.all', ['account' => $account, 'accounts' => $accounts, 'categories' => $categories, 'paymentModes' => $paymentModes, 'transactions' => $transactions, 'filteredCats' => $filteredCats, 'filteredMods' => $filteredMods, 'type' => $type, 'search' => $search, 'totalRecords' => $totalRecords, 'from' => $from, 'till' => $till, 'filters' => $filters, 'selectedFilter' => $filter, 'minRecords' => $minRecords, 'grossBalance' => $grossBalance, 'notes' => json_encode($notes)]);
        }
    }
    
    /**
     * Add/Edit account
     * @param int $id
     */
    public function add(Request $request, $id = null)
    {
        Helper::setBack($request);
        $transaction = '';
        $account = '';
        if($id > 0){
            $transaction = Transactions::user()->findorFail($id);
            if($transaction->amount < 0){
                $transaction->amount *= (-1);
            }
            $account = $transaction->account;
        }
        if($request->has(['aid']) && $request->aid > 0){
            $account = Accounts::user()->with('color')->find($request->aid);
        }
        $accounts = Accounts::user()->orderBy('sequence','asc')->with('color')->get();
        $categories = Categories::user()->orderBy('sequence','asc')->with('color')->get();
        $paymentmodes = PaymentModes::all();
        $notes = Transactions::user()->select('title')->distinct()->where('title', '!=', '')->pluck('title');

        return view('transaction.add',['transaction' => $transaction, 'accounts' => $accounts, 'categories' => $categories, 'paymentmodes' => $paymentmodes, 'curr_account' => $account, 'notes' => json_encode($notes)]);
    }

    /**
     * Add/Edit to Save account
     * @param int $id
     */
    public function save(Request $request)
    {
        $request->flash();
        $this->validate($request, [
            'amount' => 'required|numeric:between:0:999999999.99',
            'type' => 'required|digits_between:1,2',
            'date_time' => 'required|date_format:Y/m/d h:i A',
            'account_id' => 'required_with:accounts.id|integer',
            'category_id' => 'required_with:categories.id|integer',
            'payment_mode_id' => 'required_with:paymentmodes.id|integer',
        ]);

        if ($request->has(['amount', 'type', 'date_time', 'account_id', 'category_id', 'payment_mode_id'])) {

            $id = $request->tid;
            $accountUpdateFlag = true;

            if($id != ''){
                $transaction = Transactions::user()->find($id);
                $msg = 'updated';

                if($transaction->account_id != $request->account_id || $transaction->type != $request->type || $transaction->amount != $request->amount){ //adjust old amount in old account
                    $oldAccount = Accounts::user()->find($transaction->account_id);
                    $oldAccount->amount -= $transaction->amount;
                    $oldAccount->save();
                }else{
                    $accountUpdateFlag = false;
                }
            }else{
                $transaction = new Transactions;
                $msg = 'added';
            }

            if($request->type == 1){
                $request->amount *= (-1);
            }
            $transaction->title = $request->title;
            $transaction->amount = $request->amount;
            $transaction->type = $request->type;
            $transaction->date_time = date('Y-m-d H:i', strtotime($request->date_time)); //.date(':s');
            $transaction->account_id = $request->account_id;
            $transaction->category_id = $request->category_id;
            $transaction->paymentmode_id = $request->payment_mode_id;
            $transaction->user_id = Auth::id();
            $transaction->save();

            $account = Accounts::user()->find($request->account_id);
            if($account != '' && $accountUpdateFlag){
                $account->amount += $transaction->amount;
                $account->save();
            }

            return redirect()->to(Helper::getBack())->with('success', 'Transaction '.$msg.' successfully!');
    	}else{
	        return redirect()->route('transaction_add')->with('warning', 'Something missing!');
    	}
    }

    /**
     * Delete transaction
     * @param int $id
     */
    public function del(Request $request)
    {
        $msgType = 'error';
        if ($request->has(['tid'])) {
            if($transaction = Transactions::user()->where('id',$request->tid)->first()){
                $account = Accounts::user()->where('id',$transaction->account_id)->first();
                $account->amount -= $transaction->amount;
                $account->save();

                $returnToAcc = $transaction->account_id;
                $msgType = 'success';
                $msg = ($transaction->type == 1? 'Expense':'Income') . ' transaction '.strtoupper($transaction->title).' of amount '. Helper::amountFormatting($transaction->amount,$transaction->account->currency->code).' has been deleted successfully!';
                $transaction->delete();
            }else{
                $msg = 'Transaction not found!';
            }
        }else{
            $msg = 'Nothing to delete in transactions!';
        }
        return redirect()->to(Helper::getBack())->with($msgType, $msg);
    }

    /**
     * Export all transactions data.
     */
    public function export(Request $request)
    {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=wallet_export_".date('Ymd_His').".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $transactionsClass = Transactions::user()->orderBy('date_time','asc');

        if ($request->has(['tids'])) {
            $transactions = $transactionsClass->find(explode(',', $request->tids));
        }else{
            $transactions = $transactionsClass->get();
        }

        $columns = array('Category', 'Note', 'Account', 'Payment_Type', 'Amount', 'Type', 'Date');

        $callback = function() use ($transactions, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach($transactions as $transaction) {
                fputcsv($file, array($transaction->category->name, str_replace(',', '-', $transaction->title), $transaction->account->name, $transaction->paymentmode->name, $transaction->amount, ($transaction->amount < 0 ? 'Expenses':'Income'), date('Y-m-d H:i:s',strtotime($transaction->date_time))));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete all user data.
     */
    public function delall(Request $request)
    {
        $this->validate($request, [
            'confirm_del' => 'required'
        ]);
        $user = User::where('email', '=', Auth::user()->email)->first();
        if ($request->has(['confirm_del']) && Hash::check($request->confirm_del, $user->password)) {
            Transactions::user()->delete();
            Categories::user()->delete();
            Accounts::user()->delete();
            Filters::user()->delete();

            Log::debug('User Data Deleted: '.Auth::user());

            $msgType = 'success';
            $msgText = 'All data deleted successfully.';
        }else{
            $msgType = 'error';
            $msgText = 'Wrong password entered.';
        }

        return redirect()->to($request->headers->get('referer'))->with($msgType, $msgText);
    }
}
