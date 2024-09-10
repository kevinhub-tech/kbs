@props(['book', 'vendor_info'])
<section class="kbs-book-desc-container">
    <div class="kbs-book-desc-image">
        <img src="{{ route('get-image', ['image' => $book->image]) }}" alt="">
    </div>
    <div class="kbs-book-desc">
        <h3 class="book-title">{{ $book->book_name }}</h3>
        <div class='d-flex align-items-center my-2'>
            <p class="author me-3">By {{ $book->author_name }}</p>
            <div class="star-rating" rating='{{ $book->review }}'>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="rating">{{ $book->review }} stars</span>
            </div>
        </div>
        <div class="category-wrapper">
            @foreach ($book->categories as $category)
                <h5>{{ $category->category }}</h5>
            @endforeach
        </div>
        @if ($book->discount === null)
            <h4 class="book-price">Price : ${{ $book->price }}<span class="stock ms-2">Stock:
                    {{ $book->stock }}</span>
            </h4>
        @else
            <h4 class="book-price">Price : <span class="original-price">{{ $book->price }}</span><span
                    class="discounted-price">
                    {{ $book->discount_price }}</span> <span class="stock ms-2">Stock: {{ $book->stock }}</span></h4>
        @endif
        <p class="book-desc">
            {{ $book->book_desc }}
        </p>
        <div class="seller-info">
            <h4 class="seller">Sold by - </h4> <a href="{{ $vendor_info->vendor_id }}" class="seller-link">
                {{ $vendor_info->vendor_name }}</a>
        </div>
        <div class="kbs-book-desc-button-wrapper">
            @if (session('userSignedIn') && session('userRole') === 'user')
                <button class='kbs-purchase'>
                    Purchase Now
                </button>

                <!-- Button trigger modal -->
                <a class="kbs-button" data-bs-toggle="modal" data-bs-target="#{{ $book->book_id }}">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>

                <!-- Modal -->
                <div class="modal fade" id="{{ $book->book_id }}" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 id="staticBackdropLabel">Add to Cart</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="{{ $book->book_id }}">
                                <div class='d-flex justify-content-evenly align-items-center'>
                                    <img src="{{ route('get-image', ['image' => $book->image]) }}" alt=""
                                        class="book-image">
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
                            <div class="modal-footer add-to-cart">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn kbs-btn" data-book-id='{{ $book->book_id }}'
                                    data-quantity='1' data-route="{{ route('user.addcart') }}"
                                    data-token="{{ session('userToken') }}">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
                <i class="fa-solid fa-heart add-to-favourite" data-book-id="{{ $book->book_id }}"
                    data-route="{{ route('user.addfavourite') }}" data-token="{{ session('userToken') }}"></i>
            @elseif (session('userSignedIn') && session('userRole') === 'vendor')
                <a href="{{ route('vendor.book-edit', ['id' => $book->book_id]) }}" class='kbs-edit'>
                    Edit Book
                </a>
            @endif
        </div>
    </div>
</section>
