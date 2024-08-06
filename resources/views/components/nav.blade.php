<nav>
    {{-- This will be universally used --}}
    <div class="main-nav-container d-flex flex-column flex-lg-row align-items-center justify-content-between">
        {{-- Logo --}}
        <a class="nav-logo d-flex align-items-center ms-md-5 ms-0 mb-3" href='#'>
            <i class="fa-regular fa-lightbulb"></i>
            <h2 class="m-0">
                KBS
            </h2>
        </a>
        {{-- Search bar if user is customer --}}

        @if (session('userSignedIn') && session('userRole') === 'user')
            <div class="search-bar mb-3">
                <select name="" id="">
                    <option value="books">Books</option>
                    <option value="author">Authors</option>
                    <option value="genre">Genre</option>
                </select>
                <input type="text" name="search" id="search-bar" placeholder="Search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <ul>
                <li><a class="nav-links"href="#"><i class="fa-solid fa-cart-shopping"></i><small
                            class="cart-count">10</small></a>
                </li>
                <li><a class="nav-links" href="#"><i class="fa-solid fa-heart"></i><small
                            class="favourite-count">1</small></a></li>
                <li>
                    <div class="dropdown show">
                        <a class="nav-links" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false" href="#"><i
                                class="fa-solid fa-user"></i></a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#">Profile</a>
                            <a class="dropdown-item" href="#">Orders</a>
                            <a class="dropdown-item" href="#">Reviews</a>
                        </div>
                    </div>
                </li>
            </ul>
        @endif

        @if (session('userSignedIn') && session('userRole') === 'vendor')
            <ul>
                <li><a href="#">Manage Orders</a></li>
                <li><a href="#">Manage Books</a></li>
                <li><a href="#"><i class="fa-solid fa-user"></i></a></li>

            </ul>
        @endif

        @if (session('userSignedIn') && session('userRole') === 'admin')
            <ul>
                <li><a href="#">Manage Users</a></li>
                <li><a href="#">Manage Vendors</a></li>
                <li><a href="#"><i class="fa-solid fa-user"></i></a></li>
            </ul>
        @endif




        {{-- These links will change based on user role --}}

        @if (
            (!session()->has('userSignedIn') && Request::url() === route('user.login')) ||
                Request::url() === route('user.register'))
            <ul>
                <li><a href="{{ route('user.login') }}">Login</a></li>
                <li><a href="{{ route('user.register') }}">Register</a></li>
            </ul>
        @else
        @endif
    </div>
</nav>
