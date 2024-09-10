<section class="kbs-listing-main-container">
    <section>
        <button wire:click="book_review">Book Reviews</button>
        <button wire:click="vendor_review">Vendor Reviews</button>
    </section>
    @if ($show_book_review)
        @if ($book_reviews->isEmpty())
            <section class="kbs-vendor-empty">
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
                                        src="{{ route('get-image', ['image' => $review->image]) }}" alt="">
                                </td>
                                <td data-title="Book Name" class="mob-display-heading">
                                    {{ $review->book_name }}
                                </td>
                                <td data-title="Star Rating" class="mob-display-heading">
                                    @if ($review->rating === null)
                                        N/A
                                    @else
                                        <div class="star-rating" rating='{{ $review->rating }}'>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
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
                                        <i class="fa-solid fa-plus" data-toggle="tooltip" data-placement="top"
                                            title="View Order Detail"></i>
                                        <i class="fa-solid fa-pen" data-toggle="tooltip" data-placement="top"
                                            title="View Order Detail"></i>
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
            <section class="kbs-vendor-empty">
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
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
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
                                        <i class="fa-solid fa-plus" data-toggle="tooltip" data-placement="top"
                                            title="View Order Detail"></i>
                                        <i class="fa-solid fa-pen" data-toggle="tooltip" data-placement="top"
                                            title="View Order Detail"></i>
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
