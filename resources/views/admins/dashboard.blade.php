@extends('main')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
@endpush
@section('main.content')
    <section class="kbs-vendor-dashboard-container">
        <section class="kbs-vendor-dashboard">
            <div class="kbs-vendor-dashboard-card">
                <i class="fa-solid fa-users"></i>
                <div class="kbs-card-metrics">
                    <h3>{{ $vendor_count }}</h3>
                    <a href="{{ route('admin.vendors') }}">Vendors</a>
                </div>
            </div>
            <div class="kbs-vendor-dashboard-card">
                <i class="fa-solid fa-user"></i>
                <div class="kbs-card-metrics">
                    <h3>{{ $user_count }}</h3>
                    <a href="#">Users</a>
                </div>
            </div>
            <div class="kbs-vendor-dashboard-card">
                <i class="fa-solid fa-tag"></i>
                <div class="kbs-card-metrics">
                    <h3>{{ $order_count }}</h3>
                    <a href="{{ route('vendor.discount-listing') }}">Total Orders Made</a>
                </div>
            </div>
            <div class="kbs-vendor-dashboard-card">
                <i class="fa-solid fa-file"></i>
                <div class="kbs-card-metrics">
                    <h3>{{ $vendor_application_count }}</h3>
                    <a href="{{ route('admin.vendors') }}">Vendor Application</a>
                </div>
            </div>
        </section>
        <section class="kbs-vendor-charts">
            <div class="row">
                <div class="col-md-5">
                    <div id="order_status_ratio">
                    </div>
                </div>
                <div class="col-md-7 mt-md-0 mt-3">
                    <div id="monthly_revenue">
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
        function drawChart() {}
        window.addEventListener('resize', function() {
            drawChart();
        });
    </script>
@endpush
