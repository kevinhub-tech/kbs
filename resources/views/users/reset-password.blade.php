@extends('main')
@section('main.content')
    <div class="d-flex justify-content-center align-items-center h-100">
        <form action="{{ route('user.resetpassword') }}" method="POST" class="login-form">
            @csrf
            <div class="logo">
                <a class="nav-logo d-flex align-items-center" href='#'>
                    <h2 class="m-0">
                        Welcome Back {{ $user->name }}
                    </h2>
                </a>
            </div>
            <h4>Reset your Password
            </h4>

            <label for="">New Password:</label>
            <input type="hidden" name="id" value="{{ $user->user_id }}">
            <div class="input-container">
                <input type="password" name="new_password" id="password">
                <i class="fa-regular fa-eye" id="togglePassword"></i>
            </div>
            <label for="">Confirm Password:</label>
            <div class="input-container">
                <input type="password" name="confirm_password" id="password">
                <i class="fa-regular fa-eye" id="togglePassword"></i>
            </div>
            <button type="submit">Reset</button>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('i#togglePassword').on('click', function() {
                if ($(this).hasClass('fa-eye')) {
                    $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                    $(this).prev().attr('type', 'text');
                } else {
                    $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                    $(this).prev().attr('type', 'password');
                }
            });
        });
        @if (session('message'))
            const sessionMessage = "{{ session('message') }}";
        @else
            const sessionMessage = null;
        @endif

        if (sessionMessage) {
            toast('info', sessionMessage);
        }
    </script>
@endpush
