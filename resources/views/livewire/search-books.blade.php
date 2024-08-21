<section class="kbs-book-listing-main-container">
    <section class="d-flex justify-content-between align-items-center">
        <div class="kbs-search-wrapper">
            <input wire:model.live="search" type="search" name="" id="" placeholder="Search Book here....">
        </div>
        <div class="kbs-button-wrapper">
            <button>Create Discount</button>
            <button>Apply Discount</button>
            <a href="">Create Book</a>
        </div>
    </section>
    @if($books->isEmpty())
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
            @foreach($books as $book)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td><img src="{{ route('get-image', ['image' => $book->image]) }}"
                    alt=""></td>
                <td>{{$book->book_name}}</td>
                <td>{{$book->author_name}}</td>
                <td>{{$book->stock}}</td>
                <td>{{$book->price}}</td>
                <td>{{$book->delivery_fee}}</td>
                <td>@if($book->discount === null) NA @else {{$book->discount->discount_percentage}}@endif</td>
                <td>@if($book->created_at === null) NA @else  {{date('d-m-Y', strtotime($book->created_at));}} @endif</td>
                <td>@if($book->updated_at === null) NA @else  {{date('d-m-Y', strtotime($book->updated_at));}} @endif</td>
                <td>
                    <div class="action-wrapper">
                        <a href="{{route('vendor.bookdesc', ['id'=> $book->book_id])}}" data-toggle="tooltip" data-placement="top" title="View Book Description"><i class="fa-solid fa-eye"></i></a>
                        <a href="{{route('vendor.book-edit', ['id'=> $book->book_id])}}" data-toggle="tooltip" data-placement="top" title="Edit Book"><i class="fa-solid fa-pen-to-square"></i></a>
                    </div>
                </td>
            </tr>
            @endforeach
        </table>
        {{$books->links()}}
    </section>
    @endif
    
</section>
