<html>
<head>
    <title>{{ 'Welcome' }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('files/images/icon.ico')}}"/>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/bccf934f7c.js" crossorigin="anonymous"></script>

    @vite(['resources/css/public/public.scss', 'resources/js/public/public.js'])
</head>
<body>

@include('public.website.layout.snippets.menu')

<!-- Main page content -->
<div class="main-content">
    <!-- Main content of every page -->
    @yield('content')
</div>

@include('public.website.layout.snippets.footer')

</body>
</html>
