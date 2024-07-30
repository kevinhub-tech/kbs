<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    //
    public function auth(string $social)
    {
        return Socialite::driver($social)->redirect();
    }

    public function authcallback(string $social)
    {
        $user = Socialite::driver($social)->user();
        $avatar = $user->avatar_original . "&access_token={$user->token}";
        dd($user);
    }
}
