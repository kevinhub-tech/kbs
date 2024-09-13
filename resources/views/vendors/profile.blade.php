@push('head')
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-vendor-profile-banner">
        @if ($vendor->image === null)
            <img src="{{ asset('image/default-user.jpg') }}" alt="">
        @else
            <img src="{{ route('get-image', ['route' => 'vendors', 'image' => $vendor->image]) }}" alt="">
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
        @if (session('userSignedIn') && session('userRole') === 'vendor')
            <i class="fa-solid fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#editProfile"></i>
            <div class="modal fade" id="editProfile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="z-index:100000">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 id="staticBackdropLabel">Update your vendor information</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="editProfile">
                            <form action="" name="vendor-profile-update" method="POST" enctype="multipart/form-data"
                                class="profile-section-form">
                                <label for="">
                                    Vendor Name:
                                </label>
                                <input type="text" name="vendor_name" value="{{ $vendor->vendor_name }}"
                                    validate='true'>
                                <label for="" class="mb-3">Phone Number of your store:</label>
                                <input type="text" name="phone_number" value="{{ $vendor->phone_number }}"
                                    validate='true'>
                                <label for="" class="mb-3">Description about your store: </label>
                                <textarea name="vendor_description" cols="30" rows="10" validate='true'>{{ $vendor->vendor_description }}</textarea>
                                <label for="" class="mb-3">Facebook Link: (Leave 'NA' if you don't have
                                    any)</label>
                                <input type="text" name="facebook_link" value="{{ $vendor->facebook_link }}"
                                    validate='true'>
                                <label for="" class="mb-3">Instagram Link: (Leave 'NA' if you don't have
                                    any)</label>
                                <input type="text" name="instagram_link" value="{{ $vendor->instagram_link }}"
                                    validate='true'>
                                <label for="" class="mb-3">Youtube Link: (Leave 'NA' if you don't have
                                    any)</label>
                                <input type="text" name="youtube_link" value="{{ $vendor->youtube_link }}"
                                    validate='true'>
                                <label for="" class="mb-3">Twitter(X) Link: (Leave 'NA' if you don't have
                                    any)</label>
                                <input type="text" name="x_link" value="{{ $vendor->x_link }}" validate='true'>
                                <label for="" class="mb-3">Other Link: (Leave 'NA' if you don't have any)</label>
                                <input type="text" name="other_link" value="{{ $vendor->other_link }}" validate='true'>
                                <div class="mt-3 mb-3">
                                    <label for="formFile" class="form-label">Upload your new Vendor Image: <small
                                            class="text-danger">(optional)</small></label>
                                    <input class="form-control" type="file" id="formFile" name="image">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer vendor-profile-update">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn kbs-btn" data-vendor-id='{{ $vendor->vendor_id }}'
                                data-route="{{ route('vendor.updatevendorinfo') }}"
                                data-token="{{ session('userToken') }}">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </section>
    <section class="kbs-vendor-description">
        <h3>Description</h3>
        <p>{{ $vendor->vendor_description }}</p>
    </section>
    <livewire:vendor.profile :id="$id" />
@endsection
@push('scripts')
    <script>
        profileFunction();
    </script>
@endpush
