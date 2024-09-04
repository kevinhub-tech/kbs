<section class="kbs-admin-listing-main-container">
    <section
        class="d-flex justify-content-between align-items-center d-flex justify-content-between align-items-center flex-column flex-sm-row">
        <div class="kbs-search-wrapper mb-2 mb-sm-0">
            <input wire:model.live="search" type="search" name="" id=""
                placeholder="Search Vendor Email here....">
        </div>
        <div class="kbs-button-wrapper">
            <!-- Button trigger modal -->
            <select wire:model.live="status" name="status">
                <option value="pending">Pending</option>
                <option value="accepted">Accepted</option>
                <option value="rejected">Rejected</option>
            </select>

        </div>
    </section>
    @if ($vendors->isEmpty())
        <section class="kbs-admin-empty">
            <h3>
                No application found...
            </h3>
        </section>
    @else
        <section class="kbs-table-wrapper">
            <table class="table-container">
                <thead>
                    <tr>
                        <th>SI</th>
                        <th>Vendor Email</th>
                        <th>Application Letter</th>
                        <th>Status</th>
                        <th>Rejection Reason</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendors as $vendor)
                        <tr>
                            <td data-title="SI">{{ $loop->iteration }}</td>
                            <td data-title="Vendor Email" class="mob-display-heading">{{ $vendor->email }}</td>
                            <td data-title="Application Letter" class="mob-display-heading">
                                <button data-bs-toggle="modal" data-bs-target="#{{ $vendor->application_id }}"
                                    data-toggle="tooltip" data-placement="top"
                                    title="Click here to view application letter"><i
                                        class="fa-solid fa-envelope"></i></button>
                                <!-- Modal -->
                                <div class="modal fade" id="{{ $vendor->application_id }}" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 id="staticBackdropLabel"> Application Letter From
                                                    {{ $vendor->email }}</h3>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" id="{{ $vendor->application_id }}">
                                                <p>
                                                    {{ $vendor->application_letter }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td data-title="Status" class="mob-display-heading">
                                <p
                                    class="@if ($vendor->status === 'pending') pending @elseif($vendor->status === 'accepted') accepted @elseif($vendor->status === 'rejected') rejected @endif">
                                    {{ ucfirst($vendor->status) }}</p>
                            </td>
                            <td data-title="Rejection Reason" class="mob-display-heading">
                                @if ($vendor->status === 'rejected')
                                    {{ $vendor->rejection_reason }}
                                @else
                                    N/A
                                @endif

                            </td>
                            <td data-title="Created
                                At" class="mob-display-heading">
                                @if ($vendor->created_at === null)
                                    NA
                                @else
                                    {{ date('d-m-Y', strtotime($vendor->created_at)) }}
                                @endif
                            </td>
                            <td data-title="Updated At" class="mob-display-heading">
                                @if ($vendor->updated_at === null)
                                    NA
                                @else
                                    {{ date('d-m-Y', strtotime($vendor->updated_at)) }}
                                @endif
                            </td>
                            <td>
                                <div class="action-wrapper">
                                    @if ($vendor->status === 'pending')
                                        <i class="fa-solid fa-check" data-toggle="tooltip" data-placement="top"
                                            title="Accept Partnership"
                                            data-route="{{ route('admin.updateapplicationstatus') }}"
                                            data-status="accepted" data-token="{{ session('userToken') }}"
                                            data-id="{{ $vendor->application_id }}"></i>
                                        <i class="fa-solid fa-x" data-toggle="tooltip" data-placement="top"
                                            title="Reject Partnership" data-bs-toggle="modal"
                                            data-bs-target="#reject-{{ $vendor->application_id }}"></i>
                                        <div class="modal fade" id="reject-{{ $vendor->application_id }}"
                                            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 id="staticBackdropLabel">Rejection Reason</h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" id="reject-{{ $vendor->application_id }}">
                                                        <label for="">Please insert why the application
                                                            is rejected</label>
                                                        <input type="text" name="rejection_reason" class="kbs-input">

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="kbs-reject-button"
                                                            data-route="{{ route('admin.updateapplicationstatus') }}"
                                                            data-status="rejected"
                                                            data-token="{{ session('userToken') }}"
                                                            data-id="{{ $vendor->application_id }}">Reject</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $vendors->links() }}
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
