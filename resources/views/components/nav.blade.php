<nav>
    {{-- This will be universally used --}}
    <div class="main-nav-container">
        {{-- Logo --}}
        <a class="nav-logo d-flex align-items-center" href='#'>
            <i class="fa-regular fa-lightbulb"></i>
            <h2 class="m-0">
                KBS
            </h2>
        </a>
        {{-- Search bar if user is customer --}}
        <div class="search-bar">
            <select name="" id="">
                <option value="books">Books</option>
                <option value="author">Authors</option>
                <option value="genre">Genre</option>
            </select>
            <input type="text" name="search" id="search-bar" placeholder="Search">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>


        {{-- These links will change based on user role --}}
        <ul>
            <li><a href="#">Login</a></li>
            <li><a href="#">Register</a></li>
        </ul>
    </div>

    {{-- If user is customer, include this sub menu with genre of books --}}
    <div class="sub-nav-container pt-1">
    </div>

</nav>
