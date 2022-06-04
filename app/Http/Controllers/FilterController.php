<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accounts;
use App\Models\Categories;
use App\Models\PaymentModes;
use App\Models\Filters;
use Auth;
use Helper;

class FilterController extends Controller
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
     * Show the all filters for the given user.
     *
     */
    public function index()
    {
        $filters = Filters::user()->orderBy('sequence','asc')->get();
        return view('filter.all', ['filters' => $filters]);
    }
    
    /**
     * View filter
     * @param int $id
     */
    public function add(Request $request, $id = null)
    {
        $filter = Filters::user()->find($id);
        $filterValue = $filter? json_decode($filter->value, true) : array('cids' => array(), 'mods' => array(), 'type' => array(), 'search' => '');
        $aids = $filter? json_decode($filter->aids, true) : array();

        $accounts = Accounts::user()->orderBy('sequence','asc')->get();
        $categories = Categories::user()->orderBy('sequence','asc')->get();
        $paymentModes = PaymentModes::orderBy('name','ASC')->get();

        Helper::setBack($request);

        return view('filter.add',array_merge(['filter' => $filter, 'aids' => $aids, 'accounts' => $accounts, 'categories' => $categories, 'paymentModes' => $paymentModes],$filterValue));
    }

    /**
     * View filter
     * @param int $id
     */
    public function save(Request $request)
    {
        $request->flash();
        $this->validate($request, [
            'name' => 'required|string|min:2|max:20'
        ]);

        if ($request->has(['name'])) {

            $id = $request->fid;

            if($id != ''){
                $filter = Filters::user()->find($id);
                $msg = 'updated';
            }else{
                $filter = new Filters;
                $msg = 'added';
            }

        	$aids = ($request->has(['aids']))? $request->aids : array();
        	$cids = ($request->has(['cids']))? $request->cids : array();
	        $mods = ($request->has(['mods']))? $request->mods : array();
	        $type = ($request->has(['type']))? $request->type : '';
            $search = ($request->has(['search']))? $request->search : '';

	        $value = ['cids' => $cids, 'type' => $type, 'mods' => $mods, 'search' => $search];
			$value = json_encode($value);

            $filter->name = $request->name;
            $filter->value = $value;
            $filter->aids = json_encode($aids);
            $filter->sequence = $request->sequence;
            $filter->user_id = Auth::id();
            $filter->save();

            $getBackWith = Helper::getBack();
            if($request->has(['from_transac']) && $request->from_transac == 1){
                $getBackWith = route('transaction_view',[$request->aid,'filter' => $filter->id, 'from' => $request->from, 'till' => $request->till, 'search' => $search]);
            }

            return redirect()->to($getBackWith)->with('success', $filter->name . ' filter '.$msg.' successfully!');
    	}else{
	        return redirect()->route('filter_add')->with('warning', 'Something missing!');
    	}
    }

    /**
     * Delete category
     * @param int $id
     */
    public function del(Request $request)
    {
        $msgType = "error";
        if ($request->has(['fid'])) {
            if($filter = filters::user()->where('id',$request->fid)->first()){
                $filter->delete();
                $msgType = 'success';
                $msg = $filter->name . ' filter deleted successfully!';
            }else{
            	$msgType = 'error';
                $msg = 'Filter not found!';
            }
        }else{
        	$msgType = 'warning';
            $msg = 'Nothing to delete in filters!';
        }
        return redirect()->route('filters')->with($msgType, $msg);
    }
}
