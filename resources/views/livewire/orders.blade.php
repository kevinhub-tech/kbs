<section class="kbs-book-listing-main-container">
    <section
        class="d-flex justify-content-between align-items-center d-flex justify-content-between align-items-center flex-column flex-sm-row">
        <div class="kbs-search-wrapper mb-2 mb-sm-0">
            <input wire:model.live="search" type="search" name="" id=""
                placeholder="Search Order Number here....">
        </div>
        <div class="kbs-button-wrapper">
            <!-- Button trigger modal -->
            <select name="" id="">
                <option value="">Pending</option>
                <option value="">Confirmed</option>
                <option value="">Packing</option>
                <option value="">Packed</option>
                <option value="">Handing-Over</option>
                <option value="">Handed-Over</option>
                <option value="">Delivering</option>
                <option value="">Delivered</option>
                <option value="">Completed</option>
                <option value="">Cancelled</option>
            </select>

        </div>
    </section>
    @if ($orders->isEmpty())
        <section class="kbs-vendor-empty">
            <h3>
                No order found...
            </h3>
        </section>
    @else
        <section class="kbs-table-wrapper">
            <table class="table-container">
                <thead>
                    <tr>
                        <th>SI</th>
                        <th>Order Number</th>
                        <th>Book Items</th>
                        <th>Payment Method</th>
                        <th>Refundable</th>
                        <th>Is Cancelled</th>
                        <th>Delivery Address</th>
                        <th>Billing Address</th>
                        <th>Ordered By</th>
                        <th>Paid At</th>
                        <th>Delivered At</th>
                        <th>Ordered At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td data-title="SI">{{ $loop->iteration }}</td>
                            <td data-title="Order Number" class="mob-display-heading">{{ $order->order_number }}</td>
                            <td data-title="Books" class="mob-display-heading">
                                <ul>
                                    @foreach ($order->books as $book)
                                        <li>
                                            <a
                                                href="{{ route('vendor.bookdesc', ['id' => $book->book_id]) }}">{{ $book->book_name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td data-title="Payment Method" class="mob-display-heading">{{ $order->payment_method }}
                            </td>
                            <td data-title="Refundable" class="mob-display-heading">
                                @if ($order->refund_state)
                                    Yes
                                @else
                                    No
                                @endif
                            </td>
                            <td data-title="Is Cancelled" class="mob-display-heading">
                                @if ($order->is_cancelled)
                                    Yes
                                @else
                                    No
                                @endif
                            </td>
                            <td data-title="Delivery Address" class="mob-display-heading">
                                {{ $order->delivery_address->address }}, {{ $order->delivery_address->state }},
                                {{ $order->delivery_address->postal_code }}
                            </td>
                            <td data-title="Billing Address" class="mob-display-heading">
                                {{ $order->billing_address->address }}, {{ $order->billing_address->state }},
                                {{ $order->billing_address->postal_code }}
                            </td>
                            <td data-title="Ordered By" class="mob-display-heading">
                                {{ $order->order_user->name }}
                            </td>
                            <td data-title="Paid At" class="mob-display-heading">
                                @if ($order->paid_at === null)
                                    Not Paid
                                @else
                                    {{ date('d-m-Y', strtotime($order->paid_at)) }}
                                @endif
                            </td>
                            <td data-title="Delivered At" class="mob-display-heading">
                                @if ($order->delivered_at === null)
                                    Not Delivered
                                @else
                                    {{ date('d-m-Y', strtotime($order->delivered_at)) }}
                                @endif
                            </td>
                            <td data-title="Ordered At" class="mob-display-heading">
                                @if ($order->created_at === null)
                                    NA
                                @else
                                    {{ date('d-m-Y', strtotime($order->created_at)) }}
                                @endif
                            </td>
                            <td data-title="Updated At" class="mob-display-heading">
                                @if ($order->updated_at === null)
                                    NA
                                @else
                                    {{ date('d-m-Y', strtotime($order->updated_at)) }}
                                @endif
                            </td>
                            <td>
                                <div class="action-wrapper">
                                    <a href="{{ route('vendor.bookdesc', ['id' => $book->book_id]) }}"
                                        data-toggle="tooltip" data-placement="top" title="View Book Description"><i
                                            class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('vendor.book-edit', ['id' => $book->book_id]) }}"
                                        data-toggle="tooltip" data-placement="top" title="Edit Book"><i
                                            class="fa-solid fa-pen-to-square"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $orders->links() }}
        </section>
    @endif
</section>
