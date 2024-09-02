<?php

namespace App\Livewire\Admin;

use App\Models\vendorApplication;
use Livewire\Component;

class Vendors extends Component
{
    public $search = "";
    public $status = "";
    public function render()
    {

        // Start with the base query
        $query = vendorApplication::query();

        if ($this->search) {
            $query->where('email', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', '=', $this->status);
        }

        $vendors = $query->paginate(15);


        return view('livewire.admin.vendors', compact('vendors'));
    }
}
