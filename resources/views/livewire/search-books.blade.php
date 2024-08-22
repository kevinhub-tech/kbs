<section class="kbs-book-listing-main-container">
    <section class="d-flex justify-content-between align-items-center">
        <div class="kbs-search-wrapper">
            <input wire:model.live="search" type="search" name="" id="" placeholder="Search Book here....">
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
                            <h3 id="staticBackdropLabel">Create new discount</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="createDiscount">
                            <input type="number" class="kbs-input" placeholder="Enter discount percentage">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="kbs-button">Add</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <button class="kbs-button" data-bs-toggle="modal" data-bs-target="#applyDiscount">
                Apply Discount
            </button>

            <!-- Modal -->
            <div class="modal fade" id="applyDiscount" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 id="staticBackdropLabel">Apply discount on books</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="applyDiscount">
                            @if($discounts->isEmpty())
                            <h3>Oops... You don't have any discount created yet! Please kindly create discount first before applying it.</h3>
                            @else
                            <label for="" class="mb-2">Select Discount:</label>
                            <select class="kbs-select" name="discount" id="discount" >
                                <option value="#" selected disabled>NA</option>
                                @foreach ($discounts as $discount)
                                    <option value="{{ $discount->discount_id }}">{{ $discount->discount_percentage }}</option>
                                @endforeach
                            </select>
                            <label for="" class="mb-2">Select Books:</label>
                            <select name="books[]" id="books" validate='true' multiple>
                                @foreach ($books as $book)
                                    @if($book->discount === null)
                                    <option value="{{ $book->book_id }}">{{ $book->book_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @endif
                           
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            @if($discounts->isNotEmpty())
                            <button type="button" class="kbs-button">Apply</button>
                            @endif
                           
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{route('vendor.book')}}">Create Book</a>
        </div>
    </section>
    @if ($books->isEmpty())
        <section class="kbs-vendor-empty">
            <h3>
                No book found...
            </h3>
        </section>
    @else
        <section class="kbs-table-wrapper">
            <table class="table-container">
                <tr>
                    <th>SI</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Author</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Delivery Fee</th>
                    <th>Discount</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
                @foreach ($books as $book)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><img src="{{ route('get-image', ['image' => $book->image]) }}" alt=""></td>
                        <td>{{ $book->book_name }}</td>
                        <td>{{ $book->author_name }}</td>
                        <td>{{ $book->stock }}</td>
                        <td>{{ $book->price }}</td>
                        <td>{{ $book->delivery_fee }}</td>
                        <td>
                            @if ($book->discount === null)
                                NA
                            @else
                                {{ $book->discount->discount_percentage }}
                            @endif
                        </td>
                        <td>
                            @if ($book->created_at === null)
                                NA
                            @else
                                {{ date('d-m-Y', strtotime($book->created_at)) }}
                            @endif
                        </td>
                        <td>
                            @if ($book->updated_at === null)
                                NA
                            @else
                                {{ date('d-m-Y', strtotime($book->updated_at)) }}
                            @endif
                        </td>
                        <td>
                            <div class="action-wrapper">
                                <a href="{{ route('vendor.bookdesc', ['id' => $book->book_id]) }}" data-toggle="tooltip"
                                    data-placement="top" title="View Book Description"><i
                                        class="fa-solid fa-eye"></i></a>
                                <a href="{{ route('vendor.book-edit', ['id' => $book->book_id]) }}"
                                    data-toggle="tooltip" data-placement="top" title="Edit Book"><i
                                        class="fa-solid fa-pen-to-square"></i></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $books->links() }}
        </section>
    @endif
</section>
