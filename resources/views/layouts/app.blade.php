<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('components.seo', [
        'title' => $title ?? null,
        'description' => $description ?? null,
        'keywords' => $keywords ?? null,
        'image' => $image ?? null,
    ])

    @include('layouts.partials.head')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
    <!-- Include SEO component -->

</head>

<body>
    <div class="page-wrapper position-relative" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
        data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        @include('layouts.partials.side-menu') <!-- Sidebar menu -->
        <div class="body-wrapper bg-light-primary">
            @include('layouts.partials.navbar') <!-- Navbar -->
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
