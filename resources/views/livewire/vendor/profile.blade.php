<section class="kbs-listing-main-container">
    <section>
        <button class="review-button" wire:click="books">Books</button>
        <button class="review-button" wire:click="reviews">Reviews</button>
    </section>
    @if ($show_books)
        @if ($books->isEmpty())
            <section class="kbs-empty">
                <h3>
                    This vendor currently does not have any book yet...
                </h3>
            </section>
        @else
            <div class='kbs-book-listing-container'>
                @foreach ($books as $book)
                    <div class="kbs-book-card">
                        <div class='kbs-book-card-container'>
                            <img src="{{ route('get-image', ['route' => 'books', 'image' => $book->image]) }}"
                                alt="" class="book-image">
                            <div class='kbs-book-details'>
                                <a href="{{ route('vendor.book', ['id' => $book->book_id]) }}" class="book-title">
                                    <h4 class="book-title">{{ $book->book_name }}</h4>
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
                                @if ($book->discount_id === null)
                                    <h4>Price : ${{ $book->price }}</h4>
                                @else
                                    <h4>Price : <span class="original-price">{{ $book->price }}</span><span
                                            class="discounted-price"> {{ $book->discount_price }}</span></h4>
                                @endif

                                <small class="stock">Stock : {{ $book->stock }}</small><br>
                                @if (session('userSignedIn') && session('userRole') === 'user' && $book->stock !== 0)
                                    <button class='kbs-purchase'
                                        onclick="window.location.href = '{{ route('user.checkout', ['ids[]' => $book->book_id]) }}'">Purchase
                                        Now</button>
                                    <div class="d-flex justify-content-evenly align-items-center mt-3">
                                        <!-- Button trigger modal -->
                                        <a class="kbs-button" data-bs-toggle="modal"
                                            data-bs-target="#{{ $book->book_id }}">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="{{ $book->book_id }}" data-bs-backdrop="static"
                                            data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 id="staticBackdropLabel">Add to Cart</h1>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" id="{{ $book->book_id }}">
                                                        <div class='d-flex justify-content-evenly align-items-center'>
                                                            <img src="{{ route('get-image', ['route' => 'books', 'image' => $book->image]) }}"
                                                                alt="" class="book-image">
                                                            <div class="d-flex flex-column justify-content-around">
                                                                <h4>{{ $book->book_name }}</h4>
                                                                <p class='book-author'>By {{ $book->author_name }}</p>
                                                                @if ($book->discount_id === null)
                                                                    <h4>Price : ${{ $book->price }}</h4>
                                                                @else
                                                                    <h4>Price : <span
                                                                            class="original-price">{{ $book->price }}</span><span
                                                                            class="discounted-price">
                                                                            {{ $book->discount_price }}</span></h4>
                                                                @endif

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
                                                                        original-price='@if ($book->discount_id === null) {{ $book->price }} @else {{ $book->discount_price }} @endif'>
                                                                        @if ($book->discount_id === null)
                                                                            {{ $book->price }}
                                                                        @else
                                                                            {{ $book->discount_price }}
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
                                                            data-book-id='{{ $book->book_id }}' data-quantity='1'
                                                            data-route="{{ route('user.addcart') }}"
                                                            data-token="{{ session('userToken') }}">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-heart add-to-favourite"
                                            data-book-id="{{ $book->book_id }}"
                                            data-route="{{ route('user.addfavourite') }}"
                                            data-token="{{ session('userToken') }}"></i>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            <div>
                {{ $books->links() }}
            </div>
        @endif
    @elseif($show_reviews)
        @if ($reviews->isEmpty())
            <section class="kbs-empty">
                <h3>
                    This vendor does not have any reviews yet...
                </h3>
            </section>
        @else
            <section class="kbs-book-reviews">
                <h3>Reviews ({{ $reviews->count() }})</h3>
                <hr>
                <div class="kbs-book-review-card">
                    @foreach ($reviews as $review)
                        @if ($review->image === null)
                            <img src="{{ asset('image/default-user.jpg') }}" alt="">
                        @elseif(str_contains($review->image, 'http'))
                            <img src="{{ $review->image }}" alt="" />
                        @else
                            <img src="{{ route('get-image', ['route' => 'users', 'image' => $review->image]) }}"
                                alt="">
                        @endif
                        <div class="d-flex flex-column justify-content-between">
                            <div>
                                <h4>{{ $review->name }}</h4>
                                <p>{{ $review->review }}</p>
                            </div>
                            <div>
                                <small>
                                    @if ($review->updated_at !== null)
                                        {{ $review->updated_at }}
                                    @else
                                        {{ $review->created_at }}
                                    @endif
                                </small>
                            </div>
                        </div>
                        @if (!$loop->last)
                            <hr>
                        @endif
                    @endforeach


                </div>
            </section>
        @endif
    @endif
</section>
