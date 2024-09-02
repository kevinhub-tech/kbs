<section class="kbs-listing-main-container">
    <section
        class="d-flex justify-content-between align-items-center d-flex justify-content-between align-items-center flex-column flex-sm-row">
        <div class="kbs-search-wrapper mb-2 mb-sm-0">
            <input wire:model.live="search" type="search" name="" id=""
                placeholder="Search Order Number here....">
        </div>
        <div class="kbs-button-wrapper">
            <!-- Button trigger modal -->
            <select wire:model.live="status" name="status">
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="packing">Packing</option>
                <option value="packed">Packed</option>
                <option value="handing-over">Handing-Over</option>
                <option value="handed-over">Handed-Over</option>
                <option value="delivering">Delivering</option>
                <option value="delivered">Delivered</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
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
                        <th>Ordered By</th>
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
                        <tr>
                            <td data-title="SI">{{ $loop->iteration }}</td>
                            <td data-title="Order Number" class="mob-display-heading">{{ $order->order_number }}</td>
                            <td data-title="Books" class="mob-display-heading">
                                <ul>
                                    @foreach ($order->books as $book)
                                        <li>
                                            <a href="{{ route('vendor.bookdesc', ['id' => $book->book_id]) }}">{{ $book->book_name }}
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
                            <td data-title="Status" class="mob-display-heading">
                                <h5 class="status @if ($order->status->status === 'cancelled') text-danger @endif">
                                    {{ ucfirst($order->status->status) }}</h5>
                            </td>
                            <td data-title="Total" class="mob-display-heading">

                                ${{ $order->total }}
                            </td>
                            <td>
                                <div class="action-wrapper">
                                    @if ($order->status->status === 'pending')
                                        <button class="complete" name="order-status-updater"
                                            data-order-id="{{ $order->order_id }}" data-status="confirmed"
                                            data-route="{{ route('vendor.updateorderstatus') }}"
                                            data-token="{{ session('userToken') }}"> Confirm Order</button>
                                    @elseif($order->status->status === 'confirmed')
                                        <button class="start" name="order-status-updater"
                                            data-order-id="{{ $order->order_id }}" data-status="packing"
                                            data-route="{{ route('vendor.updateorderstatus') }}"
                                            data-token="{{ session('userToken') }}"> Start Packing</button>
                                    @elseif($order->status->status === 'packing')
                                        <button class="complete" name="order-status-updater"
                                            data-order-id="{{ $order->order_id }}" data-status="packed"
                                            data-route="{{ route('vendor.updateorderstatus') }}"
                                            data-token="{{ session('userToken') }}"> Complete Packing</button>
                                    @elseif($order->status->status === 'packed')
                                        <button class="start" name="order-status-updater" data-status="handing-over"
                                            data-order-id="{{ $order->order_id }}"
                                            data-route="{{ route('vendor.updateorderstatus') }}"
                                            data-token="{{ session('userToken') }}"> Start Hand-over</button>
                                    @elseif($order->status->status === 'handing-over')
                                        <button class="complete" name="order-status-updater" data-status="handed-over"
                                            data-order-id="{{ $order->order_id }}"
                                            data-route="{{ route('vendor.updateorderstatus') }}"
                                            data-token="{{ session('userToken') }}"> Complete Hand-over</button>
                                    @elseif($order->status->status === 'handed-over')
                                        <button class="start" name="order-status-updater" data-status="delivering"
                                            data-order-id="{{ $order->order_id }}"
                                            data-route="{{ route('vendor.updateorderstatus') }}"
                                            data-token="{{ session('userToken') }}"> Start Delivery</button>
                                    @elseif($order->status->status === 'delivering')
                                        <button class="complete" name="order-status-updater" data-status="delivered"
                                            data-order-id="{{ $order->order_id }}"
                                            data-route="{{ route('vendor.updateorderstatus') }}"
                                            data-token="{{ session('userToken') }}"> Complete Delivery</button>
                                    @else
                                        <h5>NA</h5>
                                    @endif

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
