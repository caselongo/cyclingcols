<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta http-equiv="Expires" content="Mon, 25 Jun 2012 21:31:12 GMT" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('title')</title>
    <meta property="og:title" content="@yield('og_title')"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="@yield('og_url')"/>
    <meta property="og:image" content="@yield('og_image')"/>
    <meta property="og:site_name" content="CyclingCols"/>
    <meta property="og:description" content="@yield('og_description')"/>
    <meta property="fb:app_id" content="388989554589033"/>
	
	<!-- daisycon -->
	<meta name="1e4deb74b154703" content="8209946515fa895e15f081f6a6ffcbb7" />

    <link rel="shortcut icon" href="/images/cyclingcols2014_klein.ico">

    <link rel="stylesheet" href="/css/bootstrap.css" type="text/css">
	<!--<link rel="stylesheet" href="/css/jquery-ui.css" type="text/css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">-->
    <link rel="stylesheet" href="/css/main.css" type="text/css">
	<link rel="stylesheet" href="/fontawesome/css/all.css" type="text/css">
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
          integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
          crossorigin=""/>
		  
	<script src="https://connect.facebook.net/en_GB/sdk.js?hash=21512d9745cd529a7fc6e27ebb3f6edd&amp;ua=modern_es6" async="" crossorigin="anonymous"></script>
	<script id="twitter-wjs" src="https://platform.twitter.com/widgets.js"></script>
	<script id="facebook-jssdk" src="//connect.facebook.net/en_GB/sdk.js#xfbml=1&amp;appId=388989554589033&amp;version=v2.0"></script>

    <script src="/js/jquery-latest.min.js" type="text/javascript"></script>
    <script src="/js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/js/main.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="/js/jquery.backstretch.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" type="text/javascript"></script>
    <script src="/js/bootstrap.js" type="text/javascript"></script>
    <script src="https://kit.fontawesome.com/15e1216192.js" crossorigin="anonymous"></script>

    <script type="text/javascript">
        var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
        document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
        var pageTracker = _gat._getTracker("UA-6166464-1");
        pageTracker._trackPageview();
    </script>
	
	<!--<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5c84344adf6421001143b70c&product=inline-share-buttons' async='async'></script>-->
</head>
<body class="{{$pagetype ?? ''}} d-flex flex-column h-100">
        @include('includes.header')

        @yield('content')
		
        @include('includes.footer')
</body>
</html>