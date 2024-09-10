<?php

namespace App\View\Components;

use App\Models\users;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Profile extends Component
{
    /**
     * Create a new component instance.
     */
    private static $user_id;
    public function __construct()
    {

        self::$user_id = session('userId');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $user = users::find(self::$user_id);
        return view('components.profile', compact('user'));
    }
}
