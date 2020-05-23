<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Colors;

class ColorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','checkRoutUser']);
    }

    /**
     * Show the all accounts for the given user.
     *
     */
    public function index()
    {
        $colors = Colors::all();
        return view('color.all', ['colors' => $colors]);
    }
    
    /**
     * View account
     * @param int $id
     */
    public function add($id = null)
    {
		$color = Colors::find($id);
    	return view('color.add', ['color' => $color]);
    }

    /**
     * View account
     * @param int $id
     */
    public function save(Request $request)
    {
    	$this->validate($request, [
		    //'name' => 'required|string|min:2|max:20',
		    'code' => 'required|string|unique:colors|min:4|max:7',
		]);

        if ($request->has(['code'])) {

            $colors = new Colors;
            $colors->code = $request->code;
            $colors->save();

            return redirect()->route('colors')->with('success', 'Color added!');
    	}else{
	        return redirect()->route('color_add')->with('warning', 'Error: Something missing in fields!');
    	}
    }

    /**
     * Delete Color
     * @param int $id
     */
    public function del($id)
    {
        if($id != ''){
            $msgType = "error";
            if($color = Colors::find($id)){
                $color->delete();
                $msgType = "success";
                $msg = $color->name . ' Color deleted successfully!';
            }else{
                $msg = 'Nothing to delete in colors!';
            }
            return redirect()->route('colors')->with($msgType, $msg);
        }else{
            return redirect()->route('home');
        }
    }
}
