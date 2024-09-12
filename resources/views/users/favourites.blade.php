@push('head')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-header">
        <h3> <i class="fa-solid fa-heart me-3"></i>My Favourite</h3>
        <div class="kbs-link-container">
            <h3><a href="{{ route('user.home') }}">Continue to Shopping <i class="fa-solid fa-arrow-right"></i></a></h3>
            @if ($favourited_books->isNotEmpty())
                <h3 class="kbs-remove-all" data-remove-type="favourite" data-route="{{ route('user.removefavourite') }}"
                    data-token="{{ session('userToken') }}">Remove All <i class="fa-solid fa-x"></i></h3>
            @endif
        </div>
    </section>
    @if ($favourited_books->isEmpty())
        <section class="kbs-favourite-empty">
            <h3>
                You currently don't have any favourite books yet...
            </h3>
        </section>
    @else
        <section class='kbs-favourite-listing-container'>
            @foreach ($favourited_books as $book)
                <div class="kbs-favourite-book-card" data-book-id="{{ $book->book_details->book_id }}">
                    <i class="fa-solid fa-x" data-book-id="{{ $book->book_details->book_id }}"
                        data-route="{{ route('user.removefavourite') }}" data-token="{{ session('userToken') }}"></i>
                    <div class='kbs-favourite-book-card-container'>
                        <img src="{{ route('get-image', ['image' => $book->book_details->image]) }}" alt=""
                            class="book-image">
                        <div class='kbs-book-details'>
                            <a href="#" class="book-title">
                                <h4>{{ $book->book_details->book_name }}</h4>
                            </a>
                            <div class="star-rating" bookid="something" rating='{{ $book->book_details->review }}'>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="rating">{{ $book->book_details->review }} stars</span>
                            </div>
                            <p class='book-author'>{{ $book->book_details->author_name }}</p>
                            @if ($book->book_details->discount === null)
                                <h4>Price : ${{ $book->book_details->price }}</h4>
                            @else
                                <h4>Price : <span class="original-price">{{ $book->book_details->price }}</span><span
                                        class="discounted-price"> {{ $book->book_details->discount_price }}</span></h4>
                            @endif
                            <small class="stock">Stock : {{ $book->book_details->stock }}</small><br>

                            <div class="d-flex justify-content-center align-items-center mt-3">
                                <!-- Button trigger modal -->
                                <button class='kbs-purchase'
                                    onclick="window.location.href = '{{ route('user.checkout', ['ids[]' => $book->book_id]) }}'">Purchase
                                    Now</button>
                                <a class="kbs-button ms-3 mt-3" data-bs-toggle="modal"
                                    data-bs-target="#{{ $book->book_details->book_id }}">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </a>

                                <!-- Modal -->
                                <div class="modal fade" id="{{ $book->book_details->book_id }}" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 id="staticBackdropLabel">Add to Cart</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" id="{{ $book->book_details->book_id }}">
                                                <div class='d-flex justify-content-evenly align-items-center'>
                                                    <img src="{{ route('get-image', ['image' => $book->book_details->image]) }}"
                                                        alt="" class="book-image">
                                                    <div class="d-flex flex-column justify-content-around">
                                                        <h4>{{ $book->book_details->book_name }}</h4>
                                                        <p class='book-author'>By {{ $book->book_details->author_name }}
                                                        </p>
                                                        @if ($book->book_details->discount === null)
                                                            <h4>Price : ${{ $book->book_details->price }}</h4>
                                                        @else
                                                            <h4>Price : <span
                                                                    class="original-price">{{ $book->book_details->price }}</span><span
                                                                    class="discounted-price">
                                                                    {{ $book->book_details->discount_price }}</span></h4>
                                                        @endif
                                                        <small class="stock">Stock :
                                                            <span>{{ $book->book_details->stock }}</span></small><br>
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
                                                                original-price='@if ($book->book_details->discount === null) {{ $book->book_details->price }} @else {{ $book->book_details->discount_price }} @endif'>
                                                                @if ($book->book_details->discount === null)
                                                                    {{ $book->book_details->price }}
                                                                @else
                                                                    {{ $book->book_details->discount_price }}
                                                                @endif
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer add-to-cart">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn kbs-btn"
                                                    data-book-id='{{ $book->book_details->book_id }}' data-quantity='1'
                                                    data-route="{{ route('user.addcart') }}"
                                                    data-token="{{ session('userToken') }}">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>
    @endif
@endsection
@push('scripts')
    <script>
        favouriteFunction();
    </script>
@endpush
