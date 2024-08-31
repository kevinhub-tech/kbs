<?php

namespace App\Livewire;

use App\Models\orders as ModelsOrders;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Orders extends Component
{
    public $search = "";
    public function render()
    {
        if ($this->search) {
            $orders = ModelsOrders::where('order_number', 'like', '%' . $this->search . '%')->where('vendor_id', '=', session('userId'))->paginate(15);
        } else {
            $orders = ModelsOrders::where('vendor_id', '=', session('userId'))->paginate(15);
        }

        foreach ($orders as $order) {
            $book_details = DB::table('ordered_book as ob')->join('books as b', 'b.book_id', '=', 'ob.book_id')->where('order_id', '=', $order->order_id)->get();
            $order->books = $book_details;
        }
        return view('livewire.orders', compact('orders'));
    }
}
