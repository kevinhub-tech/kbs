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
                    <li><a href="#" data-route="{{route('user.home')}}" @if(Request::get('category') === $category->category_id) class="active" @endif data-category-id="{{$category->category_id}}">{{ $category->category }} ({{ $category->count }})</a></li>
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
                                    <a href="{{ route('user.book', ['id' => $book->book_id]) }}" class="book-title">
                                        <h4>{{ $book->book_name }}</h4>
                                    </a>
                                    <div class="star-rating" rating='{{ $book->review }}'>
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
                                        <a class="kbs-button" data-bs-toggle="modal"
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
                                                            data-book-id='{{ $book->book_id }}' data-quantity='1'
                                                            data-route="{{ route('user.addcart') }}"
                                                            data-token="{{ session('userToken') }}">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-heart add-to-favourite" data-book-id="{{ $book->book_id }}"
                                            data-route="{{ route('user.addfavourite') }}"
                                            data-token="{{ session('userToken') }}"></i>
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
        homeFunction();
    </script>
@endpush
