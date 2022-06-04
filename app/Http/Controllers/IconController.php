<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Icons;

class IconController extends Controller
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
     * Show the all Icons.
     *
     */
    public function index()
    {
        $icons = Icons::all();
        return view('icon.all', ['icons' => $icons]);
    }
    
    /**
     * Add Icon
     * @param int $id
     */
    public function add($id = null)
    {
		$icon = Icons::find($id);
    	return view('icon.add', ['icon' => $icon]);
    }

    /**
     * Save Icon
     * @param int $id
     */
    public function save(Request $request)
    {
    	$this->validate($request, [
		    'code' => 'required|string|unique:icons',
		]);

        if ($request->has(['code'])) {

            $icons = new Icons;
            $icons->code = $request->code;
            $icons->save();

            return redirect()->route('icons')->with('success', 'Icon added!');
    	}else{
	        return redirect()->route('icon_add')->with('warning', 'Error: Something missing in fields!');
    	}
    }

    /**
     * Delete Icon
     * @param int $id
     */
    public function del($id)
    {
        if($id != ''){
            $msgType = "error";
            if($icon = Icons::find($id)){
                $icon->delete();
                $msgType = "success";
                $msg = $icon->name . ' Icon deleted successfully!';
            }else{
                $msg = 'Nothing to delete in icons!';
            }
            return redirect()->route('icons')->with($msgType, $msg);
        }else{
            return redirect()->route('home');
        }
    }
}
