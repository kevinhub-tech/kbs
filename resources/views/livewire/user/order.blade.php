<section class="kbs-listing-main-container">
    <section class="d-flex align-items-center flex-column flex-sm-row">
        <div class="kbs-search-wrapper mb-2 mb-sm-0">
            <input wire:model.live="search" type="search" name="" id=""
                placeholder="Search Order Number here....">
        </div>
    </section>
    @if ($orders->isEmpty())
        <section class="kbs-empty">
            <h3>
                No order found...
            </h3>
        </section>
    @else
        <section class="kbs-table-wrapper">
            <table class="table-container order-listing">
                <thead>
                    <tr>
                        <th>SI</th>
                        <th>Order Number</th>
                        <th>Book Items</th>
                        <th>Payment Method</th>
                        <th>Refundable</th>
                        <th>Delivery Address</th>
                        <th>Billing Address</th>
                        <th>Paid At</th>
                        <th>Delivered At</th>
                        <th>Ordered At</th>
                        <th>Updated At</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        @php
                            $order_detail_url = route('user.orderdetail') . '?id=' . $order->order_number;
                        @endphp
                        <tr>
                            <td data-title="SI">{{ $loop->iteration }}</td>
                            <td data-title="Order Number" class="mob-display-heading">{{ $order->order_number }}</td>
                            <td data-title="Books" class="mob-display-heading">
                                <ul>
                                    @foreach ($order->books as $book)
                                        <li>
                                            <a href="{{ route('user.book', ['id' => $book->book_id]) }}">{{ $book->book_name }}
                                                x {{ $book->quantity }}</a>
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

                            <td data-title="Delivery Address" class="mob-display-heading">
                                {{ $order->delivery_address->address }}, {{ $order->delivery_address->state }},
                                {{ $order->delivery_address->postal_code }}
                            </td>
                            <td data-title="Billing Address" class="mob-display-heading">
                                {{ $order->billing_address->address }}, {{ $order->billing_address->state }},
                                {{ $order->billing_address->postal_code }}
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
                            <td data-title="Status" class="mob-display-heading">
                                <h5 class="status @if ($order->status->status === 'cancelled') text-danger @endif">
                                    {{ ucfirst($order->status->status) }}</h5>
                            </td>
                            <td data-title="Total" class="mob-display-heading">

                                ${{ $order->total }}
                            </td>
                            <td>
                                <div class="action-wrapper">
                                    <a href="{{ $order_detail_url }}"><i class="fa-solid fa-eye" data-toggle="tooltip"
                                            data-placement="top" title="View Order Detail"></i></a>
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
