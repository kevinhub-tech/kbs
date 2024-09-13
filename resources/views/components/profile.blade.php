<section class="kbs-profile-banner">
    @if ($user->image === null)
        <img src="{{ asset('image/default-user.jpg') }}" alt="">
    @elseif(str_contains($user->image, 'http'))
        <img src="{{ $user->image }}" alt="" />
    @else
        <img src="{{ route('get-image', ['route' => 'users', 'image' => $user->image]) }}" alt="">
    @endif

    <div class="d-flex flex-column justify-content-center ">
        <h3>{{ $user->name }}</h3>
        <h3>{{ $user->email }}</h3>
    </div>
    <i class="fa-solid fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#editProfile"></i>
    <div class="modal fade" id="editProfile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                        <input type="text" value="{{ $user->name }}" name="name" validate='true'>
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
    </div>
</section>
