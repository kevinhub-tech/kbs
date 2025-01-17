@extends('main')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
@endpush
@section('main.content')
    <section class="kbs-vendor-header">
        <h1>
            Manage Orders
        </h1>
    </section>
    <livewire:orders />
@endsection
@push('scripts')
    <script>
        orderListingFunctiion();
    </script>
@endpush
