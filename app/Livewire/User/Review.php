<?php

namespace App\Livewire\User;

use App\Models\books;
use App\Models\orders;
use App\Models\vendorPartnership;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Review extends Component
{
    public $show_book_review = true;
    public $show_vendor_review = false;

    public function book_review()
    {
        $this->show_book_review = true;
        $this->show_vendor_review = false;
    }

    public function vendor_review()
    {
        $this->show_vendor_review = true;
        $this->show_book_review = false;
    }
    public function render()
    {
        $orders = orders::where('ordered_by', '=', session('userId'))->get();
        $book_reviews = [];
        $vendor_reviews = [];
        foreach ($orders as $order) {
            $vendor = vendorPartnership::where('vendor_id', '=', $order->vendor_id)->first();
            $vendor_review = DB::table('vendor_review')->where('vendor_id', '=', $order->vendor_id)->where('reviewed_by', '=', session('userId'))->first();
            if (!$vendor_review) {
                $vendor->rating = null;
                $vendor->review = null;
                $vendor->review_created_at = null;
                $vendor->review_updated_at = null;
            } else {
                $vendor->rating = $vendor_review->rating;
                $vendor->review = $vendor_review->review;
                $vendor->review_created_at = $vendor_review->created_at;
                $vendor->review_updated_at = $vendor_review->updated_at;
            }
            if (!array_key_exists($vendor->vendor_id, $vendor_reviews)) {
                $vendor_reviews[$vendor->vendor_id] = $vendor;
            }
            $ordered_books = DB::table('ordered_book')->where('order_id', '=', $order->order_id)->get();
            foreach ($ordered_books as $ordered_book) {
                $book = books::find($ordered_book->book_id);
                $book_review = DB::table('book_review')->where('book_id', '=', $book->book_id)->where('reviewed_by', '=', session('userId'))->first();
                if (!$book_review) {
                    $book->rating = null;
                    $book->review = null;
                    $book->review_created_at = null;
                    $book->review_updated_at = null;
                } else {
                    $book->rating = $book_review->rating;
                    $book->review = $book_review->review;
                    $book->review_created_at = $book_review->created_at;
                    $book->review_updated_at =  $book_review->updated_at;
                }
                if (!array_key_exists($book->book_id, $book_reviews)) {
                    $book_reviews[$book->book_id] = $book;
                }
            }
        }
        $book_reviews = collect($book_reviews);
        $vendor_reviews = collect($vendor_reviews);

        return view('livewire.user.review', compact('book_reviews', 'vendor_reviews'));
    }
}
