<section class="kbs-listing-main-container">
    <section
        class="d-flex justify-content-between align-items-center d-flex justify-content-between align-items-center flex-column flex-sm-row">
        <div class="kbs-search-wrapper mb-2 mb-sm-0">
            <input wire:model.live="search" type="search" name="" id="" placeholder="Search Book here....">
        </div>
        <div class="kbs-button-wrapper">
            <!-- Button trigger modal -->
            <a href="{{ route('vendor.book') }}">Create Book</a>
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
                <thead>
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
                </thead>
                <tbody>
                    @foreach ($books as $book)
                        <tr>
                            <td data-title="SI">{{ $loop->iteration }}</td>
                            <td data-title="Image"><img
                                    src="{{ route('get-image', ['route' => 'books', 'image' => $book->image]) }}"
                                    alt=""></td>
                            <td data-title="Book Name" class="mob-display-heading">{{ $book->book_name }}</td>
                            <td data-title="Author Name" class="mob-display-heading">{{ $book->author_name }}</td>
                            <td data-title="Stock" class="mob-display-heading">{{ $book->stock }}</td>
                            <td data-title="Price" class="mob-display-heading">{{ $book->price }}</td>
                            <td data-title="Delivery Fee" class="mob-display-heading">{{ $book->delivery_fee }}</td>
                            <td data-title="Discount" class="mob-display-heading">
                                @if ($book->discount === null)
                                    NA
                                @else
                                    {{ $book->discount->discount_percentage }}%
                                @endif
                            </td>
                            <td data-title="Created At" class="mob-display-heading">
                                @if ($book->created_at === null)
                                    NA
                                @else
                                    {{ date('d-m-Y', strtotime($book->created_at)) }}
                                @endif
                            </td>
                            <td data-title="Updated At" class="mob-display-heading">
                                @if ($book->updated_at === null)
                                    NA
                                @else
                                    {{ date('d-m-Y', strtotime($book->updated_at)) }}
                                @endif
                            </td>
                            <td>
                                <div class="action-wrapper">
                                    <a href="{{ route('vendor.bookdesc', ['id' => $book->book_id]) }}"
                                        data-toggle="tooltip" data-placement="top" title="View Book Description"><i
                                            class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('vendor.book-edit', ['id' => $book->book_id]) }}"
                                        data-toggle="tooltip" data-placement="top" title="Edit Book"><i
                                            class="fa-solid fa-pen-to-square"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $books->links() }}
        </section>
    @endif
</section>
