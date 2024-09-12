<?php

namespace App\Http\Controllers;

use App\Helpers\SessionHandler;
use App\Mail\RegenerateLinkMail;
use App\Mail\VendorCredentialMail;
use App\Models\books;
use App\Models\category;
use App\Models\discounts;
use App\Models\orders;
use App\Models\roles;
use App\Models\users;
use App\Models\vendorApplication;
use App\Models\vendorPartnership;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;

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
            $avg_review = round(DB::table('book_review')->where('book_id', '=', $id)->avg('rating'));
            $reviews = DB::table('book_review as br')->join('users as u', 'br.reviewed_by', '=', 'u.user_id')->where('book_id', '=', $id)->select('br.*', 'u.image', 'u.name')->get();
            foreach ($reviews as $review) {
                $review->created_at = Carbon::parse($review->created_at)->diffForHumans();
                if ($review->updated_at !== null) {
                    $review->updated_at = Carbon::parse($review->updated_at)->diffForHumans();
                }
            }
            $book->reviews = $reviews;
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
    public function vendorapplication()
    {
        return view('vendorapplication');
    }
    public function vendorinfo(String $token)
    {
        $is_token_expired = false;

        $token = vendorApplication::where('token', '=', $token)->first();
        $token_expiration_date = Carbon::parse($token->token_expiration);

        if ($token_expiration_date->isPast()) {
            $is_token_expired = true;
        }
        return view('vendorinfo', compact('is_token_expired', 'token'));
    }
    public function sendapplication(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'application_letter' => ['required']
        ]);

        $new_vendor_application = vendorApplication::create([
            'email' => $request->email,
            'application_letter' => $request->application_letter,
            'status' => 'pending',
            'created_at' => now()
        ]);
        if ($new_vendor_application) {
            return redirect()->route('vendor.login')->with('message', 'We have received your application and will shortly response in mail with email provided once admin has reviewed your application. Thank you!');
        }
    }

    public function postvendorinfo(Request $request, String $token)
    {
        $request->validate([
            'vendor_name' => ['required'],
            'phone_number' => ['required'],
            'vendor_description' => ['required'],
            'facebook_link' => ['required'],
            'instagram_link' => ['required'],
            'youtube_link' => ['required'],
            'x_link' => ['required'],
            'other_link' => ['required']
        ]);

        $vendor_application = vendorApplication::where('token', '=', $token)->first();
        if ($vendor_application) {
            $username = strtolower(str_replace(' ', '', $request->vendor_name) . Str::random(10));
            $password = Str::password();
            $vendor_role = roles::where('role_name', '=', 'vendor')->first();
            $user = users::create([
                'name' => $username,
                'email' => $vendor_application->email,
                'password' => Hash::make($password),
                'role_id' => $vendor_role->role_id,
                'created_at' => now()
            ]);

            if ($user) {
                $new_vendor_info = vendorPartnership::create([
                    'vendor_application_id' => $vendor_application->application_id,
                    'vendor_name' => $request->vendor_name,
                    'email' => $vendor_application->email,
                    'phone_number' => $request->phone_number,
                    'vendor_description' => $request->vendor_description,
                    'facebook_link' => $request->facebook_link,
                    'instagram_link' => $request->instagram_link,
                    'youtube_link' => $request->youtube_link,
                    'x_link' => $request->x_link,
                    'other_link' => $request->other_link,
                    'vendor_id' => $user->user_id,
                    'created_at' => now()
                ]);
                if ($new_vendor_info) {
                    Mail::to($new_vendor_info->email)->send(new VendorCredentialMail($user->name, $password, $user->email));
                    return redirect()->route('vendor.vendorinfo', ['token' => $vendor_application->token])->with('message', 'Your store info has been saved succesfully and we will shortly give username and password for your vendor access.');
                }
            }
        }
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

    /**
     * Order API logic code starts here
     */

    public function updateorderstatus(Request $request)
    {
        $request->validate([
            'status' => ['required'],
            'order_id' => ['required']
        ]);

        if ($request->status === "cancelled") {
            DB::table('order_status')->delete($request->order_id);
            DB::table('order_status')->insert([
                'order_id' => $request->order_id,
                'status' => $request->status,
                'state' => 'current',
                'sequence' => 0,
                'created_at' => now(),
            ]);
            return response()->json([
                'status' => "success",
                'message' => "Your order has been cancelled successfully!",
                'payload' => []
            ], Response::HTTP_ACCEPTED);
        }

        $current_state = DB::table('order_status')->where('order_id', '=', $request->order_id)->where('state', '=', 'current')->first();
        DB::table('order_status')->where('order_id', '=', $request->order_id)->where('state', '=', 'current')->update(['state' => 'completed']);

        $status = DB::table('order_status')->insert([
            'order_id' => $request->order_id,
            'status' => $request->status,
            'state' => 'current',
            'sequence' => $current_state->sequence + 1,
            'created_at' => now(),
        ]);
        $order = orders::find($request->order_id);
        $order->updated_at = now();
        if ($status) {
            if ($request->status === 'packed') {
                // Reduce stock and set refundable to no
                $order->refund_state = 0;

                $order_books = DB::table('ordered_book')->where('order_id', '=', $request->order_id)->get();
                foreach ($order_books as $books) {
                    $book = books::find($books->book_id);
                    $book->stock = $book->stock - $books->quantity;
                    $book->save();
                }
            } else if ($request->status === "delivered") {
                // Update delivered_at and paid_at column with current time.
                $order->paid_at = now();
                $order->delivered_at = now();
            }
            $order->save();
            return response()->json([
                'status' => "success",
                'message' => "Order Status has been updated successfully!",
                'payload' => []
            ], Response::HTTP_ACCEPTED);
        }
    }

    public function regeneratelink(Request $request)
    {
        $request->validate([
            'id' => ['required']
        ]);

        $vendor_application = vendorApplication::find($request->id);

        if ($vendor_application) {
            $vendor_application->token = Uuid::uuid4()->toString();
            $vendor_application->token_expiration = now()->addHours(24);
            $vendor_application->updated_at = now();
            $vendor_application->save();

            Mail::to($vendor_application->email)
                ->send(new RegenerateLinkMail($vendor_application->application_id));
            return response()->json([
                'status' => 'success',
                'message' => "New link has been generated and send to your mail. Please check your mail",
                'payload' => []
            ], Response::HTTP_OK);
        }
    }
}
