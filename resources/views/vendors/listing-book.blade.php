@extends('main')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
@endpush
@section('main.content')
    <section class="kbs-vendor-header">
        <h1>
            Manage Books
        </h1>
    </section>
    <livewire:search-books />
@endsection
@push('scripts')
    <script>
            new MultiSelectTag('books', {
            rounded: true, // default true
            shadow: true, // default false
            placeholder: 'Search', // default Search...
            tagColor: {
                textColor: '#005D6C',
                borderColor: '#005D6C',
                bgColor: '#eaffe6',
            }
        });
    </script>
@endpush