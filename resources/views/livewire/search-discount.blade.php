<section class="kbs-book-listing-main-container">
    <section class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
        <div class="kbs-search-wrapper mb-2 mb-sm-0" >
            <input wire:model.live="search" type="search" name="" id=""
                placeholder="Search Discount here....">
        </div>
        <div class="kbs-button-wrapper">
            <!-- Button trigger modal -->
            <button class="kbs-button" data-bs-toggle="modal" data-bs-target="#createDiscount">
                Create Discount
            </button>

            <!-- Modal -->
            <div class="modal fade" id="createDiscount" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 id="staticBackdropLabel">Create new discount</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="createDiscount">
                            <form name="create-discount">
                                <input type="number" name="discount_percentage" class="kbs-input"
                                    placeholder="Enter discount percentage">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="kbs-button" data-route="{{ route('vendor.discount-create') }}"
                                data-token="{{ session('userToken') }}">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            @if ($discounts->isNotEmpty())
                <button class="kbs-button" data-bs-toggle="modal" data-bs-target="#applyDiscount">
                    Apply Discount
                </button>

                <!-- Modal -->
                <div class="modal fade" id="applyDiscount" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 id="staticBackdropLabel">Apply discount on books</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="applyDiscount">
                                <form name="apply-discount">
                                    <label for="" class="mb-2">Select Discount:</label>
                                    <select class="kbs-select mb-2" name="discount_id" id="discount">
                                        @if ($discounts_dropdown->isEmpty())
                                            <option value="#" selected disabled>NA</option>
                                        @else
                                            @foreach ($discounts_dropdown as $discount)
                                                <option value="{{ $discount->discount_id }}">
                                                    {{ $discount->discount_percentage }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <label for="" class="mb-2">Select Books:</label>
                                    <select name="books[]" id="books" validate='true' multiple>
                                        @foreach ($books as $book)
                                            @if ($book->discount === null)
                                                <option value="{{ $book->book_id }}">{{ $book->book_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="kbs-button"
                                    data-route="{{ route('vendor.discount-apply') }}"
                                    data-token="{{ session('userToken') }}">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    @if ($discounts->isEmpty())
        <section class="kbs-vendor-empty">
            <h3>
                No discount found...
            </h3>
        </section>
    @else
        <section class="kbs-table-wrapper">
            <table class="table-container">
                <thead>
                    <tr>
                        <th>SI</th>
                        <th>Discount Percentages</th>
                        <th>Books Applied</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($discounts as $discount)
                        <tr>
                            <td data-title="SI" class="mob-display-heading">{{ $loop->iteration }}</td>
                            <td data-title="Discount percentages" class="mob-display-heading">{{ $discount->discount_percentage }}</td>
                            <td data-title="Books Applied" class="mob-display-heading">
                                @if ($discount->books->isEmpty())
                                    NA
                                @else
                                    <ul>
                                        @foreach ($discount->books as $book)
                                            <li>
                                                <a
                                                    href="{{ route('vendor.bookdesc', ['id' => $book->book_id]) }}">{{ $book->book_name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td data-title="Created At" class="mob-display-heading">
                                @if ($discount->created_at === null)
                                    NA
                                @else
                                    {{ date('d-m-Y', strtotime($discount->created_at)) }}
                                @endif
                            </td>
                            <td data-title="Updated At" class="mob-display-heading">
                                @if ($discount->updated_at === null)
                                    NA
                                @else
                                    {{ date('d-m-Y', strtotime($discount->updated_at)) }}
                                @endif
                            </td>
                            <td>
                                <div class="action-wrapper">

                                    <button data-bs-toggle="modal" data-bs-target="#{{ $discount->discount_id }}"
                                        data-toggle="tooltip" data-placement="top" title="Edit Discount"><i
                                            class="fa-solid fa-pen-to-square"></i></button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="{{ $discount->discount_id }}" data-bs-backdrop="static"
                                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 id="staticBackdropLabel">Edit discount</h1>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" id="{{ $discount->discount_id }}">
                                                    <form name="edit-discount"
                                                        data-discount-id="{{ $discount->discount_id }}">
                                                        <input type="hidden" class="kbs-input" name="discount_id"
                                                            value="{{ $discount->discount_id }}">
                                                        <input type="number" class="kbs-input"
                                                            name="discount_percentage"
                                                            value="{{ $discount->discount_percentage }}">
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="kbs-button"
                                                        data-route="{{ route('vendor.discount-edit') }}"
                                                        data-token="{{ session('userToken') }}"
                                                        data-discount-id="{{ $discount->discount_id }}">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($discount->books->isNotEmpty())
                                        <button data-bs-toggle="modal" data-bs-target="#removeDiscount"
                                            data-toggle="tooltip" data-placement="top"
                                            title="Remove Discounts from Books"><i
                                                class="fa-solid fa-tag"></i></button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="removeDiscount" data-bs-backdrop="static"
                                            data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 id="staticBackdropLabel">Remove discount from books</h3>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" id="removeDiscount">
                                                        @foreach ($discount->books as $book)
                                                            <div class="d-flex justify-content-between align-items-center mb-3"
                                                                data-book-id={{ $book->book_id }}
                                                                data-remove-book-id={{ $book->book_id }}>
                                                                <h4>{{ $book->book_name }}</h4>
                                                                <i class="fa-solid fa-x"
                                                                    data-book-id="{{ $book->book_id }}"
                                                                    data-route="{{ route('vendor.discount-remove') }}"
                                                                    data-token="{{ session('userToken') }}"
                                                                    data-discount-id={{ $discount->discount_id }}></i>
                                                            </div>
                                                        @endforeach

                                                        <button type="button" class="kbs-remove-all-button"
                                                            data-route="{{ route('vendor.discount-remove') }}"
                                                            data-token="{{ session('userToken') }}"
                                                            data-discount-id="{{ $discount->discount_id }}">Remove
                                                            All</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <button data-toggle="tooltip" data-placement="top" title="Delete Discount"><i
                                            class="fa-solid fa-trash"
                                            data-route="{{ route('vendor.discount-delete') }}"
                                            data-discount-id="{{ $discount->discount_id }}"
                                            data-token="{{ session('userToken') }}"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $discounts->links() }}
        </section>
    @endif
</section>
