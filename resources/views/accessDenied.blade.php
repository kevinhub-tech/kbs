@extends('main')
@section('main.content')
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="denied-card">
            <h4>{{ session('accessDeny') }} ⚠️</h4>
            <a href="javascript:history.back()" class="return">Go Back</a>
        </div>
    </div>
@endsection
