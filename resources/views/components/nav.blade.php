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

        <div class="search-bar mb-3">
            <select name="" id="">
                <option value="books">Books</option>
                <option value="author">Authors</option>
                <option value="genre">Genre</option>
            </select>
            <input type="text" name="search" id="search-bar" placeholder="Search">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>



        {{-- These links will change based on user role --}}

        @if (Request::url() === route('user.login') || Request::url() === route('user.register'))
            <ul>
                <li><a href="{{ route('user.login') }}">Login</a></li>
                <li><a href="{{ route('user.register') }}">Register</a></li>
            </ul>
        @else
            <ul>
                <li><a href="#"><i class="fa-solid fa-cart-shopping"></i></a></li>
                <li><a href="#"><i class="fa-solid fa-heart"></i></a></li>
                <li><a href="#"><i class="fa-solid fa-user"></i></a></li>
            </ul>
        @endif
    </div>
</nav>
