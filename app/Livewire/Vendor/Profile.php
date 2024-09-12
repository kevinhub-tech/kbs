<?php

namespace App\Livewire\Vendor;

use App\Models\books;
use App\Models\discounts;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Profile extends Component
{
    public $show_books = true;
    public $show_reviews = false;
    public $id;
    use WithPagination;
    public function mount($id = null)
    {
        $this->id = $id;
    }

    public function books()
    {
        $this->show_books = true;
        $this->show_reviews = false;
        $this->resetPage();
    }

    public function reviews()
    {
        $this->show_reviews = true;
        $this->show_books = false;
        $this->resetPage();
    }

    public function render()
    {
        $books = books::where('created_by', '=', $this->id)->paginate(15);
        foreach ($books as $book) {
            if ($book->discount_id !== null) {
                $discount = discounts::where('discount_id', '=', $book->discount_id)->first();
                $discounted_price = $book->price * $discount->discount_percentage / 100;
                $book->discount_price =  number_format($book->price -  $discounted_price, 2, '.', "");
            }
            $review_count = DB::table('book_review')->where('book_id', '=', $book->book_id)->count();
            if ($review_count > 0) {
                $avg_review = round(DB::table('book_review')->where('book_id', '=', $book->book_id)->avg('rating'));
                $book->review = $avg_review;
            } else {
                $book->review = 0;
            }
        }
        $reviews = DB::table('vendor_review as vr')->join('users as u', 'vr.reviewed_by', '=', 'u.user_id')->where('vendor_id', '=', $this->id)->select('vr.*', 'u.image', 'u.name')->get();
        foreach ($reviews as $review) {
            $review->created_at = Carbon::parse($review->created_at)->diffForHumans();
            if ($review->updated_at !== null) {
                $review->updated_at = Carbon::parse($review->updated_at)->diffForHumans();
            }
        }
        return view('livewire.vendor.profile', compact('books', 'reviews'));
    }
}
