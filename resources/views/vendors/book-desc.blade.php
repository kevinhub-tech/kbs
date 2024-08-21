@extends('main')
@section('main.content')
    <x-book-desc :book='$book' :vendor_info='$vendor_info'></x-book-desc>
@endsection
