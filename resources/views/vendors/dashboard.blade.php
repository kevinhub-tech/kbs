@extends('main')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
@endpush
@section('main.content')
    <section class="kbs-vendor-dashboard-container">
        <section class="kbs-vendor-dashboard">
            <div class="kbs-vendor-dashboard-card">
                <i class="fa-solid fa-cart-shopping"></i>
                <div class="kbs-card-metrics">
                    <h3>{{ $order_count }}</h3>
                    <a href="{{ route('vendor.order-listing') }}">Orders</a>
                </div>
            </div>
            <div class="kbs-vendor-dashboard-card">
                <i class="fa-solid fa-book"></i>
                <div class="kbs-card-metrics">
                    <h3>{{ $book_count }}</h3>
                    <a href="{{ route('vendor.book-listing') }}">Books</a>
                </div>
            </div>
            <div class="kbs-vendor-dashboard-card">
                <i class="fa-solid fa-tag"></i>
                <div class="kbs-card-metrics">
                    <h3>{{ $discount_count }}</h3>
                    <a href="{{ route('vendor.discount-listing') }}">Discounts</a>
                </div>
            </div>
            <div class="kbs-vendor-dashboard-card">
                <i class="fa-solid fa-comment"></i>
                <div class="kbs-card-metrics">
                    <h3>{{ $review_count }}</h3>
                    <a href="{{ route('vendor.vendorprofile', ['id' => session('userId')]) }}">Reviews</a>
                </div>
            </div>
        </section>
        <section class="kbs-vendor-charts">
            <div class="row">
                <div class="col-md-5">
                    <div id="order_status_ratio" data-pending="{{ $pending_count }}"
                        data-confirmed="{{ $confirmed_count }}" data-packing="{{ $packing_count }}"
                        data-packed="{{ $packed_count }}" data-handing-over="{{ $handing_over_count }}"
                        data-handed-over="{{ $handed_over_count }}" data-delivering="{{ $delivering_count }}"
                        data-delivered="{{ $delivered_count }}" data-completed="{{ $completed_count }}">
                    </div>
                </div>
                <div class="col-md-7 mt-md-0 mt-3">
                    <div id="monthly_revenue" data-first-week="{{ $first_week }}" data-second-week="{{ $second_week }}"
                        data-third-week="{{ $third_week }}" data-fourth-week="{{ $fourth_week }}">
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
@push('scripts')
    <script>
        google.charts.load('current', {
            'packages': ['corechart']
        });

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            // Create the data table.
            var orderStatusRatio = new google.visualization.DataTable();
            orderStatusRatio.addColumn('string', 'Status');
            orderStatusRatio.addColumn('number', 'Orders');
            orderStatusRatio.addRows([
                ['Pending', $('div#order_status_ratio').data('pending')],
                ['Confirmed', $('div#order_status_ratio').data('confirmed')],
                ['Packing', $('div#order_status_ratio').data('packing')],
                ['Packed', $('div#order_status_ratio').data('packed')],
                ['Handing-Over', $('div#order_status_ratio').data('handingOver')],
                ['Handed-Over', $('div#order_status_ratio').data('handedOver')],
                ['Delivering', $('div#order_status_ratio').data('delivering')],
                ['Delivered', $('div#order_status_ratio').data('delivered')],
                ['Completed', $('div#order_status_ratio').data('completed')],
            ]);

            var orderStatusRatioOptions = {
                height: 500,
                is3D: true,
                title: "Order Status Ratio",
                titleTextStyle: {
                    fontName: 'Manrope, sans-serif',
                    fontSize: 20,
                    bold: true
                },
            };

            // Instantiate and draw our chart, passing in some options.
            var orderStatusRatioChart = new google.visualization.PieChart(document.getElementById('order_status_ratio'));
            orderStatusRatioChart.draw(orderStatusRatio, orderStatusRatioOptions);
            // Create the data table.
            var monthlyRevenueData = google.visualization.arrayToDataTable([
                ['Weeks', 'Revenue'],
                ['1st Week', $('div#monthly_revenue').data('firstWeek')], // RGB value
                ['2nd Week', $('div#monthly_revenue').data('secondWeek')], // English color name
                ['3rd Week', $('div#monthly_revenue').data('thirdWeek')],
                ['4th Week', $('div#monthly_revenue').data('fourthWeek')], // CSS-style declaration
            ]);
            // Set chart options
            var monthlyRevenueOptions = {
                height: 500,
                colors: ['#005D6C'],
                title: "Each Month Weekly Income ($)",
                titleTextStyle: {
                    fontName: 'Manrope, sans-serif',
                    fontSize: 20,
                    bold: true
                }
            };

            // Instantiate and draw our chart, passing in some options.
            var monthlyRevenueChart = new google.visualization.ColumnChart(document.getElementById('monthly_revenue'));
            monthlyRevenueChart.draw(monthlyRevenueData, monthlyRevenueOptions);
        }
        window.addEventListener('resize', function() {
            drawChart();
        });
    </script>
@endpush
