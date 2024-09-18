<footer>
    <div class="footer-container">
        <div class="logo">
            <a class="nav-logo d-flex align-items-center ms-lg-5 ms-0 mb-3"
                @if (session('userSignedIn') && session('userRole') === 'user') href='{{ route('user.home') }}' @elseif(session('userSignedIn') && session('userRole') === 'vendor') 

            href='{{ route('vendor.dashboard') }}'
           @elseif(session('userSignedIn') && session('userRole') === 'admin') href='{{ route('admin.dashboard') }}' @else  href='{{ route('home') }}' @endif>
                <i class="fa-solid fa-lightbulb"></i>
                <h2 class="m-0 ms-2">
                    KBS
                </h2>
            </a>
            <p>
                Welcome to KBS: Kevin's Book Store – Your Gateway to Infinite Worlds.
                <br>
                Discover an endless array of books, carefully curated from local vendors and partners. <br>Only the
                finest
                quality literature awaits you here.

            </p>
        </div>
        <div>
            <h5>User</h5>
            <ul>
                <li><a href="{{ route('user.ordertracking') }}">Track Order </a></li>
                <li><a href="{{ route('htb') }}">How to Buy </a></li>
                <li><a href="{{ route('tnc') }}">Terms and Conditions </a></li>
                <li><a href="{{ route('ufaq') }}">FAQ </a></li>
            </ul>
        </div>
        <div>
            <h5>Vendors</h5>
            <ul>
                <li><a href="{{ route('vendor.vendorapplication') }}">Become a Partner </a></li>
                <li><a href="{{ route('tnc') }}">Terms and Conditions </a></li>
                <li><a href="{{ route('vfaq') }}">FAQ </a></li>
            </ul>
        </div>
        <div>
            <h5>Follow Our Socials</h5>
            <div class="social-links">
                <a href="https://www.facebook.com"><i class="fa-brands fa-square-facebook"></i></a>
                <a href="https://www.instagram.com"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://www.x.com"><i class="fa-brands fa-square-x-twitter"></i></a>
            </div>
        </div>
    </div>
</footer>
<section class="copy-right">
    <h5 class="p-0 m-0">
        Designed and Developed by Kevin © 2024 💻. All right reserved.
    </h5>
</section>
