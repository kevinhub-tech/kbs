@push('head')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-order-tracking">
        <form action="{{ route('user.orderdetail') }}">
            @csrf
            <h3>Order Tracking</h3>
            <input type="text" name="id" placeholder="Please Enter Order Number...." />
            <button type="submit">Search</button>
        </form>
    </section>
@endsection
@push('scripts')
    <script></script>
@endpush
