<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }}</title>
<link rel="icon" href="{{ asset('images/logos/Tjob_25-25.png') }}" type="image/png">
<meta property="og:image" content="{{ asset('images/logos/Tjob_25-25.png') }}" />

<!-- Fonts -->
<link rel="dns-prefetch" href="//fonts.bunny.net">
<!-- Scripts -->
<title>@yield('title', 'Dashboard')</title>

<!-- FontAwesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tabler-icons@latest/iconfont/tabler-icons.min.css" rel="stylesheet">
<script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
<!-- Fonts --><!-- Link to Font Awesome CDN for icons -->
<!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


<link rel="dns-prefetch" href="//fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

@vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css'])
