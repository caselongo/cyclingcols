<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta property="og:title" content="@yield('og_title')"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="@yield('og_url')"/>
    <meta property="og:image" content="@yield('og_image')"/>
    <meta property="og:site_name" content="CyclingCols"/>
    <meta property="og:description" content="@yield('og_description')"/>
    <meta property="fb:app_id" content="388989554589033"/>

    <link rel="shortcut icon" href="/images/cyclingcols2014_klein.ico">

    <link rel="stylesheet" href="/fonts/fonts.css" type="text/css">
    <link rel="stylesheet" href="/css/normalize.css" type="text/css">
    <link rel="stylesheet" href="/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="/css/main.css" type="text/css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
          integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
          crossorigin=""/>
    <!--<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css">
        <link href="https://api.tiles.mapbox.com/mapbox-gl-js/v0.35.1/mapbox-gl.css" rel='stylesheet' />-->

    <script src="/js/jquery.backstretch.min.js" type="text/javascript"></script>

    <script src="/js/jquery-latest.min.js" type="text/javascript"></script>
    <script src="/js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/js/main.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>


    <script type="text/javascript">
        var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
        document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
        var pageTracker = _gat._getTracker("UA-6166464-1");
        pageTracker._trackPageview();
    </script>
</head>
<body class="{{$pagetype ?? ''}}">
@if(isset($noHeader) && $noHeader === true)

@else

    <div id="div-scroll">
        @include('includes.header')
        @endif

        @yield('content')
        @include('includes.footer')

        @if(isset($noHeader) && $noHeader === true)

        @else
    </div>
@endif
</body>
</html>