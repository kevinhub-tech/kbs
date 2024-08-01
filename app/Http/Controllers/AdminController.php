<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SessionHandler;
use App\Models\users;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class AdminController extends Controller
{
    public function login()
    {
        return view('login');
    }
    public function signin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        $user = users::where('name', $request->name)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $user->token = Uuid::uuid4()->toString();
                $user->save();
                SessionHandler::storeSessionDetails($user->user_id, $user->role_id, $user->token);
                return redirect()->route('admin.home');
            } else {
                return redirect()->route('admin.login')->with('message', 'Unmatched password. Please check your password again');
            }
        } else {
            return redirect()->route('admin.login')->with('message', 'You do not have an account on the app. Please create an account or Login with Facebook/Google');
        }
    }

    public function home()
    {
        return view('admins.home');
    }
}
