<!DOCTYPE html>
<html dir="ltr" class="scroll-smooth"
      lang="{{ str_replace('_', '-', \Qoraiche\Peak\Helpers::getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
            href="https://fonts.googleapis.com/css2?family=Manrope:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Noto+Color+Emoji&family=Noto+Kufi+Arabic:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet">

    <!-- Scripts -->
    @routes

    @vite(['peak/resources/js/admin.js'])

    @inertiaHead

    <script>
        window.peak = {
            version: "{{peak_version()}}",
            title_separator: "{{website_general_setting('title_separator')}}",
        };
    </script>

    @if (get_custom_css_code())
        <style>
            {!!  get_custom_css_code() !!}
        </style>
    @endif

    @if (get_custom_js_code())
        {!!  get_custom_js_code() !!}
    @endif
</head>

<body class="font-sans antialiased">
@inertia
</body>
</html>