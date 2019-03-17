<?php
	$path = Request::path();
	$pos = stripos($path, '/');
	if ($pos > 0){
		$path = substr($path,0,$pos);
	}
	$home = ($path == "/");
	$new = ($path == "new");
	$stats = ($path == "stats");
	$stats2 = ($path == "stats2");
	$help = ($path == "help");
	$about = ($path == "about");
	$map = ($path == "map");
	$login = ($path == "login");
?>

<header>
	<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
		<a class="navbar-brand" href="/"><img id="logo_img" src="/images/logo.png" /></a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
		  <li class="nav-item font-weight-light
			@if ($home)
				active
			@endif	  
			">
			<a class="nav-link" href="/">Home 
			@if ($home)
				<span class="sr-only">(current)</span>
			@endif
			</a>
		  </li>
		  <li class="nav-item font-weight-light
			@if ($new)
				active
			@endif	  
			">	 
			<a class="nav-link" href="/new">New 
			@if ($new)
				<span class="sr-only">(current)</span>
			@endif
			</a>
		  </li>
		  <li class="nav-item font-weight-light
			@if ($stats)
				active
			@endif	  
			">
			<a class="nav-link" href="/stats">Stats 
			@if ($stats)
				<span class="sr-only">(current)</span>
			@endif
			</a>
		  </li>
		  <li class="nav-item font-weight-light
			@if ($help)
				active
			@endif	  
			">
			<a class="nav-link" href="/help">Help 
			@if ($help)
				<span class="sr-only">(current)</span>
			@endif
			</a>
		  </li>
		  <li class="nav-item font-weight-light
			@if ($about)
				active
			@endif	  
			">
			<a class="nav-link" href="/about">About 
			@if ($about)
				<span class="sr-only">(current)</span>
			@endif
			</a>
		  </li>
		  <li class="nav-item font-weight-light
			@if ($map)
				active
			@endif	  
			">
			<a class="nav-link" href="/map">Map 
			@if ($map)
				<span class="sr-only">(current)</span>
			@endif
			</a>
		  </li>  
		@auth
		  <li class="nav-item font-weight-light dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			  {{Auth::user()->name}}
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			  <a class="dropdown-item font-weight-light" href="/athlete/{{Auth::user()->id}}">Dashboard</a>
			  <div class="dropdown-divider"></div>
			  <a class="dropdown-item font-weight-light" href="/athletes">All Athletes</a>
			  <div class="dropdown-divider"></div>
			  <a class="dropdown-item font-weight-light" href="/logout">Logout</a>
			</div>
		  </li>
		@endauth
		@guest
		  <li class="nav-item font-weight-light
			@if ($login)
				active
			@endif	  
			">
			<a class="nav-link" href="/login">Login 
			@if ($login)
				<span class="sr-only">(current)</span>
			@endif
			</a>
		  </li>  
		@endguest
		  <li class="nav-item d-flex align-items-center">
			<a class="nav-link" href="https://twitter.com/cyclingcols" target="_blank"><i class="fab fa-twitter"></i></a>
		  </li> 
		</ul> 
		@if (!$home)	
		<div class="navbar-nav">
			<input class="form-control mr-sm-2 px-2 py-1 font-weight-light" id="search-box" type="search" placeholder="Search a col in Europe..." aria-label="Search">
			<div id="search-box-wrapper" class="ui-front"></div>
		</div>
		@endif
	  </div>
	</nav>
</header>

