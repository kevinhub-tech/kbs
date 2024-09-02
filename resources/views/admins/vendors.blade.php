@extends('main')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush
@section('main.content')
    <section class="kbs-admin-header">
        <h1>
            Manage Vendors
        </h1>
    </section>
    <livewire:admin.vendors />
@endsection
@push('scripts')
@endpush
