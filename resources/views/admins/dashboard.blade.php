@extends('main')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush
@section('main.content')
    <section class="kbs-admin-dashboard-container">
        <section class="kbs-admin-dashboard">
            <div class="kbs-admin-dashboard-card">
                <i class="fa-solid fa-users"></i>
                <div class="kbs-card-metrics">
                    <h3>{{ $vendor_count }}</h3>
                    <a href="{{ route('admin.vendors') }}">Vendors</a>
                </div>
            </div>
            <div class="kbs-admin-dashboard-card">
                <i class="fa-solid fa-user"></i>
                <div class="kbs-card-metrics">
                    <h3>{{ $user_count }}</h3>
                    <a href="#">Users</a>
                </div>
            </div>
            <div class="kbs-admin-dashboard-card">
                <i class="fa-solid fa-tag"></i>
                <div class="kbs-card-metrics">
                    <h3>{{ $order_count }}</h3>
                    <a href="#">Total Orders Made</a>
                </div>
            </div>
            <div class="kbs-admin-dashboard-card">
                <i class="fa-solid fa-file"></i>
                <div class="kbs-card-metrics">
                    <h3>{{ $vendor_application_count }}</h3>
                    <a href="{{ route('admin.vendors') }}">Vendor Application</a>
                </div>
            </div>
        </section>
        <section class="kbs-admin-charts">
            <div class="row">
                <div class="col-md-5">
                    <div id="monthly_vendor_application_status"
                        data-pending="{{ $monthly_pending_vendor_application_count }}"
                        data-rejected="{{ $monthly_rejected_vendor_application_count }}"
                        data-accepted="{{ $monthly_accepted_vendor_application_count }}">
                    </div>
                </div>
                <div class="col-md-7 mt-md-0 mt-3">
                    <div id="yearly_user_scale">
                    </div>
                </div>
            </div>
        </section>
    </section>
    @php
        // Convert the $yearly_user_scale into the required format
        $usersData = array_map(
            function ($count, $month) {
                return [$month, $count];
            },
            $yearly_user_scale,
            array_keys($yearly_user_scale),
        );
    @endphp
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
            var vendorApplicationStatusRatio = new google.visualization.DataTable();
            vendorApplicationStatusRatio.addColumn('string', 'Topping');
            vendorApplicationStatusRatio.addColumn('number', 'Slices');
            vendorApplicationStatusRatio.addRows([
                ['Pending', $('div#monthly_vendor_application_status').data('pending')],
                ['Accepted', $('div#monthly_vendor_application_status').data('accepted')],
                ['Rejected', $('div#monthly_vendor_application_status').data('rejected')],
            ]);

            var vendorApplicationStatusRatioOptions = {
                height: 500,
                is3D: true,
                title: "Monthly Vendor Application Status Ratio",
                titleTextStyle: {
                    fontName: 'Manrope, sans-serif',
                    fontSize: 20,
                    bold: true
                },
            };


            // Instantiate and draw our chart, passing in some options.
            var vendorApplicationStatusRatioChart = new google.visualization.PieChart(document.getElementById(
                'monthly_vendor_application_status'));
            vendorApplicationStatusRatioChart.draw(vendorApplicationStatusRatio, vendorApplicationStatusRatioOptions);

            var yearlyUserScaleData = new google.visualization.DataTable();
            yearlyUserScaleData.addColumn('string', 'Months');
            yearlyUserScaleData.addColumn('number', 'Users');
            // Assuming $usersByMonth is the result from the previous code
            var usersData = @json($usersData);

            yearlyUserScaleData.addRows(usersData);

            var yearlyUserScaleOptions = {
                hAxis: {
                    title: 'Months'
                },
                vAxis: {
                    title: 'Users'
                },
                height: 500,
                title: new Date().getFullYear() + " User Scale",
                titleTextStyle: {
                    fontName: 'Manrope, sans-serif',
                    fontSize: 20,
                    bold: true
                },
                titlePosition: 'center',
            };

            var yearlyUserScaleChart = new google.visualization.LineChart(document.getElementById('yearly_user_scale'));

            yearlyUserScaleChart.draw(yearlyUserScaleData, yearlyUserScaleOptions);
        }
        window.addEventListener('resize', function() {
            drawChart();
        });
    </script>
@endpush
