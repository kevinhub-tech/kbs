@push('head')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-forget-password">
        <form action="{{ route('user.passwordreset') }}" method="POST">
            @csrf
            <h3>Forget Password Reset Token</h3>
            <input type="text" name="token" placeholder="Please Insert Token sent via Email ...." />
            <button type="submit">Proceed</button>
            <hr>
            <button name="reset-token">Resend Token<i class="fa-solid fa-rotate-right ms-2"></i></a>

        </form>
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
            toast('info', sessionMessage);
        }

        $('button[name="reset-token"]').on('click', function(e) {
            e.preventDefault();

            let id = '{{ $id }}';

            $.ajax({
                url: '{{ route('user.resetforgetpasswordtoken') }}',
                method: 'POST',
                data: {
                    id: id
                },
                success: (res) => {
                    if (res.status === 'success') {
                        toast('success', res.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
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
                        Object.values(jqXHR.responseJSON.errors).forEach((err) => {
                            err.forEach((e) => {
                                html += `${e} <hr />`;
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
    </script>
@endpush
