<style>
.menuitem-user{
	width: 100%;
	max-width: initial;
    text-align: left;
    padding-left: 10px;
}
.headeruser{
	padding: 0;
	padding-left: 10px;
}
._dropdown{
	position: absolute;
    background: #333;
    padding: 0;
	font-size: 0.9em;
	border-left: 1px solid #666;
}

._dropdown div{
	padding: 5px 10px;
}
._dropdown div:hover{
	background: #666;
}
._dropdown a{
	display: block;
}
._dropdown a:hover{
	text-decoration: none;
}

#logo_img{
	height: 35px;
}

#search-result{
	position: absolute;
	top: 50px;
	z-index: 1000;
}

.list-group-item{
	cursor: pointer;
	padding: 5px;
}

.ui-autocomplete {
	max-width: 400px;
}

.searchitemflag {
    width: 20px;
    border-radius: 2px;
    margin-right: 4px;
    margin-bottom: 2px;
}
.searchitemheight {
	font-size: 0.8em;
    color: #666666;
    /* border: 1px solid #666666; */
    border-radius: 3px;
    padding: 2px;
    margin: 2px 4px;
    font-weight: 600;
    /* background: #dddddd; */
}
</style>
<!--<div class="homemenu">
	<div id="menuleft" class="col-md-8">
		<div class="homelogo">
			<a href="/"><img id="logo_img" src="/images/logo.png" /></a>
		</div>
		<a href="/"><div class="menuitem"><i class="glyphicon glyphicon-home" title="Home"></i><span class="headertext">Home</span></div></a>
		<a href="/new"><div class="menuitem"><i class="glyphicon glyphicon-asterisk" title="New"></i><span class="headertext">New</span></div></a>
		<a href="/stats"><div class="menuitem"><i class="glyphicon glyphicon-stats" title="Stats"></i><span class="headertext">Stats</span></div></a>
		<a href="/help"><div class="menuitem"><i class="glyphicon glyphicon-question-sign" title="Help"></i><span class="headertext">Help</span></div></a>
		<a href="/about"><div class="menuitem"><i class="glyphicon glyphicon-info-sign" title="About"></i><span class="headertext">About</span></div></a>
		<a href="/map"><div class="menuitem"><i class="glyphicon glyphicon-globe" title="Map"></i><span class="headertext">Map</span></div></a>
		<a id="twitter" href="https://twitter.com/cyclingcols" target="_blank">
			<i class="fa fa-twitter fa-lg" title="Follow CyclingCols on Twitter!"></i>
			<i class="fa fa-twitter fa-2x" title="Follow CyclingCols on Twitter!"></i>
			<i class="fa fa-twitter fa-3x" title="Follow CyclingCols on Twitter!"></i>
		</a>
	</div>
   
	<div id="menuright" class="headersearch col-md-2">
		<div id="searchtext">
			<input type="text" class="searchfield" placeholder="Search a col in Europe..." id="searchbox">
			<div id="searchstatus"></div>
		</div>
	</div>
	<div class="headeruser col-md-2">	
		@auth
			<a href="#" id="user" class="loginout"><div class="menuitem menuitem-user"><i class="glyphicon glyphicon-user" title="Logout"></i><span class="headertext">&nbsp;{{Auth::user()->name}}</span></div></a>
		@endauth
		@guest
			<a href="/login" class="loginout"><div class="menuitem menuitem-user"><i class="glyphicon glyphicon-log-in" title="Login"></i><span class="headertext">&nbsp;Login</span></div></a>
		@endguest
	</div>

</div>-->
	
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
	<form class="form-inline">
		<input class="form-control mr-sm-2" id="search-box" type="search" placeholder="Search" aria-label="Search">
		<!--<div id="search-result">
			<div class="list-group">

  <a href="#" class="list-group-item list-group-item-action active">
    Cras justo odio
  </a>
  <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>
  <a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
  <a href="#" class="list-group-item list-group-item-action">Porta ac consectetur ac</a>
  <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">Vestibulum at eros</a>
			</div>
		</div>-->
	</form>
	@endif
  </div>
</nav>

