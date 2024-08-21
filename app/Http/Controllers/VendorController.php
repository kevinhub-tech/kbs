<?php

namespace App\Http\Controllers;

use App\Helpers\SessionHandler;
use App\Models\books;
use App\Models\category;
use App\Models\roles;
use App\Models\users;
use App\Models\vendorPartnership;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class VendorController extends Controller
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
     * Login Process logic code starts here
     */

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
                return redirect()->route('vendor.book-listing');
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

    /**
     * Web logic code starts here
     */
    public function booklisting()
    {   
        return view('vendors.listing-book');
    }

    public function bookdescription(String $id){
        $book = books::where('book_id', '=', $id)->first();
        $review_count = DB::table('book_review')->where('book_id', '=', $id)->count();
        if ($review_count > 0) {
            $avg_review = DB::table('book_review')->where('book_id', '=', $id)->avg('rating');
            $book->review = $avg_review;
        } else {
            $book->review = 0;
        }
        $vendor_info = vendorPartnership::where('vendor_id', '=', $book->created_by)->first();
        return view('vendors.book-desc', compact('book', 'vendor_info'));
    }

    public function book()
    {
        $categories = category::all();
        return view('vendors.book', compact('categories'));
    }

    public function editbook(String $id)
    {

        $book = books::find($id);
        $categories = category::all();
        $book_categories = DB::table('book_categories')->where('book_id', '=', $id)->get();
        return view('vendors.book', compact('book', 'categories', 'book_categories'));
    }

    /**
     * API logic code starts here
     */

    /**
     * Book API logic code starts here
     */
    public function postbook(Request $request)
    {
        $request->validate([
            'book_name' => ['required'],
            'book_desc' => ['required'],
            'author_name' => ['required'],
            'stock' => ['required'],
            'price' => ['required'],
            'delivery_fee' => ['required'],
            'categories' => ['required'],
            'image' => ['required', 'mimes:jpg,jpeg,png']
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $path = '/books';
            $book_image_name = uniqid('bk_') . '_' . time() . '.' . $extension;

            $saved_book_image = $file->storeAs($path, $book_image_name);


            if ($saved_book_image) {
                $new_book = books::create([
                    'book_name' => $request->book_name,
                    'book_desc' => $request->book_desc,
                    'author_name' => $request->author_name,
                    'stock' => $request->stock,
                    'price' => $request->price,
                    'image' => $book_image_name,
                    'delivery_fee' =>  $request->delivery_fee,
                    'created_by' => self::$user_id,
                    'created_at' => now()
                ]);

                if ($new_book) {
                    foreach ($request->categories as $category_id) {
                        DB::table('book_categories')->insert([
                            'category_id' => $category_id,
                            'book_id' => $new_book->book_id
                        ]);
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'You have successfully added a new book!',
                        'payload' => [],
                        'error' => []
                    ], Response::HTTP_CREATED);
                }
            }
        }
    }

    public function updatebook(Request $request)
    {
        $request->validate([
            'book_id' => ['required'],
            'book_name' => ['required'],
            'book_desc' => ['required'],
            'author_name' => ['required'],
            'stock' => ['required'],
            'price' => ['required'],
            'delivery_fee' => ['required'],
            'categories' => ['required'],
        ]);


        $book = books::find($request->book_id);
        $book->book_name = $request->book_name;
        $book->book_desc = $request->book_desc;
        $book->author_name = $request->author_name;
        $book->stock = $request->stock;
        $book->price = $request->price;
        $book->delivery_fee = $request->delivery_fee;
        $book->updated_by = self::$user_id;
        $book->updated_at = now();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $path = '/books';
            $book_image_name = uniqid('bk_') . '_' . time() . '.' . $extension;
            if ($book->image) {
                unlink(storage_path('app/books/' . $book->image));
            }
            $book->image = $book_image_name;
            $file->storeAs($path, $book_image_name);
        }
        $book->save();
        DB::table('book_categories')->where('book_id', '=', $request->book_id)->delete();
        foreach ($request->categories as $category_id) {
            DB::table('book_categories')->insert([
                'category_id' => $category_id,
                'book_id' => $request->book_id
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully updated the book!',
            'payload' => [],
            'error' => []
        ], Response::HTTP_OK);
    }
}
