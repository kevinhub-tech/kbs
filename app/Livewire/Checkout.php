<?php

namespace App\Livewire;

use App\Models\address;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Checkout extends Component
{   
    public $create_form_display = false;

    #[Validate('required')]
    public $address = '';

    #[Validate('required')]
    public $state = '';

    #[Validate('required|max:5')]
    public $postal_code = '';

    #[Validate('required')]
    public $phone_number = '';

    public $default_address = '';

    public $default_billing_address = '';

    public function create(){
        $this->create_form_display = true;
    }

    public function closeform(){
        $this->create_form_display = false;
    }

    public function saveaddress(){
        $address_count = address::all()->count();
        $validated = $this->validate();
        $validated['user_id'] = session('userId');

        if($address_count > 0 ){
            if($this->default_address){
                $validated['default_address'] = $this->default_address;
                // remove all default value on this user address
                $all_addresses = address::where('user_id', '=', session('userId'))->get();
                foreach($all_addresses as $address){
                    $address->default_address = false;
                    $address->save();
                } 
            }else{
                $validated['default_address'] = false;
            }
    
            if($this->default_billing_address){
                $validated['default_billing_address'] = $this->default_billing_address; 
                $all_addresses = address::where('user_id', '=', session('userId'))->get();
                foreach($all_addresses as $address){
                    $address->default_billing_address = false;
                    $address->save();
                } 
            }else{
                $validated['default_billing_address'] = false; 
            }
        }
    
        address::create($validated);
        $this->create_form_display = false;
        $message = 'Address has been successfully created!';
        $this->dispatch('render-component',  message: $message, status: 'success');
    }

    #[On('render-component')]
    public function render($message = null, $status = null)
    {
        $addresses = address::where('user_id', '=', session('userId'))->get();
        return view('livewire.checkout', compact('addresses'));
    }
}
