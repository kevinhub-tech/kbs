@extends('main')
@section('main.content')
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="denied-card">
            <h4>{{ session('accessDeny') }} ⚠️</h4>
            <a href="@if (session('roleAccess') === 'user') {{ route('user.login') }} @elseif(session('roleAccess') === 'vendor') {{ route('vendor.login') }} @elseif(session('roleAccess') === 'admin') {{ route('admin.login') }}@else javascript:history.back(); @endif"
                class="return">Login </a>
        </div>
    </div>
@endsection
