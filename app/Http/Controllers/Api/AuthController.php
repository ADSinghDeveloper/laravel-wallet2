<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
         ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['user' => $user,'access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['user' => $user,'access_token' => $token, 'token_type' => 'Bearer']);
    }

    // method for user logout and delete token
    public function logout(Request $request)
    {
        // Revoke all tokens...
        $request->user()->tokens()->delete();

        // Revoke the token that was used to authenticate the current request...
        // $request->user()->currentAccessToken()->delete();

        // Revoke a specific token...
        // $request->user()->tokens()->where('id', $tokenId)->delete();

        return response()
            ->json(['success' => 'You have been logged-out successfully.']);
    }

    /**
     * Add/Edit to Save account
     * @param int $id
     */
    public function save_auth_profile(Request $request)
    {
        // $request->flash();
        $this->validate($request, [
            'name' => 'required|string|min:2|max:40',
        ]);

        if($request->conf_psw != $request->new_psw){
            return response()
            ->json(['error' => 'New and Confirm password does not matched. Please try again.']);

        }elseif (\Hash::check($request->curr_psw, $request->user()->password) && $request->user()->id == $request->id) {
            $profile = $request->user();
            $profile->name = $request->name;
            $pswdMsg = '';

            if(!empty($request->new_psw)){
                $profile->password = \Hash::make($request->new_psw);
                $pswdMsg = ' password';
            }

            $profile->save();

            return response()
            ->json(['error' => '', 'success' => 'Profile'.$pswdMsg.' updated successfully!', 'user' => $request->user()]);
        }else{
            return response()
            ->json(['error' => 'Wrong Password Entered.']);
        }
    }
}
