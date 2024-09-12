@push('head')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-header">
        <h3> <i class="fa-solid fa-credit-card me-3"></i>Check Out</h3>
        <div class="kbs-link-container">
            <h3><a href="javascript:history.back()">Go Back<i class="fa-solid fa-arrow-left ms-2"></i></a></h3>
        </div>
    </section>

    <section class="kbs-checkout-main-container">
        <section class="kbs-checkout-addresspayment-container">
            <livewire:checkout />


            {{-- Order Summary --}}
            <section class="kbs-payment-container">
                <h3>Payment</h3>
                <small> Credit/Debit card payment will be available soon...</small>
                <div class="kbs-payment-card">
                    <div class="round">
                        <input type="checkbox" name="payment" value="cod" checked id="checkbox" />
                        <label for="checkbox"></label>
                    </div>
                    <h4> <i class="fa-solid fa-truck me-3"></i> Cash On Delivery</h4>
                </div>
            </section>
        </section>
        <aside class="kbs-order-container">
            <h3 class="kbs-order-container-heading">Order Summary</h3>
            @foreach ($sorted_order_items as $vendor_name => $order_items)
                <div class="kbs-order-summary">

                    <h4>Order #{{ $loop->iteration }}</h4>
                    @foreach ($order_items as $order_item)
                        <div class="kbs-order-book-details" data-book-id="{{ $order_item->book_id }}"
                            data-original-price="@if ($order_item->discount === null) {{ $order_item->price }} @else {{ $order_item->discount_price }} @endif"
                            data-delivery-price="{{ $order_item->delivery_fee }}"
                            data-quantity="{{ $order_item->quantity }}">
                            <h5>{{ $order_item->book_name }} x <span class="quantity">{{ $order_item->quantity }}</span>
                            </h5>
                            @if ($order_item->discount === null)
                                <h5>${{ $order_item->price * $order_item->quantity }}</h5>
                            @else
                                <h5>${{ $order_item->discount_price * $order_item->quantity }}</h5>
                            @endif
                        </div>
                    @endforeach


                    <hr>

                    @php
                        $total_delivery_fee = 0;
                        $total_item_fee = 0;
                        $vendor_id = null;
                    @endphp
                    @foreach ($order_items as $order_item)
                        @php
                            $total_delivery_fee += $order_item->delivery_fee;
                            if ($order_item->discount === null) {
                                $total_item_fee += $order_item->price * $order_item->quantity;
                            } else {
                                $total_item_fee += $order_item->discount_price * $order_item->quantity;
                            }
                            $vendor_id = $order_item->created_by;
                        @endphp
                    @endforeach
                    <div class="kbs-order-book-cost">
                        <h5 class="total-price">Total Book Price:</h5>
                        <h5>${{ $total_item_fee }}</h5>
                    </div>
                    <div class="kbs-order-book-delivery-cost">
                        <h5>Delivery Fee:</h5>
                        <h5>${{ $total_delivery_fee / $order_items->count() }}</h5>
                    </div>
                    <hr>
                    <div class="kbs-order-book-total-cost">
                        <h5>Total Cost:</h5>
                        <h5>$<span>{{ $total_delivery_fee / $order_items->count() + $total_item_fee }}</span></h5>
                    </div>
                    <h5 class="kbs-order-book-vendor">From <a
                            href="{{ route('vendor.vendorprofile', ['id' => $vendor_id]) }}"
                            data-vendor-id={{ $vendor_id }}>
                            {{ $vendor_name }} </a></h5>
                    @if ($loop->iteration > 1)
                        <hr>
                    @endif
                </div>
            @endforeach
            <button class="kbs-confirm-order" data-route="{{ route('user.sendorder') }}"
                data-token="{{ session('userToken') }}" data-redirect-url="{{ route('user.orderdetail') }}">Confirm
                Order
            </button>
        </aside>
    </section>
@endsection
@push('scripts')
    <script>
        checkoutFunction();
    </script>
@endpush
