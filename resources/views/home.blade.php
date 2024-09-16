@push('head')
@endpush
@extends('main')
@section('main.content')
    <section class="kbs-main-hero-banner">
        <section class="kbs-hero-banner">
            <h1>
                Welcome to KBS
            </h1>
            <h3>
                Discover Your Next Great Read <br>in Book Heaven
            </h3>
            <a href="{{ route('user.register') }}">Explore Now</a>
        </section>
        <img src="{{ asset('image/hero-banner.gif') }}" alt="Anime girl reading book">
    </section>
    <section class="kbs-service-container">
        <div class="kbs-service-card">
            <i class="fa-solid fa-book"></i>
            <p>
                Discover high-quality books curated from top-notch vendors. We bring you the best reads, carefully selected
                to ensure a premium reading experience.
            </p>
        </div>
        <div class="kbs-service-card">
            <i class="fa-solid fa-location-pin"></i>
            <p>
                Effortlessly track your orders with our sleek and user-friendly interface. Enjoy a simple and convenient
                tracking experience with just a few clicks.
            </p>
        </div>
        <div class="kbs-service-card">
            <i class="fa-solid fa-handshake"></i>
            <p>
                Partner with us easily—apply to sell your books in just a few simple steps and expand your reach
                effortlessly.
            </p>
        </div>
    </section>
    <section class="kbs-detail">
        <h3>
            Discover a world of books across diverse categories, with community-powered reviews that make finding your next
            great read easier than ever
        </h3>
        <img src="{{ asset('image/community.png') }}"alt="Community Image">
    </section>

    <section class="kbs-vendor-detail">
        <div>
            <h3 class="mb-2">Unlock Your Vendor Potential</h3>
            <h5>What you should expect as a vendor from KBS</h5>
        </div>
        <div>
            <h3 class="mb-2 order">Seamless Order Management</h3>
            <h5>Manage your orders with ease using our intuitive, user-friendly dashboard. Track, update, and fulfill
                orders
                effortlessly with real-time updates at your fingertips. <br><br> No complicated
                processes—just simple, straightforward order management that saves you time.</h5>
        </div>
        <div>
            <h3 class="mb-2 book">Book Management</h3>
            <h5>Easily customize and manage your book listings. <br><br>You can quickly update titles, descriptions,
                pricing,
                and
                apply personalized discounts directly from your dashboard. <br><br>Additionally, vendors can apply
                exclusive
                bulk
                discounts, offering special deals for
                customers buying multiple copies or titles, all with just a few clicks.</h5>
        </div>
        <div>
            <h3 class="mb-2 discount">Discount Management</h3>
            <h5>Take full control of your promotions by creating custom discounts tailored to your business.
                <br><br>With
                flexible
                options, you can design the perfect discount strategy that fits your brand, driving sales and
                rewarding customers effortlessly.
            </h5>
        </div>
    </section>

    <section class="kbs-vendor-partnership">
        <section class="kbs-vendor-partnership-detail">
            <h1>
                Become Partner With Us
            </h1>
            <h3>
                Unlock your true potential as vendor
            </h3>
            <br>
            <div class="link-wrapper">
                <a href="{{ route('vendor.vendorapplication') }}">Apply</a>
            </div>

        </section>
        <img src="{{ asset('image/partner.png') }}" alt="Anime girl reading book">
    </section>
@endsection
