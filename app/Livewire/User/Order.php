<?php

namespace App\Livewire\User;

use App\Models\orders as ModelsOrders;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Order extends Component
{
    public $search = "";
    public function render()
    {
        // Start with the base query
        $query = ModelsOrders::where('ordered_by', session('userId'));

        // Add search condition if search input is provided
        if ($this->search) {
            $query->where('order_number', 'like', '%' . $this->search . '%');
        }

        $orders = $query->paginate(15);

        foreach ($orders as $order) {
            $book_details = DB::table('ordered_book as ob')->join('books as b', 'b.book_id', '=', 'ob.book_id')->where('order_id', '=', $order->order_id)->get();
            $order_status = DB::table('order_status')->where('order_id', '=', $order->order_id)->where('state', '=', 'current')->first();
            $order->status = $order_status;
            $order->books = $book_details;
        }


        return view('livewire.user.order', compact('orders'));
    }
}
