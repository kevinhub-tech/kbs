@push('head')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-header">
        <h3> Order #{{ $order->order_number }}</h3>
        @if (session('userSignedIn') && session('userRole') === 'user')
            <div class="kbs-link-container">
                <h3><a href="{{ route('user.home') }}">Continue to Shopping <i class="fa-solid fa-arrow-right"></i></a></h3>
            </div>
        @endif
    </section>
    <section class="kbs-order-detail-main-container">
        <section class="kbs-order-tracking-timeline">
            <h3>Order Timeline</h3>
            @php
                $is_cancelled = false;
                $is_delivered = false;
                $is_packed = false;
                $is_completed = false;
                foreach ($order_status as $status) {
                    if ($status->status === 'cancelled') {
                        $is_cancelled = true;
                    }

                    if ($status->status === 'delivered') {
                        $is_delivered = true;
                    }

                    if ($status->status === 'packed') {
                        $is_packed = true;
                    }

                    if ($status->status === 'completed') {
                        $is_completed = true;
                    }
                }
            @endphp
            @if ($order_status->count() === 1 && $is_cancelled)
                <ul>
                    <li class="cancelled">
                        <div class="content-wrapper">
                            <i class="fa-solid fa-x cancelled"></i>
                            <p>Cancelled</p>
                        </div>
                    </li>
                </ul>
            @else
                <ul>
                    <li
                        @if ($order_status->count() === 1) class="in-progress" @elseif($order_status->count() > 2) class="complete" @endif>
                        <div class="content-wrapper">
                            @if ($order_status->count() === 1)
                                <i class="fa-solid fa-clock in-progress"></i>
                                <p>Confirming</p>
                            @elseif($order_status->count() > 1)
                                <i class="fa-solid fa-check"></i>
                                <p>Confirmed</p>
                            @endif
                        </div>
                    </li>
                    <li
                        @if ($order_status->count() === 3) class="in-progress" @elseif($order_status->count() > 3) class="complete" @endif>
                        <div class="content-wrapper">
                            @if ($order_status->count() === 3)
                                <i class="fa-solid fa-box in-progress"></i>
                                <p>Packing</p>
                            @elseif($order_status->count() > 3)
                                <i class="fa-solid fa-check"></i>
                                <p>Packed</p>
                            @else
                                <i class="fa-solid fa-box in-complete"></i>
                                <p>Package</p>
                            @endif
                        </div>
                    </li>
                    <li
                        @if ($order_status->count() === 5) class="in-progress" @elseif($order_status->count() > 5)  class="complete" @endif>
                        <div class="content-wrapper">
                            @if ($order_status->count() === 5)
                                <i class="fa-solid fa-hand-holding-hand in-progress"></i>
                                <p>Handing Over</p>
                            @elseif($order_status->count() > 5)
                                <i class="fa-solid fa-check"></i>
                                <p>Handed Over</p>
                            @else
                                <i class="fa-solid fa-hand-holding-hand in-complete"></i>
                                <p>Hand-over</p>
                            @endif

                        </div>
                    </li>
                    <li
                        @if ($order_status->count() === 7) class="in-progress" @elseif($order_status->count() > 7)  class="complete" @endif>
                        <div class="content-wrapper">
                            @if ($order_status->count() === 7)
                                <i class="fa-solid fa-truck in-progress"></i>
                                <p>Delivering</p>
                            @elseif($order_status->count() > 7)
                                <i class="fa-solid fa-check"></i>
                                <p>Delivered</p>
                            @else
                                <i class="fa-solid fa-truck in-complete"></i>
                                <p>Delivery</p>
                            @endif
                        </div>
                    </li>
                    <li @if ($order_status->count() === 9) class="complete" @endif>
                        <div class="content-wrapper">
                            @if ($order_status->count() === 9)
                                <i class="fa-solid fa-check"></i>
                                <p>Completed</p>
                            @else
                                <i class="fa-solid fa-check in-complete"></i>
                                <p>Completion</p>
                            @endif
                        </div>
                    </li>

                </ul>
            @endif

            @if ($order_status->count() > 1)
                <section class="kbs-order-tracking-log">
                    @foreach ($order_status as $status)
                        @if ($status->status === 'confirmed')
                            <p> {{ date('d-m-Y H:i:s', strtotime($status->created_at)) }}: Your order has been confirmed
                                and
                                vendor
                                will start packing your orders</p>
                        @elseif($status->status === 'packing')
                            <p> {{ date('d-m-Y H:i:s', strtotime($status->created_at)) }}: Your order has begin packing
                                process and it will be packed by the vendor soon.</p>
                        @elseif($status->status === 'packed')
                            <p> {{ date('d-m-Y H:i:s', strtotime($status->created_at)) }}: Your order has been packed and it
                                will begin hand-over process for delivery service</p>
                        @elseif($status->status === 'handing-over')
                            <p> {{ date('d-m-Y H:i:s', strtotime($status->created_at)) }}: Your order has begin hand-over
                                process
                                to
                                the
                                delivery service.</p>
                        @elseif($status->status === 'handed-over')
                            <p> {{ date('d-m-Y H:i:s', strtotime($status->created_at)) }}: Your order has been handed-over
                                to
                                the
                                delivery service and delivery will soon begin</p>
                        @elseif($status->status === 'delivering')
                            <p> {{ date('d-m-Y H:i:s', strtotime($status->created_at)) }}: Your order is out for delivery
                                and your order should arrive soon</p>
                        @elseif($status->status === 'delivered')
                            <p> {{ date('d-m-Y H:i:s', strtotime($status->created_at)) }}: Your order has delivered! Waiting
                                for
                                the
                                customer to confirm the arrival of your order</p>
                        @elseif($status->status === 'completed')
                            <p> {{ date('d-m-Y H:i:s', strtotime($status->created_at)) }}: Your order has completed! Hope
                                you
                                enjoy
                                your book!</p>
                        @endif
                    @endforeach
                </section>
            @endif

        </section>
        <section class="kbs-order-detail-container">
            <section class="kbs-order-details">
                <h3>Order Details</h3>
                <p>Order By: {{ $order->order_user->name }}</p>
                <p>Order Number: {{ $order->order_number }}</p>
                <p>Order Payment: {{ Str::upper($order->payment_method) }}</p>
                <p>Order Refund State:
                    @if ($order->refund_state)
                        Refundable
                    @else
                        Non-refundable
                    @endif
                </p>

                <p>
                    Delivery Address: {{ $order->delivery_address->address }}, {{ $order->delivery_address->state }},
                    {{ $order->delivery_address->phone_number }}, {{ $order->delivery_address->postal_code }}
                </p>
                <p>
                    Billing Address: {{ $order->billing_address->address }}, {{ $order->billing_address->state }},
                    {{ $order->billing_address->phone_number }}, {{ $order->billing_address->postal_code }}
                </p>
                <p>
                    Payment State:
                    @if ($order->paid_at === null)
                        Not paid
                    @else
                        Paid at {{ date('d-m-Y', strtotime($order->paid_at)) }}
                    @endif
                </p>
                <p>
                    Delivery State:
                    @if ($order->delivered_at === null)
                        Not delivered
                    @else
                        Delivered at {{ date('d-m-Y', strtotime($order->paid_at)) }}
                    @endif
                </p>
                <p class="total">
                    Total:
                    ${{ $order->total }}
                </p>
                <p class="text-danger">Order Cancellable State:
                    @if ($order->refund_state)
                        Cancellable
                    @else
                        Non-cancellable
                    @endif
                </p>

                @if ($order->is_cancelled)
                    <p class="text-danger">Order Cancelled</p>
                @endif


            </section>
            <section class="kbs-order-book-details">
                <h3>Order's Book Details</h3>
                @foreach ($book_details as $book_detail)
                    <div class="kbs-book-details"">
                        <h5>{{ $book_detail->book_name }} x <span class="quantity">{{ $book_detail->quantity }}</span>
                        </h5>
                        <h5>${{ $book_detail->ordered_book_price * $book_detail->quantity }}</h5>
                    </div>
                @endforeach

                <hr>

                @php
                    $total_delivery_fee = 0;
                    $total_item_fee = 0;
                @endphp
                @foreach ($book_details as $book_detail)
                    @php
                        $total_delivery_fee += $book_detail->ordered_book_delivery_fee;
                        $total_item_fee += $book_detail->ordered_book_price * $book_detail->quantity;
                    @endphp
                @endforeach
                <div class="kbs-book-cost">
                    <h5 class="total-price">Total Book Price:</h5>
                    <h5>${{ $total_item_fee }}</h5>
                </div>
                <div class="kbs-book-delivery-cost">
                    <h5>Delivery Fee:</h5>
                    <h5>${{ $total_delivery_fee / $book_details->count() }}</h5>
                </div>
                <hr>
                <div class="kbs-book-total-cost">
                    <h5>Total Cost:</h5>
                    <h5>${{ $total_delivery_fee / $book_details->count() + $total_item_fee }}</h5>
                </div>
                @if (session('userSignedIn') && session('userRole') === 'user' && session('userId') === $order->ordered_by)
                    @if (!$is_packed)
                        <button name="order-status-updater" data-status="cancelled" data-order-id="{{ $order->order_id }}"
                            data-route="{{ route('user.updateorderstatus') }}" data-token="{{ session('userToken') }}"
                            class="kbs-cancel-order">Cancel Order</button>
                    @elseif($is_delivered && !$is_completed)
                        <button name="order-status-updater" data-status="completed" data-order-id="{{ $order->order_id }}"
                            data-route="{{ route('user.updateorderstatus') }}"
                            data-token="{{ session('userToken') }}"class="kbs-confirm-order">Confirm Order</button>
                    @endif
                @endif
            </section>
        </section>

    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(() => {
            $('button[name="order-status-updater"]').on('click', function(e) {
                let status = $(this).data('status');
                let route = $(this).data('route');
                let token = $(this).data('token');
                let orderId = $(this).data('orderId');
                let title = "";
                let popupText = '';
                let confirmButtonText = '';
                if (status === 'cancelled') {
                    title = "Cancel Order!"
                    popupText = 'Are you sure to cancel your order? This action is untracable.';
                    confirmButtonText = "Cancel";
                } else {
                    title = "Confirm Order!"
                    popupText =
                        'Have you receive your order yet? Please click on complete if you received your order!';
                    confirmButtonText = "Complete";
                }

                Swal.fire({
                    title: title,
                    icon: 'info',
                    text: popupText,
                    showConfirmButton: true,
                    showDenyButton: true,
                    allowOutsideClick: false,
                    confirmButtonText: confirmButtonText,
                    denyButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: route,
                            method: 'POST',
                            headers: {
                                Accept: "application/json",
                                Authorization: token
                            },
                            data: {
                                status: status,
                                order_id: orderId
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
                    } else if (result.isDenied) {
                        Swal.close();
                    }
                })
            })
        })
    </script>
@endpush
