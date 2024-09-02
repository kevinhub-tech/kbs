@extends('main')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
@endpush
@section('main.content')
    <section class="kbs-vendor-header">
        <h1>
            Manage Discounts
        </h1>
    </section>
    <livewire:search-discount />
@endsection
@push('scripts')
    <script>
        discountFunction();
    </script>
@endpush
