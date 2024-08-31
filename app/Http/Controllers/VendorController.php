<?php

namespace App\Http\Controllers;

use App\Helpers\SessionHandler;
use App\Models\books;
use App\Models\category;
use App\Models\discounts;
use App\Models\roles;
use App\Models\users;
use App\Models\vendorPartnership;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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

    public function bookdescription(String $id)
    {
        $book = books::where('book_id', '=', $id)->first();
        $categories = DB::table('book_categories as bc')->join('categories as c', 'c.category_id', '=', 'bc.category_id')->where('book_id', '=', $id)->orderBy('c.category')->get();
        $book->categories = $categories;
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

    public function discountlisting()
    {
        return view('vendors.discounts');
    }

    public function orderlisting()
    {
        return view('vendors.listing-order');
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
            'book_name' => ['required', 'unique:App\Models\books,book_name'],
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

    /**
     * Discount API logic code starts here
     */
    public function creatediscount(Request $request)
    {
        $validated_discount = $request->validate([
            'discount_percentage' => ['required', 'unique:App\Models\Discounts,discount_percentage']
        ]);

        $validated_discount['created_by'] = self::$user_id;
        $validated_discount['created_at'] = now();

        discounts::create($validated_discount);

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully added a discount!',
            'payload' => [],
            'error' => []
        ], Response::HTTP_OK);
    }

    public function applydiscount(Request $request)
    {
        $request->validate([
            'discount_id' => ['required'],
            'books' => ['required'],
        ]);

        foreach ($request->books as $book_id) {
            $book = books::find($book_id);
            if (!$book) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Book cannot be found! Please kindly check first in book listing.',
                    'payload' => [],
                    'error' => []
                ], Response::HTTP_CONFLICT);
            }

            $book->discount_id = $request->discount_id;
            $book->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully applied discount on books',
            'payload' => [],
            'error' => []
        ], Response::HTTP_OK);
    }

    /**
     * Discount API logic code starts here
     */
    public function editdiscount(Request $request)
    {
        $request->validate([
            'discount_id' => ['required'],
            'discount_percentage' => ['required',  Rule::unique('discounts', 'discount_percentage')->ignore($request->discount_id, 'discount_id')]
        ]);

        $discount = discounts::find($request->discount_id);

        $discount->discount_percentage = $request->discount_percentage;
        $discount->updated_by = self::$user_id;
        $discount->updated_at = now();
        $discount->save();


        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully updated the discount percentage!',
            'payload' => [],
            'error' => []
        ], Response::HTTP_OK);
    }

    public function removediscount(Request $request)
    {
        $request->validate([
            'method' => ['required'],
            'discount_id' => ['required']
        ]);

        if ($request->method === 'all') {
            $message = "All books' discount has been successfully removed!";
            $removed =  books::where('discount_id', $request->discount_id)->update(['discount_id' => null]);
        } elseif ($request->method === 'partial') {
            $request->validate([
                'book_id' => ['required']
            ]);
            $message = "Discount has been successfully removed from the book!";
            $removed = books::where('book_id', $request->book_id)->update(['discount_id' => null]);
        }

        if ($removed) {
            $discount_count = books::where("discount_id", '=', $request->discount_id)->count();

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'payload' => [
                    'discount_count' => $discount_count
                ]
            ], Response::HTTP_ACCEPTED);
        }
    }

    public function deletediscount(Request $request)
    {
        $request->validate([
            'discount_id' => ['required']
        ]);

        $deleted = discounts::destroy($request->discount_id);
        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'A discount has been successfully deleted!',
                'payload' => []
            ], Response::HTTP_ACCEPTED);
        }
    }
}
