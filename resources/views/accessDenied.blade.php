@extends('main')
@section('main.content')
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="denied-card">
            <h4>{{ session('accessDeny') }} ⚠️</h4>
            <a href="{{ route('user.login') }}" class="return">Login</a>
        </div>
    </div>
@endsection
