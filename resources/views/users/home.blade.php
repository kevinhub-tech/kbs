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
        <section class="kbs-book-listing">
            <div class='kbs-book-listing-container'>
                @foreach ($books as $book)
                    <div class="kbs-book-card">
                        <div class='kbs-book-card-container'>
                            <img src="{{ route('get-image', ['image' => $book->image]) }}" alt="" class="book-image">
                            <div class='kbs-book-details'>
                                <a href="#" class="book-title">
                                    <h4>{{ $book->book_name }}</h4>
                                </a>
                                <div class="star-rating" bookid="something" rating='{{ $book->review }}'>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
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
                                    <a type="button" class="kbs-buttom" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                    </a>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    ...
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-heart"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach


            </div>
            <div>
                Pagination section
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
            }
        }
        $(document).ready(function() {
            $(window).resize(function() {
                setBookListingHeightDynamically();
            });
            setBookListingHeightDynamically();

            // $("div.star-rating").each(function(index, div) {
            //     let div_wda = $(div);
            //     let rating = 3;
            //     for (i = 1; i <= rating; i++) {
            //         div_wda.children().each(function(index, span) {
            //             if (index + 1 === i) {
            //                 return;
            //             }
            //                 console.log(index);
            //                 console.log(span);

            //         });
            //     }

            // })
            // console.log($("div.star-rating").attr('bookid'));

        });
    </script>
@endpush
