<title>{{ $title ?? config('app.name') }}</title>
<meta name="description" content="{{ $description ?? 'Default description' }}">
<meta name="keywords" content="{{ $keywords ?? 'default, keywords' }}">
<meta property="og:title" content="{{ $title ?? config('app.name') }}">
<meta property="og:description" content="{{ $description ?? 'Default description' }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
{{-- <meta property="og:image" content="{{ $image ?? asset('default-image.jpg') }}"> --}}
