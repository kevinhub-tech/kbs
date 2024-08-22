<?php

namespace App\Livewire;

use App\Models\books;
use App\Models\discounts;
use Livewire\Component;

class SearchBooks extends Component
{
    public $search = "";
    public function render()
    {   
        if($this->search){
            $books = books::where('book_name', 'like', '%'. $this->search . '%')->where('created_by', '=', session('userId'))->paginate(15);
        }else{
            $books = books::where('created_by', '=', session('userId'))->paginate(15);
        }
        return view('livewire.search-books', compact('books'));
    }
}
