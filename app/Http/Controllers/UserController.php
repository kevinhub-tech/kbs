<?php

namespace App\Http\Controllers;

use App\Helpers\SessionHandler;
use App\Models\address;
use App\Models\books;
use App\Models\category;
use App\Models\discounts;
use App\Models\orders;
use App\Models\roles;
use App\Models\users;
use App\Models\vendorPartnership;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    private static $user_id;
    public function __construct(Request $request)
    {
        if ($request->hasHeader('Authorization')) {
            $user = users::select('user_id')->where('token', '=', $request->header('Authorization'))->first();
            self::$user_id = $user->user_id;
        } else {
            self::$user_id = session('userId');
        }
    }

    /**
     * Login / Register Process logic code starts here
     */
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

        $user_role = roles::where('role_name', '=', 'user')->first();
        $new_user = users::create([
            'name' => $request->name,
            'email' => $request->email,
            'token' => Uuid::uuid4()->toString(),
            'password' => Hash::make($request->password),
            'role_id' => $user_role->role_id
        ]);

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

        $user_role = roles::where('role_name', '=', 'user')->first();
        $new_user = users::create([
            'name' => $auth_user->name,
            'email' => $auth_user->email,
            'is_auth' => true,
            'auth_provider' => $social,
            'image' => $avatar,
            'token' => Uuid::uuid4()->toString(),
            'role_id' => $user_role->role_id
        ]);

        SessionHandler::storeSessionDetails($new_user->user_id, $new_user->name, $user_role->role_name, $new_user->token);
        return redirect()->route('user.home');
    }

    /**
     * Web logic code starts here
     */

    public function home(Request $request)
    {
        $categories = category::all()->sortBy('category');
        if ($request->category) {
            $books = DB::table('book_categories as bc')->join('books as b', 'b.book_id', '=', 'bc.book_id')->where('category_id', '=', $request->category)->paginate(20);
        } else {
            $query = books::query();
            if ($request->c) {
                if ($request->c === 'books') {
                    $query->where('book_name', 'like', '%' . $request->v  . '%');
                } else {
                    $query->where('author_name', 'like', '%' . $request->v  . '%');
                }
            }

            $books = $query->orderBy('book_name')->paginate(20);
        }

        foreach ($books as $book) {
            if ($book->discount_id !== null) {
                $discount = discounts::where('discount_id', '=', $book->discount_id)->first();
                $discounted_price = $book->price * $discount->discount_percentage / 100;
                $book->discount_price =  number_format($book->price -  $discounted_price, 2, '.', "");
            }
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

    public function cart()
    {
        $cart_items = DB::table('user_cart')->where('user_id', '=', self::$user_id)->get();
        $sorted_books = [];
        foreach ($cart_items as $cart_item) {

            $book_details = books::where('book_id', '=', $cart_item->book_id)->first();
            if ($book_details->discount !== null) {
                $discounted_price = $book_details->price * $book_details->discount->discount_percentage / 100;
                $book_details->discount_price =  number_format($book_details->price -  $discounted_price, 2, '.', "");
            }
            $sorted_books[$book_details->created_by][] = $book_details;
            $cart_item->book_details = $book_details;
        }


        return view('users.cart', compact('cart_items', 'sorted_books'));
    }

    public function favourite()
    {
        $favourited_books = DB::table('user_favourites')->where('user_id', '=', self::$user_id)->get();
        foreach ($favourited_books as $favourited_book) {
            $book_details = books::where('book_id', '=', $favourited_book->book_id)->first();
            $review_count = DB::table('book_review')->where('book_id', '=', $favourited_book->book_id)->count();
            if ($book_details->discount !== null) {
                $discounted_price = $book_details->price * $book_details->discount->discount_percentage / 100;
                $book_details->discount_price =  number_format($book_details->price -  $discounted_price, 2, '.', "");
            }
            if ($review_count > 0) {
                $avg_review = DB::table('book_review')->where('book_id', '=', $favourited_book->book_id)->avg('rating');
                $book_details->review = $avg_review;
            } else {
                $book_details->review = 0;
            }
            $favourited_book->book_details = $book_details;
        }
        return view('users.favourites', compact('favourited_books'));
    }

    public function book(String $id)
    {
        $book = books::where('book_id', '=', $id)->first();
        $categories = DB::table('book_categories as bc')->join('categories as c', 'c.category_id', '=', 'bc.category_id')->where('book_id', '=', $id)->orderBy('c.category')->get();
        $book->categories = $categories;
        $review_count = DB::table('book_review')->where('book_id', '=', $id)->count();
        if ($book->discount !== null) {
            $discounted_price = $book->price * $book->discount->discount_percentage / 100;
            $book->discount_price =  number_format($book->price -  $discounted_price, 2, '.', "");
        }
        if ($review_count > 0) {
            $avg_review = DB::table('book_review')->where('book_id', '=', $id)->avg('rating');
            $book->review = $avg_review;
        } else {
            $book->review = 0;
        }
        $vendor_info = vendorPartnership::where('vendor_id', '=', $book->created_by)->first();
        return view('users.book', compact('book', 'vendor_info'));
    }

    public function checkout(Request $request)
    {
        $order_items = [];
        $vendor_ids = [];
        $sorted_order_items = [];
        foreach ($request->ids as $id) {
            $book = books::find($id);
            if ($book) {
                $cart_quantity = DB::table('user_cart')->where("book_id", '=', $id)->where('user_id', '=', self::$user_id)->first();
                if ($book->discount !== null) {
                    $discounted_price = $book->price * $book->discount->discount_percentage / 100;
                    $book->discount_price =  number_format($book->price -  $discounted_price, 2, '.', "");
                }
                if (!in_array($book->created_by, $vendor_ids)) {
                    $vendor_ids[] = $book->created_by;
                }
                if ($cart_quantity) {
                    $book->quantity = $cart_quantity->quantity;
                } else {
                    $book->quantity = 1;
                }

                $order_items[] = $book;
            }
        }

        $order_items = collect($order_items);
        foreach ($vendor_ids as $vendor_id) {
            $vendor_info = vendorPartnership::where('vendor_id', '=', $vendor_id)->first();
            $order_item = $order_items->filter(function ($value, $key) use ($vendor_id) {
                return $value->created_by === $vendor_id;
            });
            $sorted_order_items[$vendor_info->vendor_name] = $order_item;
        }

        $addresses = address::where('user_id', '=', session('userId'))->get();
        return view('users.checkout', compact('sorted_order_items', 'addresses'));
    }

    public function orderdetail(Request $request)
    {
        $order = orders::where('order_number', '=', $request->id)->first();
        $book_details = DB::table('ordered_book as ob')->join('books as b', 'b.book_id', '=', 'ob.book_id')->where('order_id', '=', $order->order_id)->get();
        $order_status = DB::table('order_status')->where('order_id', '=', $order->order_id)->get();
        return view('order-detail', compact('order', 'order_status', 'book_details'));
    }
    /**
     * API logic code starts here
     */

    /**
     * Cart API logic code starts here
     */
    public function addcart(Request $request)
    {
        /**
         * Accept book_id, quantity and return cart count
         */
        $request->validate([
            'book_id' => ['required'],
            'quantity' => ['required']
        ]);

        $book_found_count = DB::table('user_cart')->where('book_id', '=', $request->book_id)->count();

        if ($book_found_count > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'This book has been already added to your cart.',
                'payload' => []
            ], Response::HTTP_CONFLICT);
        }
        DB::table('user_cart')->insert([
            'user_id' => self::$user_id,
            'book_id' => $request->book_id,
            'quantity' => $request->quantity,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $cart_count = DB::table('user_cart')->where('user_id', '=', self::$user_id)->count();
        return response()->json([
            'status' => 'success',
            'message' => 'An item has been successfully added into your cart!',
            'payload' => [
                'cart_count' => $cart_count
            ]
        ], Response::HTTP_ACCEPTED);
    }

    public function updatecart(Request $request)
    {
        $request->validate([
            'book_id' => ['required'],
            'quantity' => ['required']
        ]);

        DB::table('user_cart')->updateOrInsert(['book_id' => $request->book_id, 'user_id' => self::$user_id], [
            'quantity' => $request->quantity,
            'updated_at' => now()
        ]);

        $cart_count = DB::table('user_cart')->where('user_id', '=', self::$user_id)->count();
        return response()->json([
            'status' => 'success',
            'message' => 'An item has been successfully updated within your cart!',
            'payload' => [
                'cart_count' => $cart_count
            ]
        ], Response::HTTP_ACCEPTED);
    }

    public function removecart(Request $request)
    {
        $request->validate([
            'method' => ['required']
        ]);

        if ($request->method === 'all') {
            $message = "All items have been successfully removed from your cart!";
            $deleted =  DB::table('user_cart')->where('user_id', '=', self::$user_id)->delete();
        } elseif ($request->method === 'partial') {
            $request->validate([
                'book_id' => ['required']
            ]);
            $message = "An item has been successfully removed from your cart!";
            $deleted = DB::table('user_cart')->where('user_id', '=', self::$user_id)->where('book_id', '=', $request->book_id)->delete();
        }

        if ($deleted) {
            $cart_count = DB::table('user_cart')->where('user_id', '=', self::$user_id)->count();

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'payload' => [
                    'cart_count' => $cart_count
                ]
            ], Response::HTTP_ACCEPTED);
        }
    }

    /**
     * Favourite API logic code starts here
     */
    public function addfavourite(Request $request)
    {
        /**
         * Accept book_id, quantity and return cart count
         */
        $request->validate([
            'book_id' => ['required']
        ]);

        $book_found_count = DB::table('user_favourites')->where('user_id', "=", self::$user_id)->where('book_id', '=', $request->book_id)->count();

        if ($book_found_count > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'This book has been already added to your favourites.',
                'payload' => []
            ], Response::HTTP_CONFLICT);
        }
        DB::table('user_favourites')->insert([
            'user_id' => self::$user_id,
            'book_id' => $request->book_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $favourite_count = DB::table('user_favourites')->where('user_id', '=', self::$user_id)->count();
        return response()->json([
            'status' => 'success',
            'message' => 'An item has been successfully added into your favourites!',
            'payload' => [
                'favourite_count' => $favourite_count
            ]
        ], Response::HTTP_ACCEPTED);
    }

    public function removefavourite(Request $request)
    {
        $request->validate([
            'method' => ['required']
        ]);

        if ($request->method === 'all') {
            $message = "All items have been successfully removed from your cart!";
            $deleted =  DB::table('user_favourites')->where('user_id', '=', self::$user_id)->delete();
        } elseif ($request->method === 'partial') {
            $request->validate([
                'book_id' => ['required']
            ]);
            $message = "An item has been successfully removed from your favourite!";
            $deleted = DB::table('user_favourites')->where('user_id', '=', self::$user_id)->where('book_id', '=', $request->book_id)->delete();
        }

        if ($deleted) {
            $favourite_count = DB::table('user_favourites')->where('user_id', '=', self::$user_id)->count();

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'payload' => [
                    'favourite_count' => $favourite_count
                ]
            ], Response::HTTP_ACCEPTED);
        }
    }

    /**
     * Order API logic code starts here
     */
    public function sendorder(Request $request)
    {

        $request->validate([
            'orders' => ['required']
        ]);
        foreach ($request->orders as $order) {
            $order_number = uniqid('kbs');
            $created_order = orders::create([
                'order_number' => $order_number,
                'payment_method' => $order['payment'],
                'refund_state' => 1,
                'is_cancelled' => 0,
                'total' => $order['total'],
                'address_id' => $order['address_id'],
                'billing_address_id' => $order['billing_address_id'],
                'vendor_id' => $order['vendor_id'],
                'ordered_by' => self::$user_id,
                'created_at' => now()
            ]);
            if ($created_order) {
                foreach ($order['order_book_mapping'] as $order_book_mapping) {
                    DB::table('ordered_book')->insert([
                        'order_id' => $created_order->order_id,
                        'book_id' => $order_book_mapping['book_id'],
                        'quantity' => $order_book_mapping['quantity'],
                        'ordered_book_price' => $order_book_mapping['ordered_book_price'],
                        'ordered_book_delivery_fee' => $order_book_mapping['ordered_book_delivery_fee']
                    ]);

                    DB::table('user_cart')->where('user_id', '=', self::$user_id)->where('book_id', '=', $order_book_mapping['book_id'])->delete();
                }
                DB::table('order_status')->insert([
                    'order_id' => $created_order->order_id,
                    'status' => 'pending',
                    'state' => 'current',
                    'sequence' => 0,
                    'created_at' => now()
                ]);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => "Your order has been sent successfully!",
            'payload' => []
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Order API logic code starts here
     */

    public function updateorderstatus(Request $request)
    {
        $request->validate([
            'status' => ['required'],
            'order_id' => ['required']
        ]);
        $order = orders::find($request->order_id);
        $order->updated_at = now();

        if ($request->status === "cancelled") {
            DB::table('order_status')->delete($request->order_id);
            DB::table('order_status')->insert([
                'order_id' => $request->order_id,
                'status' => $request->status,
                'state' => 'current',
                'sequence' => 0,
                'created_at' => now(),
            ]);
            $order->save();
            return response()->json([
                'status' => "success",
                'message' => "Your order has been cancelled successfully!",
                'payload' => []
            ], Response::HTTP_ACCEPTED);
        } else {
            $current_state = DB::table('order_status')->where('order_id', '=', $request->order_id)->where('state', '=', 'current')->first();
            DB::table('order_status')->where('order_id', '=', $request->order_id)->where('state', '=', 'current')->update(['state' => 'completed']);

            $status = DB::table('order_status')->insert([
                'order_id' => $request->order_id,
                'status' => $request->status,
                'state' => 'current',
                'sequence' => $current_state->sequence + 1,
                'created_at' => now(),
            ]);

            if ($status) {
                $order->save();
                return response()->json([
                    'status' => "success",
                    'message' => "Order Status has been updated successfully!",
                    'payload' => []
                ], Response::HTTP_ACCEPTED);
            }
        }
    }
}
