<nav class="kbs-nav-bar">
    {{-- This will be universally used --}}
    <div class="main-nav-container d-flex flex-column flex-lg-row align-items-center justify-content-between">
        {{-- Logo --}}
        <a class="nav-logo d-flex align-items-center ms-md-5 ms-0 mb-3"
            @if (session('userSignedIn') && session('userRole') === 'user') href='{{ route('user.home') }}' @elseif(session('userSignedIn') && session('userRole') === 'vendor') 

             href='{{ route('vendor.dashboard') }}'
            @elseif(session('userSignedIn') && session('userRole') === 'admin') href='{{ route('admin.dashboard') }}' @else  href='{{ route('home') }}' @endif>
            <i class="fa-regular fa-lightbulb"></i>
            <h2 class="m-0">
                KBS
            </h2>
        </a>
        {{-- Search bar if user is customer --}}

        @if (session('userSignedIn') && session('userRole') === 'user')
            <div class="search-bar mb-3">
                <select name="" id="">
                    <option value="books" @if (Request::get('c') === 'books') selected @endif>Books</option>
                    <option value="author" @if (Request::get('c') === 'author') selected @endif>Authors</option>
                </select>
                <input type="text" name="search" id="search-bar" placeholder="Search"
                    @if (Request::get('v')) value="{{ Request::get('v') }}" @endif>
                <i class="fa-solid fa-magnifying-glass" data-route="{{ route('user.home') }}"></i>
            </div>
            <ul>
                <li><a class="nav-links" href="{{ route('user.cart') }}"><i class="fa-solid fa-cart-shopping"></i><small
                            class="cart-count">{{ $cart_count }}</small></a>
                </li>
                <li><a class="nav-links" href="{{ route('user.favourite') }}"><i class="fa-solid fa-heart"></i><small
                            class="favourite-count">{{ $favourite_count }}</small></a></li>
                <li>
                    <div class="dropdown show">
                        <a class="nav-links" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false" href="#"><i
                                class="fa-solid fa-user"></i></a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('user.address') }}">Profile</a>
                            <a class="dropdown-item" href="{{ route('user.orderlisting') }}">Orders</a>
                            <a class="dropdown-item" href="{{ route('user.reviews') }}">Reviews</a>
                            <a class="dropdown-item" href="{{ route('user.logout') }}">Log Out</a>
                        </div>
                    </div>
                </li>
            </ul>
        @endif

        @if (session('userSignedIn') && session('userRole') === 'vendor')
            <ul>
                <li><a href="{{ route('vendor.order-listing') }}">Manage Orders</a></li>
                <li><a href="{{ route('vendor.book-listing') }}">Manage Books</a></li>
                <li><a href="{{ route('vendor.discount-listing') }}">Manage Discounts</a></li>
                <li>
                    <div class="dropdown show">
                        <a class="nav-links" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false" href="#"><i
                                class="fa-solid fa-user"></i></a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item"
                                href="{{ route('vendor.vendorprofile', ['id' => session('userId')]) }}">Profile</a>
                            <a class="dropdown-item" href="{{ route('vendor.logout') }}">Log Out</a>
                        </div>
                    </div>
                </li>
            </ul>
        @endif

        @if (session('userSignedIn') && session('userRole') === 'admin')
            <ul>
                <li><a href="{{ route('admin.vendors') }}">Manage Vendors</a></li>
                <li>
                    <div class="dropdown show">
                        <a class="nav-links" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false" href="#"><i
                                class="fa-solid fa-user"></i></a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('admin.logout') }}">Log Out</a>
                        </div>
                    </div>
                </li>
            </ul>
        @endif




        {{-- These links will change based on user role --}}

        @if (
            (!session()->has('userSignedIn') && Request::url() === route('user.login')) ||
                Request::url() === route('user.register') ||
                Request::url() === route('home'))
            <ul>
                <li><a href="{{ route('user.login') }}">Login</a></li>
                <li><a href="{{ route('user.register') }}">Register</a></li>
            </ul>
        @else
        @endif
    </div>
</nav>
