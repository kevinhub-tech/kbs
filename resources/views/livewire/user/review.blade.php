<section class="kbs-listing-main-container">
    <section>
        <button class="review-button" wire:click="book_review">Book Reviews</button>
        <button class="review-button" wire:click="vendor_review">Vendor Reviews</button>
    </section>
    @if ($show_book_review)
        @if ($book_reviews->isEmpty())
            <section class="kbs-empty">
                <h3>
                    You have not ordered any book yet...
                </h3>
            </section>
        @else
            <section class="kbs-table-wrapper">
                <table class="table-container">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Book Image</th>
                            <th>Book Name</th>
                            <th>Star Rating</th>
                            <th>Review</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($book_reviews as $review)
                            <tr>
                                <td data-title="SI">{{ $loop->iteration }}</td>
                                <td data-title="Book Image"><img
                                        src="{{ route('get-image', ['route' => 'books', 'image' => $review->image]) }}"
                                        alt="">
                                </td>
                                <td data-title="Book Name" class="mob-display-heading">
                                    {{ $review->book_name }}
                                </td>
                                <td data-title="Star Rating" class="mob-display-heading">
                                    @if ($review->rating === null)
                                        N/A
                                    @else
                                        <div class="star-rating" rating='{{ $review->rating }}'>
                                            <span
                                                class="fa fa-star @if ($review->rating === 1 || $review->rating > 1) checked @endif"></span>
                                            <span
                                                class="fa fa-star @if ($review->rating === 2 || $review->rating > 2) checked @endif"></span>
                                            <span
                                                class="fa fa-star @if ($review->rating === 3 || $review->rating > 3) checked @endif"></span>
                                            <span
                                                class="fa fa-star @if ($review->rating === 4 || $review->rating > 4) checked @endif"></span>
                                            <span
                                                class="fa fa-star @if ($review->rating === 5) checked @endif"></span>
                                            <span class="rating">{{ $review->rating }} stars</span>
                                        </div>
                                    @endif

                                </td>
                                <td data-title="Review" class="mob-display-heading">
                                    @if ($review->review)
                                        {{ $review->review }}
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td data-title="Created At" class="mob-display-heading">
                                    @if ($review->review_created_at === null)
                                        N/A
                                    @else
                                        {{ date('d-m-Y', strtotime($review->created_at)) }}
                                    @endif
                                </td>

                                <td data-title="Updated At" class="mob-display-heading">
                                    @if ($review->review_updated_at === null)
                                        N/A
                                    @else
                                        {{ date('d-m-Y', strtotime($review->updated_at)) }}
                                    @endif
                                </td>

                                <td>
                                    <div class="action-wrapper">
                                        @if ($review->review)
                                            <i class="fa-solid fa-pen" data-toggle="tooltip" data-placement="top"
                                                title="Edit Review" data-bs-toggle="modal"
                                                data-bs-target="#{{ $review->book_id }}"></i>

                                            <div class="modal fade" id="{{ $review->book_id }}"
                                                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h3 id="staticBackdropLabel">Update review of
                                                                {{ $review->book_name }}</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" id="{{ $review->book_id }}">
                                                            <h3>{{ $review->rating }} @if ($review->rating === 1)
                                                                    Star
                                                                @else
                                                                    Stars
                                                                @endif
                                                            </h3>
                                                            <form action="" name="{{ $review->book_id }}">
                                                                <div class="star-widget">

                                                                    <input type="radio" name="rating" id="rate-5"
                                                                        value="5"
                                                                        @if ($review->rating === 5) checked @endif>
                                                                    <label for="rate-5"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating" id="rate-4"
                                                                        value="4"
                                                                        @if ($review->rating === 4) checked @endif>
                                                                    <label for="rate-4"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating" id="rate-3"
                                                                        value="3"
                                                                        @if ($review->rating === 3) checked @endif>
                                                                    <label for="rate-3"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating" id="rate-2"
                                                                        value="2"
                                                                        @if ($review->rating === 2) checked @endif>
                                                                    <label for="rate-2"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating" id="rate-1"
                                                                        value="1"
                                                                        @if ($review->rating === 1) checked @endif>
                                                                    <label for="rate-1"><i
                                                                            class="fa-solid fa-star"></i></label>

                                                                </div>
                                                                <textarea id="" cols="30" rows="10" placeholder="Please describe your review on this book"
                                                                    name="review" class="mt-3">{{ $review->review }}</textarea>
                                                            </form>

                                                        </div>
                                                        <div class="modal-footer review">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="button" class="btn kbs-btn"
                                                                data-id="{{ $review->book_id }}"
                                                                data-route = "{{ route('user.updatereview') }}"
                                                                data-token="{{ session('userToken') }}"
                                                                data-review-type="books">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <i class="fa-solid fa-plus" data-toggle="tooltip" data-placement="top"
                                                title="Add Review" data-bs-toggle="modal"
                                                data-bs-target="#{{ $review->book_id }}"></i>
                                            <div class="modal fade" id="{{ $review->book_id }}"
                                                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h3 id="staticBackdropLabel">Give review for
                                                                {{ $review->book_name }}</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" id="{{ $review->book_id }}">
                                                            <h3>Give Star Ratings</h3>
                                                            <form action="" name="{{ $review->book_id }}">
                                                                <div class="star-widget">

                                                                    <input type="radio" name="rating"
                                                                        id="rate-5" value="5">
                                                                    <label for="rate-5"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating"
                                                                        id="rate-4" value="4">
                                                                    <label for="rate-4"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating"
                                                                        id="rate-3" value="3">
                                                                    <label for="rate-3"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating"
                                                                        id="rate-2" value="2">
                                                                    <label for="rate-2"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating"
                                                                        id="rate-1" value="1">
                                                                    <label for="rate-1"><i
                                                                            class="fa-solid fa-star"></i></label>

                                                                </div>
                                                                <textarea id="" cols="30" rows="10" placeholder="Please describe your review on this book"
                                                                    class="mt-3" name="review"></textarea>

                                                            </form>

                                                        </div>
                                                        <div class="modal-footer review">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="button" class="btn kbs-btn"
                                                                data-route = "{{ route('user.addreview') }}"
                                                                data-token="{{ session('userToken') }}"
                                                                data-id="{{ $review->book_id }}"
                                                                data-review-type="books">Add</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif



                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @endif
    @elseif($show_vendor_review)
        @if ($book_reviews->isEmpty())
            <section class="kbs-empty">
                <h3>
                    You have not ordered any book yet...
                </h3>
            </section>
        @else
            <section class="kbs-table-wrapper">
                <table class="table-container">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Vendor Name</th>
                            <th>Star Rating</th>
                            <th>Review</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendor_reviews as $review)
                            <tr>
                                <td data-title="SI">{{ $loop->iteration }}</td>
                                <td data-title="Vendor Name" class="mob-display-heading">
                                    {{ $review->vendor_name }}
                                </td>
                                <td data-title="Star Rating" class="mob-display-heading">
                                    @if ($review->rating === null)
                                        N/A
                                    @else
                                        <div class="star-rating" rating='{{ $review->rating }}'>
                                            <span
                                                class="fa fa-star @if ($review->rating === 1 || $review->rating > 1) checked @endif"></span>
                                            <span
                                                class="fa fa-star @if ($review->rating === 2 || $review->rating > 2) checked @endif"></span>
                                            <span
                                                class="fa fa-star @if ($review->rating === 3 || $review->rating > 3) checked @endif"></span>
                                            <span
                                                class="fa fa-star @if ($review->rating === 4 || $review->rating > 4) checked @endif"></span>
                                            <span
                                                class="fa fa-star @if ($review->rating === 5) checked @endif"></span>
                                            <span class="rating">{{ $review->rating }} stars</span>
                                        </div>
                                    @endif

                                </td>
                                <td data-title="Review" class="mob-display-heading">
                                    @if ($review->review)
                                        {{ $review->review }}
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td data-title="Created At" class="mob-display-heading">
                                    @if ($review->review_created_at === null)
                                        N/A
                                    @else
                                        {{ date('d-m-Y', strtotime($review->created_at)) }}
                                    @endif
                                </td>

                                <td data-title="Updated At" class="mob-display-heading">
                                    @if ($review->review_updated_at === null)
                                        N/A
                                    @else
                                        {{ date('d-m-Y', strtotime($review->updated_at)) }}
                                    @endif
                                </td>

                                <td>
                                    <div class="action-wrapper">
                                        @if ($review->review)
                                            <i class="fa-solid fa-pen" data-toggle="tooltip" data-placement="top"
                                                title="Edit Review" data-bs-toggle="modal"
                                                data-bs-target="#{{ $review->vendor_id }}"></i>

                                            <div class="modal fade" id="{{ $review->vendor_id }}"
                                                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h3 id="staticBackdropLabel">Update review of
                                                                {{ $review->vendor_name }}
                                                                </h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" id="{{ $review->vendor_id }}">
                                                            <h3>{{ $review->rating }} @if ($review->rating === 1)
                                                                    Star
                                                                @else
                                                                    Stars
                                                                @endif
                                                            </h3>
                                                            <form action="" name="{{ $review->vendor_id }}">
                                                                <div class="star-widget">

                                                                    <input type="radio" name="rating"
                                                                        id="rate-5" value="5"
                                                                        @if ($review->rating === 5) checked @endif>
                                                                    <label for="rate-5"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating"
                                                                        id="rate-4" value="4"
                                                                        @if ($review->rating === 4) checked @endif>
                                                                    <label for="rate-4"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating"
                                                                        id="rate-3" value="3"
                                                                        @if ($review->rating === 3) checked @endif>
                                                                    <label for="rate-3"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating"
                                                                        id="rate-2" value="2"
                                                                        @if ($review->rating === 2) checked @endif>
                                                                    <label for="rate-2"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating"
                                                                        id="rate-1" value="1"
                                                                        @if ($review->rating === 1) checked @endif>
                                                                    <label for="rate-1"><i
                                                                            class="fa-solid fa-star"></i></label>

                                                                </div>
                                                                <textarea id="" cols="30" rows="10" placeholder="Please describe your review on this vendor"
                                                                    name="review" class="mt-3">{{ $review->review }}</textarea>
                                                            </form>

                                                        </div>
                                                        <div class="modal-footer review">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="button" class="btn kbs-btn"
                                                                data-id="{{ $review->vendor_id }}"
                                                                data-route = "{{ route('user.updatereview') }}"
                                                                data-token="{{ session('userToken') }}"
                                                                data-review-type="vendors">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <i class="fa-solid fa-plus" data-toggle="tooltip" data-placement="top"
                                                title="Add Review" data-bs-toggle="modal"
                                                data-bs-target="#{{ $review->vendor_id }}"></i>
                                            <div class="modal fade" id="{{ $review->vendor_id }}"
                                                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h3 id="staticBackdropLabel">Give review for
                                                                {{ $review->vendor_name }}</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" id="{{ $review->vendor_id }}">
                                                            <h3>Give Star Ratings</h3>
                                                            <form action="" name="{{ $review->vendor_id }}">
                                                                <div class="star-widget">

                                                                    <input type="radio" name="rating"
                                                                        id="rate-5" value="5">
                                                                    <label for="rate-5"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating"
                                                                        id="rate-4" value="4">
                                                                    <label for="rate-4"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating"
                                                                        id="rate-3" value="3">
                                                                    <label for="rate-3"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating"
                                                                        id="rate-2" value="2">
                                                                    <label for="rate-2"><i
                                                                            class="fa-solid fa-star"></i></label>
                                                                    <input type="radio" name="rating"
                                                                        id="rate-1" value="1">
                                                                    <label for="rate-1"><i
                                                                            class="fa-solid fa-star"></i></label>

                                                                </div>
                                                                <textarea id="" cols="30" rows="10" placeholder="Please describe your review on this vendor"
                                                                    name="review" class="mt-3"></textarea>

                                                            </form>

                                                        </div>
                                                        <div class="modal-footer review">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="button" class="btn kbs-btn"
                                                                data-route = "{{ route('user.addreview') }}"
                                                                data-token="{{ session('userToken') }}"
                                                                data-id="{{ $review->vendor_id }}"
                                                                data-review-type="vendors">Add</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @endif
    @endif
</section>
