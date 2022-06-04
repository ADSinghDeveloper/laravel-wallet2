<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\AccountController;
use App\Models\Accounts;
use App\Models\Categories;
use App\Models\PaymentModes;
use App\Models\Transactions;
use App\Models\Currencies;
use Helper;
use Auth;
use Log;

class ImportController extends Controller
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
     * Show the all categories for the given user.
     *
     */
    public function index()
    {
        return view('import.import', ['title' => 'Import']);
    }

    /**
     * Import data.
     *
     */
    public function save(Request $request)
    {
    	$this->validate($request, [
	        'csv_file' => 'required|mimes:csv,txt'
	    ]);

	    $delimeter = $request->delimeter != ''? $request->delimeter : ',';

    	if ($request->hasFile('csv_file')) {
    		$path = $request->file('csv_file')->getRealPath();
    		$file = $request->file('image');
	      	/*$file->getClientOriginalName();
			$file->getClientOriginalExtension();
			$file->getRealPath();
			$file->getSize();
			$file->getMimeType();
			//Move Uploaded File
			$destinationPath = 'uploads';
			$file->move($destinationPath,$file->getClientOriginalName());*/

    		$file = file($path);
    		if(!is_array($file)){
    			return redirect()->route('import')->with('error','Invalid file!');
    		}

			$head = str_replace("\n", '', array_shift($file)); // Get first row as a head
    		$heads = explode($delimeter, $head);
    		$headCount = count($heads);
    		$invalidMsg = $oldRawRow = $rawRow = '';
    		$invalidData = array();
    		$totalRows = count($file);
    		$importFlag = false;

    		foreach ($file as $index => $row) {
				$rawRow = str_replace("\n", '', $row);
    			Log::debug('Data row: '.$rawRow);
    			$row = explode($delimeter,$row);
    			$rowCount = count($row);

    			if($headCount == $rowCount){
	                $transaction = new Transactions;
		            // $transaction->description = $request->description;
    				foreach ($heads as $key => $head) {
						if(strtolower($head) == 'account'){
							$accountID = $this->getAccountId($row[$key]);
							$transaction->account_id = $accountID;
		            		$importFlag = true;
	    					continue;
	    				}
						if(strtolower($head) == 'category'){
	    					$categoryID = $this->getCategoryId($row[$key]);
							$transaction->category_id = $categoryID;
		            		$importFlag = true;
	    					continue;
	    				}
						if(strtolower($head) == 'amount'){
							$transaction->amount = $row[$key];
                            // $importFlag = true;
                            // continue;
                        // }
						// if(strtolower($head) == 'type'){
							// $typeID = Transactions::getTypeCode(strtolower($row[$key]));
							$transaction->type = ($transaction->amount < 0) ? 1 : 2; // Transactions::TYPE = [ 1 => 'expenses', 2 => 'income'];
		            		$importFlag = true;
	    					continue;
	    				}
						if(strtolower($head) == 'payment_type'){
	    					$paymentModeID = $this::getPaymentModeId(Helper::removeSpecialChars(strtolower($row[$key])));
							$transaction->paymentmode_id = $paymentModeID;
		            		$importFlag = true;
	    					continue;
	    				}
						if(strtolower($head) == 'note'){
							$transaction->title = Helper::removeSpecialChars($row[$key]);
		            		$importFlag = true;
		            		continue;
	    				}
						if(strtolower($head) == 'date'){
							$transaction->date_time = Helper::formattedDate($row[$key]);
		            		$importFlag = true;
		            		continue;
	    				}
	    			}
	    			if($importFlag){
			            $transaction->user_id = Auth::id();
			            $transaction->save();
						Log::debug('Transactions saved: '.implode($delimeter, $row));
			        }
	    		}else{
    				Log::debug('Invalid data: '.implode($delimeter, $row));
    				$invalidData[$index+2] = implode($delimeter, $row);
    			}
		    }

		    AccountController::updateAccountsBalance();

		    $invalidDataCount = count($invalidData);

		    if($invalidDataCount > 0 && $importFlag){
		    	$invalidMsg = $invalidDataCount . " row(s) invalid";
		    	$request->session()->flash('warning', $invalidMsg.' at line no. '.implode(',', array_keys($invalidData)) . '. File: "'.$request->file('csv_file')->getClientOriginalName().'"');
		    }

		    if($importFlag){
		    	$msgType = 'success';
		    	$msgText = ($totalRows - $invalidDataCount). ' transaction(s) added successfully!';
		    }else{
		    	$msgType = 'error';
		    	$msgText = 'Invalid Data or something went wrong. Please try again.';
		    }

		    $request->session()->flash($msgType, $msgText);

	    	return redirect()->route('import')->with($msgType, $msgText);
    	}
    }

    protected function getAccountId($accountName = '')
    {
    	if($accountName == '') return false;

    	$account = Accounts::user()->firstOrCreate(
			['name' => Helper::removeSpecialChars($accountName)],
			['color_id' => 1, 'currency_id' => 1, 'user_id' => Auth::id(), 'status' => '1']
		);

		if($account){
			return $account->id;
		}
    }

    protected function getCategoryId($categoryName = '')
    {
    	if($categoryName == '') return false;

    	$category = Categories::user()->firstOrCreate(
			['name' => Helper::removeSpecialChars($categoryName)],
			['color_id' => 1, 'icon_id' => 1, 'user_id' => Auth::id()]
		);

		if($category){
			return $category->id;
		}
    }

    protected function getPaymentModeId($paymentModeName = '')
    {
    	if ($paymentModeName == '') return false;

    	$paymentMode = PaymentModes::where('name',$paymentModeName)->first();

		if($paymentMode){
			return $paymentMode->id;
		}else{
			return 1;
		}
    }
}
