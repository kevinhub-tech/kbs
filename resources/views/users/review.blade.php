@push('head')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush
@extends('main')
@section('main.content')
    <x-profile />
    <livewire:user.review />
@endsection
@push('scripts')
    <script></script>
@endpush
