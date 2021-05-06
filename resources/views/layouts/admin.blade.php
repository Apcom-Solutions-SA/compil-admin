<!DOCTYPE html>
<html lang="fr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$title?? "Admin"}}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Add iOS meta tags and icons -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Instant-regard">
    <!-- prevent photos appear in google search-->
    <meta name="robots" content="noindex">
    <!-- Favicon -->
    @if( setting('site.favicon') )
    <link rel="icon" href="{{ Storage::disk(config('voyager.storage.disk'))->url(setting('site.favicon')) }}">
    @elseif( isset($favicon_url) && $favicon_url )
    <link rel="icon" href="{{ $favicon_url }}">
    @else
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json')}}">
    @endif

    <!-- Scripts -->
    <script src="{{ asset('js/vue-i18n-locales.generated.js') }}"></script>
    <script>
        window.App = {!! json_encode(['authUser' => Auth::user()]) !!};
    </script>
    @yield('head')
</head>

<body>
    <div class="admin-wrapper" id="app">
        <!-- Sidebar -->
        <nav-admin></nav-admin>
        <!-- Top nav -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div>
                    <!-- button to toggle sidebar -->
                    <i class="fas fa-bars" id="sidebarCollapse"></i>
                </div>
            </nav>
            <div class="container-fluid" style="padding-top:15px;">
                @yield('content')
            </div>
        </div>
        <back-to-top bottom="30px" right="30px">
            <button type="button" class="btn btn-info btn-to-top"><i class="fas fa-chevron-up"></i></button>
        </back-to-top>
        <flash message="{{ session('flash') }}"></flash>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            // toggle sidebar
            $('#sidebarCollapse ').on('click', function() {
                $('#sidebar').toggleClass('active');
            });
            $(".rotate").click(function() {
                $(this).toggleClass("down");
            });
        });
    </script>
    @yield('scripts')
</body>

</html>