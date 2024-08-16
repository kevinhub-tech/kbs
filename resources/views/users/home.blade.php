@push('head')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-book-container">
        <aside class="kbs-book-categories">
            <h3>Book Categories</h3>
            <ul>
                @foreach ($categories as $category)
                    <li><a href="">{{ $category->category }} ({{ $category->count }})</a></li>
                @endforeach
            </ul>
        </aside>
        <section class="kbs-book-listing @if ($books->isEmpty()) empty @endif">
            @if ($books->isEmpty())
                <h3 class="kbs-book-empty">Oops! Books are not available or not found...
                </h3>
            @else
                <div class='kbs-book-listing-container'>

                    @foreach ($books as $book)
                        <div class="kbs-book-card">
                            <div class='kbs-book-card-container'>
                                <img src="{{ route('get-image', ['image' => $book->image]) }}" alt=""
                                    class="book-image">
                                <div class='kbs-book-details'>
                                    <a href="#" class="book-title">
                                        <h4>{{ $book->book_name }}</h4>
                                    </a>
                                    <div class="star-rating" bookid="something" rating='{{ $book->review }}'>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="rating">{{ $book->review }} stars</span>
                                    </div>
                                    <p class='book-author'>{{ $book->author_name }}</p>
                                    <h4>Price : ${{ $book->price }}</h4>
                                    <small class="stock">Stock : {{ $book->stock }}</small><br>
                                    <button class='kbs-purchase'>Purchase Now</button>
                                    <div class="d-flex justify-content-evenly align-items-center mt-3">
                                        <!-- Button trigger modal -->
                                        <a class="kbs-buttom" data-bs-toggle="modal"
                                            data-bs-target="#{{ $book->book_id }}">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="{{ $book->book_id }}" data-bs-backdrop="static"
                                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 id="staticBackdropLabel">Add to Cart</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" id="{{ $book->book_id }}">
                                                        <div class='d-flex justify-content-evenly align-items-center'>
                                                            <img src="{{ route('get-image', ['image' => $book->image]) }}"
                                                                alt="" class="book-image">
                                                            <div class="d-flex flex-column justify-content-around">
                                                                <h4>{{ $book->book_name }}</h4>
                                                                <p class='book-author'>By {{ $book->author_name }}</p>
                                                                <h4>Price : ${{ $book->price }}</h4>
                                                                <small class="stock">Stock :
                                                                    <span>{{ $book->stock }}</span></small><br>
                                                                <label for="">Quantity:</label>
                                                                <div class="cart-quantity">
                                                                    <button class="substract-quantity" disabled>
                                                                        -
                                                                    </button>
                                                                    <small id="quantity">1</small>
                                                                    <button class="add-quantity">
                                                                        +
                                                                    </button>
                                                                </div>
                                                                <label for="" class="mt-3">Total Price:
                                                                    $<span id="total-price"
                                                                        original-price='{{ $book->price }}'>{{ $book->price }}</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn kbs-btn"
                                                            data-book-id='{{ $book->book_id }}'
                                                            data-quantity='1'>Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-heart add-to-favourite"
                                            data-book-id="{{ $book->book_id }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            @endif

            <div>
                {{ $books->links() }}
            </div>

        </section>
    </section>
@endsection
@push('scripts')
    <script>
        const setBookListingHeightDynamically = () => {
            if (screen.width > 1116) {
                if ($(".kbs-book-listing-container").height() > $(".kbs-book-categories ul").height()) {
                    $(".kbs-book-listing-container").css("height", $(".kbs-book-categories ul")
                        .height());
                }
                $(".kbs-book-listing-container").css("height", $(".kbs-book-categories ul")
                    .height());
            } else {
                $(".kbs-book-listing-container").removeAttr('style');
            }
        }
        $(document).ready(function() {
            $("small.cart-count").html('{{ $cart_count }}');
            $("small.favourite-count").html('{{ $favourite_count }}');
            $(window).resize(function() {
                setBookListingHeightDynamically();
            });
            setBookListingHeightDynamically();

            $("div.star-rating").each(function(index, div) {
                let divStarRating = $(div);
                let rating = divStarRating.attr('rating') - 1;
                for (i = 0; i <= rating; i++) {
                    divStarRating.children().eq(i).addClass('checked');
                }
            })

            $("div.cart-quantity button").on('click', function(e) {
                let button = e.currentTarget;
                let buttonClassName = e.currentTarget.className
                let stock = parseInt(button.parentElement.previousElementSibling.previousElementSibling
                    .previousElementSibling.children[0].innerHTML);
                let totalPriceElement = button.parentElement.nextElementSibling.children[0];
                let totalPrice = parseFloat(button.parentElement.nextElementSibling.children[0]
                    .getAttribute('original-price'));
                let addButton = button.parentElement.parentElement.parentElement.parentElement
                    .nextElementSibling.children[1];
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
            $("div.modal-footer button.kbs-btn").on('click', function(e) {
                // Get book_id and quantity
                let bookId = $(this).data('book-id');
                let quantity = $(this).data('quantity');

                // Call ajax and once it is succesful, check cart-count and add that number there
                $.ajax({
                    url: "{{ route('user.addcart') }}",
                    method: 'POST',
                    headers: {
                        Accept: "application/json",
                        Authorization: "{{ session('userToken') }}"
                    },
                    data: {
                        book_id: bookId,
                        quantity: quantity
                    },
                    success: (res) => {
                        if (res.status === 'success') {
                            toast('success', res.message);
                            $("small.cart-count").html(res.payload.cart_count);
                            $("small.cart-count").css('display', 'flex');
                            setTimeout(() => {
                                $(`#${bookId}`).modal('hide');
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
            })

            $("i.fa-solid.fa-heart.add-to-favourite").on('click', (e) => {
                let bookId = e.currentTarget.dataset.bookId;

                // Call ajax and once it is succesful, check cart-count and add that number there
                $.ajax({
                    url: "{{ route('user.addfavourite') }}",
                    method: 'POST',
                    headers: {
                        Accept: "application/json",
                        Authorization: "{{ session('userToken') }}"
                    },
                    data: {
                        book_id: bookId
                    },
                    success: (res) => {
                        if (res.status === 'success') {
                            toast('success', res.message);
                            $("small.favourite-count").html(res.payload.favourite_count);
                            $("small.favourite-count").css('display', 'flex');
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
            })
        });
    </script>
@endpush
