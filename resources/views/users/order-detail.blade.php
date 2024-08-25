@push('head')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-header">
        <h3> Order #{{$order->order_number}}</h3>
        <div class="kbs-cart-link-container">
            <h3><a href="{{ route('user.home') }}">Continue to Shopping <i class="fa-solid fa-arrow-right"></i></a></h3>
        </div>
    </section>
    <section class="kbs-order-detail-main-container">
        <section class="kbs-order-tracking-timeline">
            <h3>Order Tracking</h3>
            <ul>
                <li><i class="fa-solid fa-check"></i> Confirmed</li>
                <li class="line"></li>
                <li><i class="fa-solid fa-check"></i> Confirmed</li>
                <li class="line"></li>
                <li><i class="fa-solid fa-check"></i> Confirmed</li>
                <li class="line"></li>
                <li><i class="fa-solid fa-check"></i> Confirmed</li>
            </ul>
        </section>
        <section class="kbs-order-detail-container">

        </section>
    </section>  
    <h3>Order Detail Page</h3>
@endsection
@push('scripts')
    <script>
        favouriteFunction();
    </script>
@endpush
