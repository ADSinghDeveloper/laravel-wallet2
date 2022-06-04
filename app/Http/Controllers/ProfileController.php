<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
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
     * View profile info.
     *
     */
    public function edit()
    {
        $profile = \Auth::user();
        return view('profile.edit', ['profile' => $profile]);
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
        ]);

        if($request->conf_psw != $request->new_psw){
        	return redirect()->route('profile')->with('error', 'New and Confirm password does not matched. Please try again.');
        }elseif ($request->has(['name'])) {
            $profile = \Auth::user();
            $profile->name = $request->name;
            $pswdMsg = '';
            if($request->curr_psw && $request->new_psw){
	            if(\Hash::check($request->curr_psw, \Auth::user()->password)){
	            	$profile->password = \Hash::make($request->new_psw);
                    $pswdMsg = ' password';
	            }else{
	            	return redirect()->route('profile')->with('error', 'Current password does not matched. Please try again.');
	            }
	        }
            $profile->save();

            return redirect()->route('profile')->with('success','Profile'.$pswdMsg.' updated successfully!');
    	}else{
	        return redirect()->route('profile')->with('warning', 'Something missing!');
    	}
    }
}
