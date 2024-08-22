@extends('main')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
@endpush
@section('main.content')
    <section class="kbs-vendor-header">
        <h1>
            Manage Discounts
        </h1>
    </section>
    <livewire:search-discount />
@endsection
@push('scripts')
    <script>
        new MultiSelectTag('books', {
            rounded: true, // default true
            shadow: true, // default false
            placeholder: 'Search', // default Search...
            tagColor: {
                textColor: '#005D6C',
                borderColor: '#005D6C',
                bgColor: '#eaffe6',
            }
        });
        $('div.modal-footer button[type="button"].kbs-button').on('click', function(e) {
            let route = $(this).data('route');
            let token = $(this).data('token');

            if ($(this).html() === 'Add') {
                var formData = $('form[name="create-discount"]').serializeArray();
            } else if ($(this).html() === 'Update') {
                let discountId = $(this).data('discountId');
                var formData = $(`form[name="edit-discount"][data-discount-id='${discountId}']`).serializeArray();
            } else {
                var formData = $('form[name="apply-discount"]').serializeArray();
            }
            $.ajax({
                url: route,
                method: 'POST',
                headers: {
                    Accept: "application/json",
                    Authorization: token
                },
                data: formData,
                success: (res) => {
                    if (res.status === 'success') {
                        toast('success', res.message);
                        setTimeout(() => {
                            window.location.href = "{{ route('vendor.discount-listing') }}";
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

        $("i.fa-solid.fa-x").on('click', function(e) {
            let route = e.currentTarget.dataset.route;
            let token = e.currentTarget.dataset.token;
            Swal.fire({
                title: "Info!",
                icon: 'info',
                text: "You are about to remove the discount from one of the books. Are you sure?",
                showConfirmButton: true,
                showDenyButton: true,
                allowOutsideClick: false,
                confirmButtonText: 'Remove',
                denyButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    let bookId = e.currentTarget.dataset.bookId;
                    let discountId = e.currentTarget.dataset.discountId;
                    $.ajax({
                        url: route,
                        method: 'POST',
                        headers: {
                            Accept: "application/json",
                            Authorization: token
                        },
                        data: {
                            book_id: bookId,
                            discount_id: discountId,
                            method: 'partial'
                        },
                        success: (res) => {
                            if (res.status === 'success') {
                                toast('success', res.message);

                                if (res.payload.discount_count > 0) {
                                    $(`div[data-remove-book-id='${bookId}']`)
                                        .remove();
                                } else if (res.payload.discount_count === 0) {
                                    setTimeout(() => {
                                        window.location.href =
                                            "{{ route('vendor.discount-listing') }}";
                                    }, 1500);
                                }

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
                } else if (result.isDenied) {
                    Swal.close();
                }
            })
        })

        $("button.kbs-remove-all-button").on('click', function(e) {
            let route = e.currentTarget.dataset.route;
            let token = e.currentTarget.dataset.token;
            Swal.fire({
                title: "Info!",
                icon: 'info',
                text: "You are about to remove the discount from all of the books. Are you sure?",
                showConfirmButton: true,
                showDenyButton: true,
                allowOutsideClick: false,
                confirmButtonText: 'Remove',
                denyButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    let discountId = e.currentTarget.dataset.discountId;
                    $.ajax({
                        url: route,
                        method: 'POST',
                        headers: {
                            Accept: "application/json",
                            Authorization: token
                        },
                        data: {
                            discount_id: discountId,
                            method: 'all'
                        },
                        success: (res) => {
                            if (res.status === 'success') {
                                toast('success', res.message);
                                setTimeout(() => {
                                    window.location.href =
                                        "{{ route('vendor.discount-listing') }}";
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
                } else if (result.isDenied) {
                    Swal.close();
                }
            })
        })

        $("i.fa-solid.fa-trash").on('click', function(e) {
            let route = e.currentTarget.dataset.route;
            let token = e.currentTarget.dataset.token;
            Swal.fire({
                title: "Warning!",
                icon: 'info',
                text: "You are about to delete the discount from the database. This action is untracable. Are you sure?",
                showConfirmButton: true,
                showDenyButton: true,
                allowOutsideClick: false,
                confirmButtonText: 'Delete',
                denyButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    let discountId = e.currentTarget.dataset.discountId;
                    $.ajax({
                        url: route,
                        method: 'DELETE',
                        headers: {
                            Accept: "application/json",
                            Authorization: token
                        },
                        data: {
                            discount_id: discountId,
                        },
                        success: (res) => {
                            if (res.status === 'success') {
                                toast('success', res.message);
                                setTimeout(() => {
                                    window.location.href =
                                        "{{ route('vendor.discount-listing') }}";
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
                } else if (result.isDenied) {
                    Swal.close();
                }
            })
        })
    </script>
@endpush
