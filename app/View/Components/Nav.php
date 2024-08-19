<?php

namespace App\View\Components;

use App\Models\users;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Nav extends Component
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
        $cart_count = DB::table('user_cart')->where('user_id', '=', self::$user_id)->count();
        $favourite_count = DB::table('user_favourites')->where('user_id', '=', self::$user_id)->count();
        return view('components.nav', compact('cart_count', 'favourite_count'));
    }
}
