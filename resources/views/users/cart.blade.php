@push('head')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-header">
        <h3> <i class="fa-solid fa-cart-shopping me-3"></i>My Cart</h3>
        <div class="kbs-link-container">
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
                    @if ($cart_item->book_details)
                        <div class="kbs-cart-card" data-book-id={{ $cart_item->book_details->book_id }}
                            data-quantity="{{ $cart_item->quantity }}"
                            data-delivery-fee="{{ $cart_item->book_details->delivery_fee }}">
                            <div class="round">
                                <input type="checkbox" value="{{ $cart_item->book_details->book_id }}"
                                    name="{{ $cart_item->book_details->book_id }}"
                                    data-original-price="@if ($cart_item->book_details->discount === null) {{ $cart_item->book_details->price }} @else {{ $cart_item->book_details->discount_price }} @endif "
                                    data-delivery-price="{{ $cart_item->book_details->delivery_fee }}"
                                    data-vendor-id="{{ $cart_item->book_details->created_by }}"
                                    data-quantity="{{ $cart_item->quantity }}"
                                    data-title="{{ $cart_item->book_details->book_name }}"
                                    id="{{ $cart_item->book_details->book_id }}" checked />
                                <label class="checkbox" for="{{ $cart_item->book_details->book_id }}"></label>
                            </div>
                            <h3 class="kbs-cart-serial-number">{{ $loop->iteration }}</h3>
                            <div class="kbs-cart-book-image">
                                <img src="{{ route('get-image', ['route' => 'books', 'image' => $cart_item->book_details->image]) }}"
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
                                @if ($cart_item->book_details->discount === null)
                                    <h4 data-price={{ $cart_item->book_details->price }}>
                                        ${{ $cart_item->book_details->price }}</h4>
                                @else
                                    <h4 data-price={{ $cart_item->book_details->discount_price }}> <span
                                            class="original-price"> ${{ $cart_item->book_details->price }}</span><span
                                            class="discounted-price">
                                            ${{ $cart_item->book_details->discount_price }}</span>
                                    </h4>
                                @endif


                            </div>
                            <div class="kbs-cart-book-subtotal-price">
                                <h3>Total Sub Book Cost</h3>
                                @if ($cart_item->book_details->discount === null)
                                    <h4>{{ $cart_item->book_details->price * $cart_item->quantity }}
                                    </h4>
                                @else
                                    <h4>{{ $cart_item->book_details->discount_price * $cart_item->quantity }}
                                    </h4>
                                @endif

                            </div>
                            <div class="kbs-cart-delivery-fee-price">
                                <h3>Delivery Fee</h3>
                                <h4>{{ $cart_item->book_details->delivery_fee }}</h4>
                            </div>


                            <div class="kbs-cart-total-price">
                                <h3>Total</h3>
                                @if ($cart_item->book_details->discount === null)
                                    <h4 data-price={{ $cart_item->book_details->price }}
                                        data-delivery-fee={{ $cart_item->book_details->delivery_fee }}>
                                        {{ $cart_item->book_details->price * $cart_item->quantity + $cart_item->book_details->delivery_fee }}
                                    </h4>
                                @else
                                    <h4 data-price={{ $cart_item->book_details->discount_price }}
                                        data-delivery-fee={{ $cart_item->book_details->delivery_fee }}>
                                        {{ $cart_item->book_details->discount_price * $cart_item->quantity + $cart_item->book_details->delivery_fee }}
                                    </h4>
                                @endif

                            </div>

                            <div class="kbs-cart-action">
                                <i class="fa-solid fa-check" data-quantity="{{ $cart_item->quantity }}"
                                    data-book-id="{{ $cart_item->book_details->book_id }}"
                                    data-route="{{ route('user.updatecart') }}"
                                    data-token="{{ session('userToken') }}"></i>
                                <i class="fa-solid fa-trash" data-book-id="{{ $cart_item->book_details->book_id }}"
                                    data-route="{{ route('user.removecart') }}"
                                    data-token="{{ session('userToken') }}"></i>
                            </div>
                        </div>
                    @endif
                @endforeach

            </section>
            {{-- Summary --}}
            <aside class="kbs-cart-summary-container">
                <div class="kbs-cart-summary">
                    <h3>Cart Summary</h3>
                    <section class="kbs-cart-book-details-container">


                        @foreach ($cart_items as $cart_item)
                            @if ($cart_item->book_details)
                                <div class="kbs-cart-book-details" data-book-id="{{ $cart_item->book_details->book_id }}"
                                    data-original-price="@if ($cart_item->book_details->discount === null) {{ $cart_item->book_details->price }} @else {{ $cart_item->book_details->discount_price }} @endif"
                                    data-delivery-price="{{ $cart_item->book_details->delivery_fee }}"
                                    data-vendor-id="{{ $cart_item->book_details->created_by }}">
                                    <h5>{{ $cart_item->book_details->book_name }} x <span
                                            class="quantity">{{ $cart_item->quantity }}</span></h5>
                                    @if ($cart_item->book_details->discount === null)
                                        <h5>{{ $cart_item->book_details->price * $cart_item->quantity }}</h5>
                                    @else
                                        <h5>{{ $cart_item->book_details->discount_price * $cart_item->quantity }}</h5>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </section>
                    <hr>

                    @php
                        $total_delivery_fee = 0;
                        $total_item_fee = 0;
                        foreach ($sorted_books as $sorted_book) {
                            $book_count = count($sorted_book);
                            $delivery_fee = 0;
                            foreach ($sorted_book as $book) {
                                $cart_book = $cart_items
                                    ->filter(function ($value, $key) use ($book) {
                                        return $value->book_details->book_id === $book->book_id;
                                    })
                                    ->first();

                                $delivery_fee += $book->delivery_fee;
                                if ($book->discount === null) {
                                    $total_item_fee += $book->price * $cart_book->quantity;
                                } else {
                                    $total_item_fee += $book->price * $cart_book->quantity;
                                }
                            }
                            $total_delivery_fee = $delivery_fee / $book_count;
                        }
                    @endphp

                    <div class="kbs-cart-book-cost">
                        <h5 class="total-price">Total Book Price:</h5>
                        <h5>${{ $total_item_fee }}</h5>
                    </div>
                    <div class="kbs-cart-book-delivery-cost">
                        <h5>Delivery Fee:</h5>
                        <h5>{{ $total_delivery_fee }}</h5>
                    </div>
                    <hr>
                    <div class="kbs-cart-book-total-cost">
                        <h5>Total Cost:</h5>
                        <h5>{{ $total_delivery_fee + $total_item_fee }}</h5>
                    </div>

                </div>
                <button name="cart-check-out" class="kbs-check-out" data-route="{{ route('user.checkout') }}">Check
                    Out</button>
            </aside>
        </section>
    @endif


@endsection
@push('scripts')
    <script>
        cartFunction();
        @if ($errors)
            const errors = {!! json_encode($errors) !!};
            const errorIds = {!! json_encode($error_ids) !!};
        @else
            const errors = null;
        @endif
        let errorMessage = ``;
        if (errors) {
            errors.forEach((element, key) => {
                if (key !== errors.length - 1) {
                    errorMessage += element + ', '
                } else {
                    errorMessage += element
                }
            });
            if (errors.length > 1) {
                errorMessage +=
                    ' books are out of stock. Please kindly remove them from your cart to avoid conflict on your order. Apologies.'
            } else {
                errorMessage +=
                    ' book is out of stock. Please kindly remove them from your cart to avoid conflict on your order. Apologies.'
            }
            Swal.fire({
                title: "Info!",
                icon: 'info',
                text: errorMessage,
                showConfirmButton: true,
                showDenyButton: false,
                allowOutsideClick: false,
                confirmButtonText: 'Remove',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('user.selectiveremovecart') }}",
                        method: 'POST',
                        headers: {
                            Accept: "application/json",
                            Authorization: "{{ session('userToken') }}"
                        },
                        data: {
                            ids: errorIds
                        },
                        success: (res) => {
                            if (res.status === 'success') {
                                toast('success', res.message);
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            }
                        },
                        error: (jqXHR, exception) => {
                            var errorMessage = "";

                            if (jqXHR.status === 0) {
                                errorMessage =
                                    "Not connect.\n Verify Network.";
                            } else if (jqXHR.status == 404) {
                                errorMessage =
                                    "Requested page not found. [404]";
                            } else if (jqXHR.status == 500) {
                                errorMessage =
                                    "Internal Server Error [500].";
                            } else if (exception === "parsererror") {
                                errorMessage =
                                    "Requested JSON parse failed.";
                            } else if (exception === "timeout") {
                                errorMessage = "Time out error.";
                            } else if (exception === "abort") {
                                errorMessage = "Ajax request aborted.";
                            } else {
                                let html = ''
                                Object.values(jqXHR.responseJSON.errors).forEach((err) => {
                                    err.forEach((e) => {
                                        html += `${e} <hr />`;
                                    });
                                });
                                Swal.fire({
                                    title: 'Error!',
                                    html: html,
                                    icon: 'error',
                                    animation: true,
                                    showConfirmButton: true,
                                })
                                return;
                            }
                            toast("error", errorMessage);
                        }
                    })
                }
            })
        }
    </script>
@endpush
