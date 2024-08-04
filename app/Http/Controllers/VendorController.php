<?php

namespace App\Http\Controllers;

use App\Helpers\SessionHandler;
use App\Models\roles;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class VendorController extends Controller
{
    //

    public function login()
    {
        if (SessionHandler::checkUserSession()) {
            return redirect()->back();
        }
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
                $user_role = roles::find($user->role_id);
                SessionHandler::storeSessionDetails($user->user_id, $user->name, $user_role->role_name, $user->token);
                return redirect()->route('vendor.home');
            } else {
                return redirect()->route('vendor.login')->with('message', 'Unmatched password. Please check your password again');
            }
        } else {
            return redirect()->route('vendor.login')->with('message', 'You do not have an account on the app. Please create an account or Login with Facebook/Google');
        }
    }

    public function logout()
    {
        $user = users::find(session('userId'));
        $user->token = null;
        $user->save();
        if ($user) {
            SessionHandler::removeSessionDetails();
            return redirect()->route('vendor.login');
        }
    }

    public function home()
    {
        return view('vendors.home');
    }

    public function demopost(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully posted some info on user route',
            'payload' => [
                $request->name,
                $request->email,
            ]
        ], Response::HTTP_ACCEPTED);
    }

    public function demoget(Request $request)
    {
        $get_data = ['1' => 'data 1', '2' => 'data 2'];
        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully posted some info on user route',
            'payload' => $get_data
        ], Response::HTTP_ACCEPTED);
    }
}
