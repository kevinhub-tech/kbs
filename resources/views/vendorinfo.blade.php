@push('head')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="d-flex justify-content-center align-items-center h-100">
        @if ($is_token_expired)
            <section class="page-expired">
                <h3>
                    Your token has expired to gain access to this page. <br>If you would like to regenerate another link,
                    please
                    click below button.
                </h3>
                <button name="regenerate-link" data-route="{{ route('vendor.regenerate-link') }}"
                    data-id="{{ $token->application_id }}"> Regenerate Link</button>
            </section>
        @else
            <form action="{{ route('vendor.postvendorinfo', ['token' => $token->token]) }}" method="POST"
                class="vendor-application-form" enctype="multipart/form-data">
                @csrf
                <div class="logo">
                    <a class="nav-logo d-flex align-items-center" href='#'>
                        <i class="fa-regular fa-lightbulb"></i>
                        <h2 class="m-0">
                            KBS 📖
                        </h2>
                    </a>
                </div>
                <h4>Please fill out your store information
                </h4>
                <label for="">
                    Vendor Name:
                </label>
                @error('vendor_name')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
                <input type="text" name="vendor_name" value="{{ old('vendor_name') }}">
                <label for="" class="mb-3">Phone Number of your store:</label>
                @error('phone_number')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
                <input type="text" name="phone_number" value="{{ old('phone_number') }}">
                <label for="" class="mb-3">Provide some detail about your store to display to users:</label>
                @error('vendor_description')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
                <textarea name="vendor_description" cols="30" rows="10">{{ old('vendor_description') }}</textarea>
                <label for="" class="mb-3">Facebook Link: (Leave 'NA' if you don't have any)</label>
                @error('facebook_link')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
                <input type="text" name="facebook_link" value="{{ old('facebook_link') }}">
                <label for="" class="mb-3">Instagram Link: (Leave 'NA' if you don't have any)</label>
                @error('instagram_link')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
                <input type="text" name="instagram_link" value="{{ old('instagram_link') }}">
                <label for="" class="mb-3">Youtube Link: (Leave 'NA' if you don't have any)</label>
                @error('youtube_link')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
                <input type="text" name="youtube_link" value="{{ old('youtube_link') }}">
                <label for="" class="mb-3">Twitter(X) Link: (Leave 'NA' if you don't have any)</label>
                @error('x_link')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
                <input type="text" name="x_link" value="{{ old('x_link') }}">
                <label for="" class="mb-3">Other Link: (Leave 'NA' if you don't have any)</label>
                @error('other_link')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
                <input type="text" name="other_link" value="{{ old('other_link') }}">
                <div class="mt-3 mb-3">
                    <label for="formFile" class="form-label">Upload your Vendor Image: <small
                            class="text-danger">(optional)</small></label>
                    <input class="form-control" type="file" id="formFile" name="image">
                </div>
                <button type="submit">Submit</button>
            </form>
        @endif

    </section>
@endsection
@push('scripts')
    <script>
        @if (session('message'))
            const sessionMessage = "{{ session('message') }}";
        @else
            const sessionMessage = null;
        @endif

        if (sessionMessage) {
            toast('success', sessionMessage);
        }

        $(document).ready(() => {
            $('button[name="regenerate-link"]').on('click', function(e) {
                let route = $(this).data('route');
                let id = $(this).data('id');
                $.ajax({
                    url: route,
                    method: 'POST',
                    data: {
                        id: id
                    },
                    success: (res) => {
                        if (res.status === 'success') {
                            toast('success', res.message);
                            $(this).remove();
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
                        } else if (jqXHR.status == 409) {
                            errorMessage = jqXHR.responseJSON.message;
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
                            Object.values(jqXHR.responseJSON.errors).forEach((
                                err) => {
                                err.forEach((e) => {
                                    html += `${e}
<hr />`;
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
            })
        })
    </script>
@endpush
