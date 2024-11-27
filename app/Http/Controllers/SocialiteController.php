<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class SocialiteController extends Controller
{   
    /**
     * Function: googleLogin
     * Description: Redirect to Google
     * 
     * 
     */
    public function googleLogin(){
        return Socialite::driver('google')->redirect();
    }

    /**
     * Function: googleAuthentication
     * Description: Authenticate user through google account
     * 
     * 
     */
    public function googleAuthentication() {

        try {

            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                Auth::login($user);
                return redirect()->route('login');
            } else {
                $userData = User::create( [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make('Password@1234'),
                    'google_id' => $googleUser->id,
                ]);

                if ($userData){
                    Auth::login($userData);
                    return redirect()->route('login');
                }
            }

        } catch (Exception $e) {
            dd($e);
        }

    }
}
