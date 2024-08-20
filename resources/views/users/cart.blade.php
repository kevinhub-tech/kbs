@push('head')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-header">
        <h3> <i class="fa-solid fa-cart-shopping me-3"></i>My Cart</h3>
        <div class="kbs-cart-link-container">
            <h3><a href="{{ route('user.home') }}">Continue to Shopping <i class="fa-solid fa-arrow-right"></i></a></h3>
            @if ($cart_items->isNotEmpty())
                <h3 class="kbs-remove-all" data-remove-type="cart" data-route="{{ route('user.removecart') }}"
                    data-token="{{ session('userToken') }}">Remove All <i class="fa-solid fa-x"></i></h3>
            @endif

        </div>

    </section>

    @if ($cart_items->isEmpty())
        <section class="kbs-cart-empty">
            <h3>
                Your cart is empty at the moment...
            </h3>
        </section>
    @else
        <section class='kbs-main-cart-container'>

            {{-- Cart items --}}
            <section class="kbs-cart-container">
                @foreach ($cart_items as $cart_item)
                    <div class="kbs-cart-card" data-book-id={{ $cart_item->book_details->book_id }}>
                        <h3 class="kbs-cart-serial-number">{{ $loop->iteration }}</h3>
                        <div class="kbs-cart-book-image">
                            <img src="{{ route('get-image', ['image' => $cart_item->book_details->image]) }}"
                                alt="">
                        </div>
                        <div class="kbs-cart-book-title">
                            <h3>Book</h3>
                            <h4>{{ $cart_item->book_details->book_name }}</h4>
                            <p class="kbs-book-author">By {{ $cart_item->book_details->author_name }}</p>
                        </div>
                        <div class="kbs-cart-book-quantity">
                            <h3>Quantity</h3>
                            <button class="substract-quantity" data-stock="{{ $cart_item->book_details->stock }}">
                                -
                            </button>
                            <small id="quantity">{{ $cart_item->quantity }}</small>
                            <button class="add-quantity" data-stock="{{ $cart_item->book_details->stock }}">
                                +
                            </button>
                        </div>
                        <div class="kbs-cart-book-price">
                            <h3>Book Price</h3>
                            <h4>{{ $cart_item->book_details->price }}</h4>
                        </div>
                        <div class="kbs-cart-book-subtotal-price">
                            <h3>Sub Total</h3>
                            <h4>{{ $cart_item->book_details->price * $cart_item->quantity }}</h4>
                        </div>
                        <div class="kbs-cart-action">
                            <i class="fa-solid fa-check" data-quantity="{{ $cart_item->quantity }}"
                                data-book-id="{{ $cart_item->book_details->book_id }}"
                                data-route="{{ route('user.updatecart') }}" data-token="{{ session('userToken') }}"></i>
                            <i class="fa-solid fa-trash" data-book-id="{{ $cart_item->book_details->book_id }}"
                                data-route="{{ route('user.removecart') }}" data-token="{{ session('userToken') }}"></i>
                        </div>
                    </div>
                @endforeach

            </section>
            {{-- Summary --}}
            <aside class="kbs-cart-summary-container">
                <div class="kbs-cart-summary">
                    <h3>Cart Summary</h3>

                    @foreach ($cart_items as $cart_item)
                        <div class="kbs-cart-book-details" data-book-id="{{ $cart_item->book_details->book_id }}"
                            data-original-price="{{ $cart_item->book_details->price }}"
                            data-delivery-price="{{ $cart_item->book_details->delivery_fee }}">
                            <h5>{{ $cart_item->book_details->book_name }} x <span
                                    class="quantity">{{ $cart_item->quantity }}</span></h5>

                            <h5>{{ $cart_item->book_details->price * $cart_item->quantity }}</h5>
                        </div>
                    @endforeach

                    <hr>

                    @php
                        $total_delivery_fee = 0;
                        $total_item_fee = 0;
                    @endphp
                    @foreach ($cart_items as $cart_item)
                        @php
                            $total_delivery_fee += $cart_item->book_details->delivery_fee;
                            $total_item_fee += $cart_item->book_details->price * $cart_item->quantity;
                        @endphp
                    @endforeach
                    <div class="kbs-cart-book-cost">
                        <h5 class="total-price">Total Book Price:</h5>
                        <h5>{{ $total_item_fee }}</h5>
                    </div>
                    <div class="kbs-cart-book-delivery-cost">
                        <h5>Delivery Fee:</h5>
                        <h5> {{ $total_delivery_fee / $cart_items->count() }}</h5>
                    </div>
                    <hr>
                    <div class="kbs-cart-book-total-cost">
                        <h5>Total Cost:</h5>
                        <h5> {{ $total_delivery_fee / $cart_items->count() + $total_item_fee }}</h5>
                    </div>
                    <button class="kbs-check-out">Check Out</button>

            </aside>
        </section>
    @endif


@endsection
@push('scripts')
    <script>
        cartFunction();
    </script>
@endpush
