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
    <livewire:orders />
@endsection
@push('scripts')
    <script>
        $(document).ready(() => {
            $('button[name="order-status-updater"]').on('click', function(e) {
                let status = $(this).data('status');
            })
        })
    </script>
@endpush
