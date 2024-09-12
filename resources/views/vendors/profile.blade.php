@push('head')
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-vendor-profile-banner">
        @if ($vendor->image === null)
            <img src="{{ asset('image/default-user.jpg') }}" alt="">
        @else
            <img src="{{ route('get-user-image', ['image' => $vendor->image]) }}" alt="">
        @endif

        <div class="d-flex flex-column justify-content-around ">
            <h3>{{ $vendor->vendor_name }}</h3>
            <h3>{{ $vendor->email }}</h3>
            <h3>{{ $vendor->phone_number }}</h3>
        </div>


        <div class="social-links">
            @if ($vendor->facebook_link !== 'NA')
                <a href="{{ $vendor->facebook_link }}"><i class="fa-brands fa-square-facebook"></i></a>
            @endif

            @if ($vendor->instagram_link !== 'NA')
                <a href="{{ $vendor->instagram_link }}"><i class="fa-brands fa-instagram"></i></a>
            @endif

            @if ($vendor->youtube_link !== 'NA')
                <a href="{{ $vendor->youtube_link }}"><i class="fa-brands fa-youtube"></i></a>
            @endif

            @if ($vendor->x_link !== 'NA')
                <a href="{{ $vendor->x_link }}"><i class="fa-brands fa-square-x-twitter"></i></a>
            @endif

            @if ($vendor->other_link !== 'NA')
                <a href="{{ $vendor->other_link }}"><i class="fa-solid fa-link"></i></a>
            @endif



        </div>
        <i class="fa-solid fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#editProfile"></i>
        {{-- <div class="modal fade" id="editProfile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="staticBackdropLabel">Update your name and image</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="editProfile">

                        <form name="update-profile" class="profile-section-form">
                            <label for="">Name:</label>
                            <input type="text" name="name" validate='true'>
                            <label for="">Image:</label>
                            <input class="form-control" type="file" id="formFile" name="image">
                        </form>

                    </div>
                    <div class="modal-footer profile-update">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn kbs-btn" data-user-id='{{ $user->user_id }}'
                            data-route="{{ route('user.updateprofile') }}"
                            data-token="{{ session('userToken') }}">Update</button>
                    </div>
                </div>
            </div>
        </div> --}}
    </section>
    <section class="kbs-vendor-description">
        <h3>Description</h3>
        <p>{{ $vendor->vendor_description }}</p>
    </section>
    <livewire:vendor.profile :id="$id" />
@endsection
