<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth; 

class GoogleController extends Controller
{
    
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('google')->user();
        // dd($user);
        
       // $users_data = $this->createUser($user,'google');
 
        // auth()->login($user);

        // $user_data = ['email' => $user->email, 'password' => '12345678'];

        // Auth::login($user_data);
        // $user_data = User::where('email','=',$user->email)->first();
        //  Auth::loginUsingId($user_data->id, TRUE);
        //  Auth::user();

        // dd($user->token);

        dd($user);

        die();

        // var_dump('Login Success');
    
        // return redirect()->to('/home');
        // $user->token;
    }

    public function createUser($getInfo,$provider){
 
        $user = User::where('provider_id', $getInfo->id)->first();

        dd($getInfo->user['picture']);

        die();
        
        if (!$user) {
            $user = User::create([
               'name'     => $getInfo->name,
               'email'    => $getInfo->email,
               'password' => bcrypt('12345678'),               
               'provider' => $provider,
               'provider_id' => $getInfo->id,
               'g_token'  => $getInfo->token,
           ]);
        //    dd('Not Matched So Created New ');
            return $user;
         }else{
            // dd('Matched so Existing Dta');
            return $getInfo;  
         }
         
       }
}
