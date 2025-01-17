<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KBS Store</title>

    {{-- bootstrap css CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- font-awesome v6.5.1 require files -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Jquery CDN --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- custom css and js to use globally --}}
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script type="text/javascript" src='{{ asset('js/main.js') }}'></script>



    {{-- bootstrap js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    {{-- toastify CDN --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    {{-- sweetalert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    {{-- Multi-select dropdown CDN --}}
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/css/multi-select-tag.css">
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/js/multi-select-tag.js"></script>

    @livewireStyles
    @stack('head')
</head>

<body>
    <x-nav></x-nav>
    <main>
        @yield('main.content')
    </main>
    <x-footer></x-footer>
</body>
@if (session('userSignedIn') && session('userRole') === 'user')
    <script type="text/javascript" src='{{ asset('js/user-section.js') }}'></script>
@elseif(session('userSignedIn') && session('userRole') === 'vendor')
    <script type="text/javascript" src='{{ asset('js/vendor-section.js') }}'></script>
@elseif(session('userSignedIn') && session('userRole') === 'admin')
    <script type="text/javascript" src='{{ asset('js/admin-section.js') }}'></script>
@endif

@livewireScripts
@stack('scripts')

</html>
