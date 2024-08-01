<?php

namespace App\Http\Controllers;

use App\Helpers\SessionHandler;
use App\Models\users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    //
    public function register()
    {
        return view('users.register');
    }
    public function signup(Request $request)
    {
        $user = users::where('email', $request->email)->first();

        if ($user) {
            if ($user->is_auth) {
                $auth_provider = $user->auth_provider === 'facebook' ? 'Facebook' : 'Google';
                return  redirect()->route('user.register')->with('message', 'You have an account within this app, created with ' . $auth_provider . '. Please login with ' . $auth_provider . ' .');
            } else {
                return  redirect()->route('user.register')->with('message', 'You have created an account with this email already. Please login with that email');
            }
        }

        $new_user = users::create([
            'name' => $request->name,
            'email' => $request->email,
            'token' => Uuid::uuid4()->toString(),
            'password' => Hash::make($request->password),
            'role_id' => 'eaf20dd0-81cf-4115-8e9a-9ac5fe43ac21'
        ]);

        if ($new_user) {
            SessionHandler::storeSessionDetails($new_user->user_id, $new_user->role_id, $new_user->token);
            return redirect()->route('user.home');
        }
    }
    public function login()
    {
        return view('login');
    }
    public function signin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = users::where('email', $request->email)->first();
        if ($user) {
            if ($user->password === null) {
                $auth_provider = $user->auth_provider === 'facebook' ? 'Facebook' : 'Google';
                return redirect()->route('user.login')->with('message', 'You have an account on the app with ' . $auth_provider . ' already. Please login with ' . $auth_provider);
            } else {
                if (Hash::check($request->password, $user->password)) {
                    $user->token = Uuid::uuid4()->toString();
                    $user->save();
                    SessionHandler::storeSessionDetails($user->user_id, $user->role_id, $user->token);
                    return redirect()->route('user.home');
                } else {
                    return redirect()->route('user.login')->with('message', 'Unmatched password. Please check your password again');
                }
            }
        } else {
            return redirect()->route('user.login')->with('message', 'You do not have an account on the app. Please create an account or Login with Facebook/Google');
        }
    }

    public function home()
    {
        return view('users.home');
    }
    public function auth(string $social)
    {
        return Socialite::driver($social)->redirect();
    }

    public function authcallback(string $social)
    {
        /*
        * find user in model. if user is found, login. if not, new user stored and login
        */
        $auth_user = Socialite::driver($social)->user();
        if ($social === 'facebook') {
            $avatar = $auth_user->avatar_original . "&access_token={$auth_user->token}";
        } else {
            $avatar = str_replace('s96-c', 's400-c', $auth_user->avatar);
        }


        $user = users::where('email', $auth_user->email)->first();
        if ($user) {
            if ($user->is_auth) {

                $user->token = Uuid::uuid4()->toString();
                $user->save();
                SessionHandler::storeSessionDetails($user->user_id, $user->role_id, $user->token);
                return redirect()->route('user.home');
            } else {
                $user->is_auth = true;
                $user->auth_provider = $social;
                $user->image = $avatar;
                $user->token = Uuid::uuid4()->toString();
                $user->save();
                SessionHandler::storeSessionDetails($user->user_id, $user->role_id, $user->token);
                return redirect()->route('user.home');
            }
        }
        $new_user = users::create([
            'name' => $auth_user->name,
            'email' => $auth_user->email,
            'is_auth' => true,
            'auth_provider' => $social,
            'image' => $avatar,
            'token' => Uuid::uuid4()->toString(),
            'role_id' => 'eaf20dd0-81cf-4115-8e9a-9ac5fe43ac21'
        ]);
        SessionHandler::storeSessionDetails($new_user->user_id, $new_user->role_id, $new_user->token);
        return redirect()->route('user.home');
    }
}
