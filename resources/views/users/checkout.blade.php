@push('head')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-header">
        <h3> <i class="fa-solid fa-credit-card me-3"></i>Check Out</h3>
        <div class="kbs-cart-link-container">
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
            <div class="kbs-order-summary">
                <h3>Order Summary</h3>
                @if ($location === 'c')
                    @foreach ($order_items as $order_item)
                        <div class="kbs-order-book-details" data-book-id="{{ $order_item->book_details->book_id }}"
                            data-original-price="@if ($order_item->book_details->discount === null) {{ $order_item->book_details->price }} @else {{ $order_item->book_details->discount_price }} @endif"
                            data-delivery-price="{{ $order_item->book_details->delivery_fee }}"
                            data-quantity="{{ $order_item->quantity }}">
                            <h5>{{ $order_item->book_details->book_name }} x <span
                                    class="quantity">{{ $order_item->quantity }}</span></h5>
                            @if ($order_item->book_details->discount === null)
                                <h5>${{ $order_item->book_details->price * $order_item->quantity }}</h5>
                            @else
                                <h5>${{ $order_item->book_details->discount_price * $order_item->quantity }}</h5>
                            @endif
                        </div>
                    @endforeach

                    <hr>

                    @php
                        $total_delivery_fee = 0;
                        $total_item_fee = 0;
                    @endphp
                    @foreach ($order_items as $order_item)
                        @php
                            $total_delivery_fee += $order_item->book_details->delivery_fee;
                            if ($order_item->book_details->discount === null) {
                                $total_item_fee += $order_item->book_details->price * $order_item->quantity;
                            } else {
                                $total_item_fee += $order_item->book_details->discount_price * $order_item->quantity;
                            }
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
                @else
                    <div class="kbs-order-book-details" data-book-id="{{ $order_items->book_id }}"
                        data-original-price="@if ($order_items->discount === null) {{ $order_items->price }} @else {{ $order_items->discount_price }} @endif"
                        data-delivery-price="{{ $order_items->delivery_fee }}" data-quantity='1'>
                        <h5>{{ $order_items->book_name }} x <span class="quantity">1</span></h5>
                        @if ($order_items->discount === null)
                            <h5>${{ $order_items->price }}</h5>
                        @else
                            <h5>${{ $order_items->discount_price }}</h5>
                        @endif
                    </div>
                    <hr>
                    <div class="kbs-order-book-cost">
                        <h5 class="total-price">Total Book Price:</h5>
                        @if ($order_items->discount === null)
                            <h5>${{ $order_items->price }}</h5>
                        @else
                            <h5>${{ $order_items->discount_price }}</h5>
                        @endif
                    </div>
                    <div class="kbs-order-book-delivery-cost">
                        <h5>Delivery Fee:</h5>
                        <h5>${{ $order_items->delivery_fee }}</h5>
                    </div>
                    <hr>
                    <div class="kbs-order-book-total-cost">
                        <h5>Total Cost:</h5>
                        @if ($order_items->discount === null)
                            <h5>$<span>{{ $order_items->delivery_fee + $order_items->price }}</span></h5>
                        @else
                            <h5>$<span>{{ $order_items->delivery_fee + $order_items->discount_price }}</span></h5>
                        @endif

                    </div>
                @endif

                <button class="kbs-confirm-order" data-route="{{ route('user.sendorder') }}"
                    data-token="{{ session('userToken') }}" data-redirect-url="{{ route('user.orderdetail') }}">Check
                    Out</button>
            </div>
        </aside>
    </section>
@endsection
@push('scripts')
    <script>
        $('input[type="checkbox"]').on('click', function(event) {
            let addressId = $(this).data('addressId');
            let checkboxParentSection = $(this).parent().parent().parent();
            $(checkboxParentSection).children().each((index, element) => {
                if ($(element).prop('nodeName') === 'DIV') {
                    let checkbox = $(element).children().eq(0).children().eq(0);
                    if (checkbox.data('addressId') !== addressId) {
                        checkbox.prop('checked', false);
                    } else {
                        checkbox.prop('checked', true);
                    }
                }
            });
        });

        $('button.kbs-confirm-order').on('click', function(event) {
            let addressId = $('input[type="checkbox"][name="address"]:checked').val();
            let billingAddressId = $('input[type="checkbox"][name="billing_address"]:checked').val();
            let payment = $('input[type="checkbox"][name="payment"]:checked').val();
            let total = parseFloat($('div.kbs-order-book-total-cost').children().eq(1).children().eq(0).html());
            let route = $(this).data('route');
            let token = $(this).data('token');
            let redirectUrl = $(this).data('redirectUrl');
            let orderedBooks = [];
            $('div.kbs-order-book-details').each((index, div) => {
                let bookId = $(div).data('bookId');
                let price = $(div).data('originalPrice');
                let deliveryFee = $(div).data('deliveryPrice');
                let quantity = $(div).data('quantity');
                orderedBooks[index] = {
                    book_id: bookId,
                    quantity: quantity,
                    ordered_book_price: price,
                    ordered_book_delivery_fee: deliveryFee
                }
            })
            $.ajax({
                url: route,
                method: 'POST',
                headers: {
                    Accept: "application/json",
                    Authorization: token
                },
                data: {
                    address_id: addressId,
                    billing_address_id: billingAddressId,
                    payment: payment,
                    total: total,
                    order_book_mapping: orderedBooks
                },
                success: (res) => {
                    if (res.status === 'success') {
                        toast('success', res.message);

                        let order_number = res.payload.order_number
                        setTimeout(() => {
                            window.location.href = redirectUrl + '?' + 'id=' + order_number;
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
                    } else if (jqXHR.status == 409) {
                        errorMessage = jqXHR.responseJSON.message;
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
                        Object.values(jqXHR.responseJSON.errors).forEach((
                            err) => {
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
        })
    </script>
@endpush
