@extends('main')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
@endpush
@section('main.content')
    @php
        if (Request::url() === route('vendor.book')) {
            $form_method = 'POST';
            $form_action = route('vendor.book-post');
            $heading = 'Add new Book';
        } else {
            $form_method = 'PUT';
            $form_action = route('vendor.book-update');
            $heading = 'Edit Book';
        }
    @endphp
    <form method="#" action="#" enctype="multipart/form-data" name="book-form" class="book-form">
        @csrf
        <h4>{{ $heading }}</h4>
        @if ($form_method === 'PUT')
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name='book_id' value="{{ $book->book_id }}">
        @endif
        <label for="book_name">Book Name:</label>
        <input type="text" name='book_name' @if ($form_method === 'PUT') value="{{ $book->book_name }}" @endif
            validate='true'>
        <label for="book_desc">Book Description:</label>
        <textarea name="book_desc" id="book_desc" cols="20" rows="10" validate='true'></textarea>
        <label for="author_name">Author Name:</label>
        <input type="text" name="author_name" @if ($form_method === 'PUT') value="{{ $book->author_name }}" @endif
            validate='true'>
        <label for="stock">Stock:</label>
        <input type="number" name="stock" @if ($form_method === 'PUT') value="{{ $book->stock }}" @endif
            validate='true'>
        <label for="price">Price:</label>
        <input type="number" name="price" @if ($form_method === 'PUT') value="{{ $book->price }}" @endif
            validate='true' step="any">
        <label for="delivery_fee">Delivery Fee:</label>
        <input type="number" name="delivery_fee" @if ($form_method === 'PUT') value="{{ $book->delivery_fee }}" @endif
            validate='true' step="any">
        <label for="categories">Category:</label>
        <select name="categories[]" id="categories" validate='true' multiple>
            @foreach ($categories as $category)
                <option value="{{ $category->category_id }}"
                    @if ($form_method === 'PUT') @foreach ($book_categories as $book_category) @if ($book_category->category_id === $category->category_id) selected @endif
                    @endforeach
            @endif>
            {{ $category->category }}</option>
            @endforeach
        </select>
        <div class="mt-3 mb-3">
            <label for="formFile" class="form-label">Book Cover:</label>
            <input class="form-control" type="file" id="formFile" name="image"
                @if ($form_method !== 'PUT') validate='true' @endif>
        </div>
        <div class="button-container">
            <button type="submit">
                @if ($form_method === 'PUT')
                    Update
                @else
                    Add
                @endif
            </button>
            <button type="reset">Reset</button>
        </div>

    </form>
@endsection
@push('scripts')
    <script>
        @if ($form_method === 'PUT')
            $('textarea#book_desc').val("{{ $book->book_desc }}");
        @endif

        new MultiSelectTag('categories', {
            rounded: true, // default true
            shadow: true, // default false
            placeholder: 'Search', // default Search...
            tagColor: {
                textColor: '#005D6C',
                borderColor: '#005D6C',
                bgColor: '#eaffe6',
            }
        })
        $("form[name='book-form']").submit(function(e) {
            e.preventDefault();
            if (formValidate()) {
                let formData = new FormData(this);
                $.ajax({
                    url: '{{ $form_action }}',
                    method: 'POST',
                    headers: {
                        Accept: "application/json",
                        Authorization: "{{ session('userToken') }}"
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (res) => {
                        if (res.status === 'success') {
                            toast('success', res.message);
                            setTimeout(() => {
                                window.location.href = "{{ route('vendor.book-listing') }}";
                            }, 1500);

                        }
                    },
                    error: (jqXHR, exception) => {
                        var errorMessage = "";

                        if (jqXHR.status === 0) {
                            errorMessage =
                                "Not connect.\n Verify Network.";
                        } else if (jqXHR.status == 404) {
                            errorMessage =
                                "Requested page not found. [404]";
                        } else if (jqXHR.status == 500) {
                            errorMessage =
                                "Internal Server Error [500].";
                        } else if (exception === "parsererror") {
                            errorMessage =
                                "Requested JSON parse failed.";
                        } else if (exception === "timeout") {
                            errorMessage = "Time out error.";
                        } else if (exception === "abort") {
                            errorMessage = "Ajax request aborted.";
                        } else {
                            let html = ''
                            Object.values(jqXHR.responseJSON.errors).forEach((err) => {
                                err.forEach((e) => {
                                    html += `${e} <hr />`;
                                });
                            });
                            Swal.fire({
                                title: 'Error!',
                                html: html,
                                icon: 'error',
                                animation: true,
                                showConfirmButton: true,
                            })
                            return;
                        }
                        toast("error", errorMessage);
                    }
                })
            }
        })
    </script>
@endpush
