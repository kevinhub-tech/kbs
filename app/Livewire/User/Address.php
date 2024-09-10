<?php

namespace App\Livewire\User;

use App\Models\address as ModelsAddress;
use Livewire\Component;

class Address extends Component
{

    public function render()
    {

        // Start with the base query
        $addresses = ModelsAddress::where('user_id', '=', session('userId'))->paginate(15);
        return view('livewire.user.address', compact('addresses'));
    }
}
