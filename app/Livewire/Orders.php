<?php

namespace App\Livewire;

use App\Models\orders as ModelsOrders;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Orders extends Component
{
    public $search = "";
    public $status = "";
    public function render()
    {
        // Start with the base query
        $query = ModelsOrders::where('vendor_id', session('userId'));

        // Add search condition if search input is provided
        if ($this->search) {
            $query->where('order_number', 'like', '%' . $this->search . '%');
        }

        // Add status condition if status is selected using a join
        if ($this->status) {
            $query->join('order_status as os', 'os.order_id', '=', 'orders.order_id')
                ->where('os.status', '=', $this->status)->where('os.state', '=', 'current');
        }

        $orders = $query->paginate(15);

        foreach ($orders as $order) {
            $book_details = DB::table('ordered_book as ob')->join('books as b', 'b.book_id', '=', 'ob.book_id')->where('order_id', '=', $order->order_id)->get();
            $order_status = DB::table('order_status')->where('order_id', '=', $order->order_id)->where('state', '=', 'current')->first();
            $order->status = $order_status;
            $order->books = $book_details;
        }


        return view('livewire.orders', compact('orders'));
    }
}
