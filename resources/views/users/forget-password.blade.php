@push('head')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-forget-password">
        <form action="{{ route('user.sendforgetpassword') }}" method="POST">
            @csrf
            <h3>Forget Password</h3>
            <input type="text" name="email" placeholder="Please Enter Email Address...." />
            <button type="submit">Send Link</button>
        </form>
    </section>
@endsection
@push('scripts')
    <script>
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
