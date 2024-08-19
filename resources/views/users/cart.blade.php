@push('head')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-cart-header">
        <h3> <i class="fa-solid fa-cart-shopping me-3"></i>My Cart</h3>
        <div class="kbs-cart-link-container">
            <h3><a href="{{ route('user.home') }}">Continue to Shopping <i class="fa-solid fa-arrow-right"></i></a></h3>
            @if ($cart_items->isNotEmpty())
                <h3 class="kbs-remove-all">Remove All <i class="fa-solid fa-x"></i></h3>
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
                                data-book-id="{{ $cart_item->book_details->book_id }}"></i>
                            <i class="fa-solid fa-trash" data-book-id="{{ $cart_item->book_details->book_id }}"></i>
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
        const updateTotalSection = () => {

            let totalLatestBookCost = 0;
            let totalDeliveryFee = 0;
            let totalBookCount = 0;
            $('div.kbs-cart-book-details').each((index, div) => {
                totalLatestBookCost += parseFloat($(div).children()
                    .eq(1).html());


                totalDeliveryFee += parseFloat($(div).data(
                    'deliveryPrice'));
                totalBookCount = index + 1;
            })
            let finalUpdatedDeliveryFee = totalDeliveryFee / totalBookCount;
            $('div.kbs-cart-book-cost').children().eq(1).html(
                totalLatestBookCost);

            $('div.kbs-cart-book-delivery-cost').children().eq(1)
                .html(
                    finalUpdatedDeliveryFee);

            let finalUpdatedSumCost = totalLatestBookCost +
                finalUpdatedDeliveryFee;
            $('div.kbs-cart-book-total-cost').children().eq(1)
                .html(
                    finalUpdatedSumCost.toFixed(2));
        }
        $("div.kbs-cart-book-quantity button").on('click', function(e) {
            let button = e.currentTarget;
            let buttonClassName = e.currentTarget.className
            let stock = parseInt(button.dataset.stock);

            let totalPriceElement = button.parentElement.nextElementSibling.nextElementSibling.children[1];
            let totalPrice = parseFloat(button.parentElement.nextElementSibling.children[1]
                .innerHTML);
            let addButton = button.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.children[
                0];
            $(addButton).css('display', 'inline-block');
            if (buttonClassName === 'add-quantity') {
                let quantityElement = button.previousElementSibling;
                let quantity = parseInt(quantityElement.innerHTML);
                quantity += 1;
                if (quantity === stock) {
                    quantityElement.innerHTML = quantity;
                    button.setAttribute('disabled', true);
                } else if (quantity > 1) {
                    button.previousElementSibling.previousElementSibling.removeAttribute('disabled');
                    quantityElement.innerHTML = quantity;
                } else {
                    quantityElement.innerHTML = quantity;
                }
                totalPrice *= quantity;
                totalPriceElement.innerHTML = totalPrice;
                addButton.dataset.quantity = quantity;
            } else {
                let quantityElement = button.nextElementSibling;
                let quantity = parseInt(quantityElement.innerHTML);
                quantity -= 1;

                if (quantity < 2) {
                    button.setAttribute('disabled', true);
                    quantityElement.innerHTML = quantity;
                } else if (quantity < stock) {
                    button.nextElementSibling.nextElementSibling.removeAttribute('disabled');
                    quantityElement.innerHTML = quantity;
                } else {
                    quantityElement.innerHTML = quantity;
                }
                totalPrice *= quantity;
                totalPriceElement.innerHTML = totalPrice;
                addButton.dataset.quantity = quantity;
            }
        })

        $("i.fa-solid.fa-check").on('click', function(e) {
            Swal.fire({
                title: "Info!",
                icon: 'info',
                text: "You are about to update an item in your cart. Are you sure?",
                showConfirmButton: true,
                showDenyButton: true,
                allowOutsideClick: false,
                confirmButtonText: 'Update',
                denyButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    let bookId = e.currentTarget.dataset.bookId;
                    let updatedBookQuantity = parseInt(e.currentTarget.dataset.quantity);

                    $.ajax({
                        url: "{{ route('user.updatecart') }}",
                        method: 'POST',
                        headers: {
                            Accept: "application/json",
                            Authorization: "{{ session('userToken') }}"
                        },
                        data: {
                            book_id: bookId,
                            quantity: updatedBookQuantity
                        },
                        success: (res) => {
                            if (res.status === 'success') {
                                toast('success', res.message);
                                $("small.cart-count").html(res.payload.cart_count);
                                $("small.cart-count").css('display', 'flex');

                                let originalPrice = parseFloat($(
                                        `div.kbs-cart-book-details[data-book-id='${bookId}']`
                                    )
                                    .data('original-price'));

                                $(`div.kbs-cart-book-details[data-book-id='${bookId}']`)
                                    .children().eq(0).children().html(updatedBookQuantity);
                                let latestSubTotal = originalPrice * updatedBookQuantity;

                                $(`div.kbs-cart-book-details[data-book-id='${bookId}']`)
                                    .children().eq(1).html(latestSubTotal);

                                updateTotalSection();
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

        $("i.fa-solid.fa-trash").on('click', function(e) {
            Swal.fire({
                title: "Info!",
                icon: 'info',
                text: "You are about to delete an item in your cart. Are you sure?",
                showConfirmButton: true,
                showDenyButton: true,
                allowOutsideClick: false,
                confirmButtonText: 'Remove',
                denyButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    let bookId = e.currentTarget.dataset.bookId;

                    $.ajax({
                        url: "{{ route('user.removecart') }}",
                        method: 'POST',
                        headers: {
                            Accept: "application/json",
                            Authorization: "{{ session('userToken') }}"
                        },
                        data: {
                            book_id: bookId,
                            method: 'partial'
                        },
                        success: (res) => {
                            if (res.status === 'success') {
                                toast('success', res.message);
                                $("small.cart-count").html(res.payload.cart_count);
                                $("small.cart-count").css('display', 'flex');

                                // Remove from cart item and cart summary
                                $(`div.kbs-cart-card[data-book-id='${bookId}']`)
                                    .remove();

                                // Reset serial number
                                $('div.kbs-cart-card').each((index, div) => {
                                    $(div).children().eq(0).html(index + 1);
                                })

                                $(`div.kbs-cart-book-details[data-book-id='${bookId}']`)
                                    .remove();

                                updateTotalSection();
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

        $("h3.kbs-remove-all").on('click', function(e) {
            Swal.fire({
                title: "Info!",
                icon: 'info',
                text: "You are about to delete all item in your cart. Are you sure?",
                showConfirmButton: true,
                showDenyButton: true,
                allowOutsideClick: false,
                confirmButtonText: 'Proceed',
                denyButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('user.removecart') }}",
                        method: 'POST',
                        headers: {
                            Accept: "application/json",
                            Authorization: "{{ session('userToken') }}"
                        },
                        data: {
                            method: 'all'
                        },
                        success: (res) => {
                            if (res.status === 'success') {
                                toast('success', res.message);
                                $("small.cart-count").html(res.payload.cart_count);
                                $("small.cart-count").css('display', 'none');

                                $('section.kbs-main-cart-container').remove();
                                $('h3.kbs-remove-all').remove();
                                let cartEmptyhtml = `  
                                <section class="kbs-cart-empty">
                                    <h3>
                                        Your cart is empty at the moment...
                                    </h3>
                                </section>`;
                                $('main').append(cartEmptyhtml);
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
    </script>
@endpush
