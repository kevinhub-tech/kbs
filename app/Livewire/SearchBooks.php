<?php

namespace App\Livewire;

use App\Models\books;
use Livewire\Component;

class SearchBooks extends Component
{
    public $search = "";
    public function render()
    {   
        if($this->search){
            $books = books::where('book_name', 'like', '%'. $this->search . '%')->paginate(15);
        }else{
            $books = books::paginate(15);
        }
        return view('livewire.search-books', compact('books'));
    }
}
