<?php

namespace App\Livewire;

use App\Models\books;
use App\Models\discounts;
use Livewire\Component;

class SearchDiscount extends Component
{
    public $search = "";

    public function render()
    {   
        if($this->search){
            $discounts = discounts::where('discount_percentage', 'like', '%'. $this->search . '%')->where('created_by', '=', session('userId'))->paginate(15);
        }else{
            $discounts = discounts::where('created_by', '=', session('userId'))->paginate(15);
        }
        $books = books::all();
        $discounts_dropdown = discounts::where('created_by', '=', session('userId'))->get();
        return view('livewire.search-discount', compact('books','discounts','discounts_dropdown'));
    }
}
