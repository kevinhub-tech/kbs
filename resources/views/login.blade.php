@extends('main')
@section('main.content')
    <div class="d-flex justify-content-center align-items-center h-100">
        <form action="#" method="POST" class="login-form">
            <div class="logo">
                <a class="nav-logo d-flex align-items-center" href='#'>
                    <i class="fa-regular fa-lightbulb"></i>
                    <h2 class="m-0">
                        KBS 📖
                    </h2>
                </a>
            </div>
            <h4>Log Into
                Your
                @if (Request::url() === route('vendor.login'))
                    Vendor
                @elseif(Request::url() === route('admin.login'))
                    Admin
                @endif Account
            </h4>
            <label for="">Email:</label>
            <input type="text" name="email" id="">
            <label for="">Password:</label>
            <div class="input-container">
                <input type="password" name="password" id="password">
                <i class="fa-regular fa-eye" id="togglePassword"></i>
            </div>
            <button type="submit">Login</button>
            @if (Request::url() === route('user.login'))
                <p>Don't have an account? <a href="#">Register Here!</a></p>
                <p class="form-divider">Or</p>
                <h4 class="mt-2">Login with</h4>
                <div class="row">
                    <div class="facebook-login col-md-5 mb-2 mb-md-0 text-center">
                        <i class="fa-brands fa-facebook"></i>
                        <label for="facebook"> Facebook</label>
                    </div>
                    <div class="google-login offset-md-2 col-md-5 text-center">
                        <i class="fa-brands fa-google"></i>
                        <label for="google"> Google</label>
                    </div>
                </div>
            @endif
        </form>
    </div>
@endsection
