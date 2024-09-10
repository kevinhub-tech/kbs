<section class="kbs-listing-main-container">
    @if ($addresses->isEmpty())
        <section class="kbs-empty">
            <h3>
                No address found...
            </h3>
        </section>
    @else
        <section class="kbs-table-wrapper">
            <table class="table-container">
                <thead>
                    <tr>
                        <th>SI</th>
                        <th>Address</th>
                        <th>State</th>
                        <th>Phone Number</th>
                        <th>Postal Code</th>
                        <th>Default State</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($addresses as $address)
                        <tr>
                            <td data-title="SI">{{ $loop->iteration }}</td>
                            <td data-title="Address" class="mob-display-heading">{{ $address->address }}</td>
                            <td data-title="State" class="mob-display-heading">{{ $address->state }}
                            </td>
                            <td data-title="Phone Number" class="mob-display-heading">
                                {{ $address->phone_number }}
                            </td>
                            <td data-title="Postal Code" class="mob-display-heading">
                                {{ $address->postal_code }}
                            </td>
                            <td data-title="Default State" class="mob-display-heading">
                                @if ($address->default_address && $address->default_billing_address)
                                    <ul>
                                        <li>Default Delivery Address</li>
                                        <li>Default Billing Address</li>
                                    </ul>
                                @elseif($address->default_billing_address)
                                    <ul>
                                        <li>Default Billing Address</li>
                                    </ul>
                                @elseif($address->default_address)
                                    <ul>
                                        <li>Default Delivery Address</li>
                                    </ul>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td data-title="Created
                                At" class="mob-display-heading">
                                @if ($address->created_at === null)
                                    NA
                                @else
                                    {{ date('d-m-Y', strtotime($address->created_at)) }}
                                @endif
                            </td>
                            <td data-title="Updated At" class="mob-display-heading">
                                @if ($address->updated_at === null)
                                    NA
                                @else
                                    {{ date('d-m-Y', strtotime($address->updated_at)) }}
                                @endif
                            </td>
                            <td>
                                <div class="action-wrapper">
                                    @if (!$address->default_address && !$address->default_billing_address)
                                        <i data-route="{{ route('user.updatedefaultaddress') }}"
                                            data-token="{{ session('userToken') }}"
                                            data-address-id="{{ $address->address_id }}" class="fa-solid fa-house"
                                            data-toggle="tooltip" data-placement="top"
                                            title="Make
                                            Default Delivery Address"></i>
                                        <i data-route="{{ route('user.updatedefaultaddress') }}"
                                            data-token="{{ session('userToken') }}"
                                            data-address-id="{{ $address->address_id }}" class="fa-solid fa-money-bill"
                                            data-toggle="tooltip" data-placement="top"
                                            title="Make
                                            Default Billing Address"></i>
                                    @elseif (!$address->default_address)
                                        <i data-route="{{ route('user.updatedefaultaddress') }}"
                                            data-token="{{ session('userToken') }}"
                                            data-address-id="{{ $address->address_id }}" class="fa-solid fa-house"
                                            data-toggle="tooltip" data-placement="top"
                                            title="Make
                                    Default Delivery Address"></i>
                                    @elseif(!$address->default_billing_address)
                                        <i data-route="{{ route('user.updatedefaultaddress') }}"
                                            data-token="{{ session('userToken') }}"
                                            data-address-id="{{ $address->address_id }}" class="fa-solid fa-money-bill"
                                            data-toggle="tooltip" data-placement="top"
                                            title="Make
                                    Default Billing Address"></i>
                                    @elseif($address->default_address && $address->default_billing_address)
                                        N/A
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $addresses->links() }}
        </section>
    @endif
</section>
@push('scripts')
    <script>
        $(document).ready(() => {
            $('i.fa-check').on('click', function(e) {
                let route = $(this).data('route');
                let status = $(this).data('status');
                let token = $(this).data('token');
                let id = $(this).data('id');
                Swal.fire({
                    title: "Warning!",
                    icon: 'info',
                    text: "You are about to accept the application. Are you sure?",
                    showConfirmButton: true,
                    showDenyButton: true,
                    allowOutsideClick: false,
                    confirmButtonText: 'Proceed',
                    denyButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: route,
                            method: 'POST',
                            headers: {
                                Accept: "application/json",
                                Authorization: token
                            },
                            data: {
                                status: status,
                                id: id
                            },
                            success: (res) => {
                                if (res.status === 'success') {
                                    toast('success', res.message);
                                    Swal.close();
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
                    } else if (result.isDenied) {
                        Swal.close();
                    }
                });


            })
            $('button.kbs-reject-button').on('click', function(e) {
                let route = $(this).data('route');
                let status = $(this).data('status');
                let token = $(this).data('token');
                let id = $(this).data('id');
                let rejectionReason = $(this).parent().prev().children().eq(1).val();

                $.ajax({
                    url: route,
                    method: 'POST',
                    headers: {
                        Accept: "application/json",
                        Authorization: token
                    },
                    data: {
                        status: status,
                        id: id,
                        rejection_reason: rejectionReason
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
