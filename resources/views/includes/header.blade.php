<!--<div class="overmain">-->
	<style>
	.menuitem-user{
		width: 100%;
		max-width: initial;
	}
	.headeruser{
		padding: 0;
		padding-left: 10px;
	}
	</style>
    <div class="homemenu">
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
				<a href="/logout" class="loginout"><div class="menuitem menuitem-user"><i class="glyphicon glyphicon-log-out" title="Logout"></i><span class="headertext">&nbsp;{{Auth::user()->name}}</span></div></a>
			@endauth
			@guest
				<a href="/login" class="loginout"><div class="menuitem menuitem-user"><i class="glyphicon glyphicon-log-in" title="Login"></i><span class="headertext">&nbsp;Login</span></div></a>
			@endguest
		</div>

    </div>
<!--</div>-->

