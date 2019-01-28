<header>
	<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
		<a class="navbar-brand" href="/"><img id="logo_img" src="/images/logo.png" /></a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	  </button>

	  <div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
		  <li class="nav-item 
			@if (Request::is('/'))
				active
			@endif	  
			">
			<a class="nav-link" href="/">Home 
			@if (Request::is('/'))
				<span class="sr-only">(current)</span>
			@endif
			</a>
		  </li>
		  <li class="nav-item
			@if (Request::is('new') || Request::is('new/*'))
				active
			@endif	  
			">	 
			<a class="nav-link" href="/new">New 
			@if (Request::is('new') || Request::is('new/*'))
				<span class="sr-only">(current)</span>
			@endif
			</a>
		  </li>
		  <li class="nav-item
			@if (Request::is('stats') || Request::is('stats/*'))
				active
			@endif	  
			">
			<a class="nav-link" href="/stats">Stats 
			@if (Request::is('stats') || Request::is('stats/*'))
				<span class="sr-only">(current)</span>
			@endif
			</a>
		  </li>
		  <li class="nav-item
			@if (Request::is('help') || Request::is('help/*'))
				active
			@endif	  
			">
			<a class="nav-link" href="/help">Help 
			@if (Request::is('help') || Request::is('help/*'))
				<span class="sr-only">(current)</span>
			@endif
			</a>
		  </li>
		  <li class="nav-item
			@if (Request::is('about') || Request::is('about/*'))
				active
			@endif	  
			">
			<a class="nav-link" href="/about">About 
			@if (Request::is('about') || Request::is('about/*'))
				<span class="sr-only">(current)</span>
			@endif
			</a>
		  </li>  
		@auth
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			  {{Auth::user()->name}}
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			  <a class="dropdown-item" href="/logout">Logout</a>
			  <div class="dropdown-divider"></div>
			  <a class="dropdown-item" href="/user/cols">My CyclingCols</a>
			</div>
		  </li>
		@endauth
		@guest
		  <li class="nav-item
			@if (Request::is('login') || Request::is('login/*'))
				active
			@endif	  
			">
			<a class="nav-link" href="/login">Login 
			@if (Request::is('login') || Request::is('login/*'))
				<span class="sr-only">(current)</span>
			@endif
			</a>
		  </li>  
		@endguest

		</ul> 
		@if (!Request::is('/'))	
		<div class="navbar-nav">
			<input class="form-control mr-sm-2" id="search-box" type="search" placeholder="Search" aria-label="Search">
			<div id="search-box-wrapper" class="ui-front"></div>
		</div>
		@endif
	  </div>
	</nav>
</header>

