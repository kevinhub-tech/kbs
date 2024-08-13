<?php

namespace App\Http\Controllers;

use App\Helpers\SessionHandler;
use App\Models\books;
use App\Models\category;
use App\Models\roles;
use App\Models\users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    //
    public function register()
    {
        if (SessionHandler::checkUserSession()) {
            return redirect()->back();
        }
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

        $user_role = roles::find('eaf20dd0-81cf-4115-8e9a-9ac5fe43ac21');
        if ($new_user) {
            SessionHandler::storeSessionDetails($new_user->user_id, $new_user->name, $user_role->role_name, $new_user->token);
            return redirect()->route('user.home');
        }
    }
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
                    $user_role = roles::find($user->role_id);
                    SessionHandler::storeSessionDetails($user->user_id, $user->name, $user_role->role_name, $user->token);
                    return redirect()->route('user.home');
                } else {
                    return redirect()->route('user.login')->with('message', 'Unmatched password. Please check your password again');
                }
            }
        } else {
            return redirect()->route('user.login')->with('message', 'You do not have an account on the app. Please create an account or Login with Facebook/Google');
        }
    }

    public function logout()
    {
        $user = users::find(session('userId'));
        $user->token = null;
        $user->save();
        if ($user) {
            SessionHandler::removeSessionDetails();
            return redirect()->route('user.login');
        }
    }

    public function home()
    {
        $categories = category::all()->sortBy('category');
        $books = books::all()->sortBy('book_name');
        foreach ($books as $book) {
            $review_count = DB::table('book_review')->where('book_id', '=', $book->book_id)->count();
            if ($review_count > 0) {
                $avg_review = DB::table('book_review')->where('book_id', '=', $book->book_id)->avg('rating');
                $book->review = $avg_review;
            } else {
                $book->review = 0;
            }
        }
        foreach ($categories as $category) {
            $count = DB::table('book_categories')->where('category_id', '=', $category->category_id)->count();
            $category->count = $count;
        }
        return view('users.home', compact('categories', 'books'));
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
                $user_role = roles::find($user->role_id);
                SessionHandler::storeSessionDetails($user->user_id, $user->name, $user_role->role_name, $user->token);
                return redirect()->route('user.home');
            } else {
                $user->is_auth = true;
                $user->auth_provider = $social;
                $user->image = $avatar;
                $user->token = Uuid::uuid4()->toString();
                $user->save();
                $user_role = roles::find($user->role_id);
                SessionHandler::storeSessionDetails($user->user_id, $user->name, $user_role->role_name, $user->token);
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
        $user_role = roles::where($new_user->role_id);
        SessionHandler::storeSessionDetails($new_user->user_id, $new_user->name, $user_role->role_name, $new_user->token);
        return redirect()->route('user.home');
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
