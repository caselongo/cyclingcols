@extends('layouts.master')

@section('title')
CyclingCols - Stats
@stop

@include('includes.functions')

@section('content')


<script type="text/javascript">	
	$(document).ready(function() {
		$(".stat_icon_header").removeClass("stat_icon_selected");
		$("#stat{{$statid}}").addClass("stat_icon_selected");
		
		$(".flag_header").removeClass("flag_selected");
		$("#flag{{$geoid}}").addClass("flag_selected");
	});

</script>

<?php
	$stat = Request::segment(2);
	$country = Request::segment(3);
	
	$statname = "";
	if ($stat == 0) $statname = "All Stats";
	else if ($stat == 1) $statname = "Distance";
	else if ($stat == 2) $statname = "Altitude Gain";
	else if ($stat == 3) $statname = "Average Slope";
	else if ($stat == 4) $statname = "Maximum Slope";
	else if ($stat == 5) $statname = "Profile Index";
	
	$countryname = "";
	if ($country == 0) $countryname = "All Countries";
	else if ($country == 2) $countryname = "Andorra";
	else if ($country == 3) $countryname = "Austria";
	else if ($country == 4) $countryname = "France";
	else if ($country == 5833) $countryname = "Great-Britain";
	else if ($country == 5) $countryname = "Italy";
	else if ($country == 6383) $countryname = "Norway";
	else if ($country == 6) $countryname = "Slovenia";
	else if ($country == 7) $countryname = "Spain";
	else if ($country == 8) $countryname = "Switzerland";
?>

<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light">Stats</h4>
	</div>		
	<div class="container-fluid mx-2">
		<nav class="navbar navbar-expand-sm navbar-light" style="display: none">	
			<ul class="navbar-nav">
			  <li class="nav-item">
				<a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Statistic</a>
			  </li>
			  <li class="nav-item 
				@if ($stat == 0) active @endif
				">
				<a class="nav-link" href="/stats/0/{{$country}}">All</a>
			  </li>
			  <li class="nav-item
				@if ($stat == 1) active @endif
				">
				<a class="nav-link" href="/stats/1/{{$country}}">Distance</a>
			  </li>
			  <li class="nav-item 
				@if ($stat == 2) active @endif
				">
				<a class="nav-link" href="/stats/2/{{$country}}">Altitude Gain</a>
			  </li>
			  <li class="nav-item 
				@if ($stat == 3) active @endif
				">
				<a class="nav-link" href="/stats/3/{{$country}}">Average Slope</a>
			  </li>
			  <li class="nav-item 
				@if ($stat == 4) active @endif
				">
				<a class="nav-link" href="/stats/4/{{$country}}">Maximum Slope</a>
			  </li>
			  <li class="nav-item 
				@if ($stat == 5) active @endif
				">
				<a class="nav-link" href="/stats/5/{{$country}}">Profile Index</a>
			  </li>
			</ul>
		</nav>
		<nav class="navbar navbar-expand-sm navbar-light" style="display: none">	
			<ul class="navbar-nav">
			  <li class="nav-item">
				<a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Country</a>
			  </li>
			  <li class="nav-item 
				@if ($country == 0) active @endif
				">
				<a class="nav-link" href="/stats/{{$stat}}/0">
				<!--<img src="/images/flags/Europe.gif" title="All" class="flag">-->All</a>
			  </li>
			  <li class="nav-item 
				@if ($country == 2) active @endif
				">
				<a class="nav-link" href="/stats/{{$stat}}/2">
				<!--<img src="/images/flags/Andorra.gif" title="All" class="flag">-->Andorra</a>
			  </li>
			  <li class="nav-item 
				@if ($country == 3) active @endif
				">
				<a class="nav-link" href="/stats/{{$stat}}/3">
				<!--<img src="/images/flags/Austria.gif" title="All" class="flag">-->Austria</a>
			  </li>
			  <li class="nav-item 
				@if ($country == 4) active @endif
				">
				<a class="nav-link" href="/stats/{{$stat}}/4">
				<!--<img src="/images/flags/France.gif" title="All" class="flag">-->France</a>
			  </li>
			  <li class="nav-item 
				@if ($country == 5833) active @endif
				">
				<a class="nav-link" href="/stats/{{$stat}}/5833">
				<!--<img src="/images/flags/Great-Britain.gif" title="All" class="flag">-->Great-Britain</a>
			  </li>
			  <li class="nav-item 
				@if ($country == 5) active @endif
				">
				<a class="nav-link" href="/stats/{{$stat}}/5">
				<!--<img src="/images/flags/Italy.gif" title="All" class="flag">-->Italy</a>
			  </li>
			  <li class="nav-item 
				@if ($country == 6383) active @endif
				">
				<a class="nav-link" href="/stats/{{$stat}}/6383">
				<!--<img src="/images/flags/Norway.gif" title="All" class="flag">-->Norway</a>
			  </li>
			  <li class="nav-item 
				@if ($country == 6) active @endif
				">
				<a class="nav-link" href="/stats/{{$stat}}/6">
				<!--<img src="/images/flags/Slovenia.gif" title="All" class="flag">-->Slovenia</a>
			  </li>
			  <li class="nav-item 
				@if ($country == 7) active @endif
				">
				<a class="nav-link" href="/stats/{{$stat}}/7">
				<!--<img src="/images/flags/Spain.gif" title="All" class="flag">-->Spain</a>
			  </li>
			  <li class="nav-item 
				@if ($country == 8) active @endif
				">
				<a class="nav-link" href="/stats/{{$stat}}/8">
				<!--<img src="/images/flags/Switzerland.gif" title="All" class="flag">-->Switzerland</a>
			  </li>
			</ul>
		</nav>
		
		
		<nav class="navbar navbar-expand-sm navbar-light">
			<ul class="nav">
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$statname}}</a>
				<div class="dropdown-menu">
				  <a class="dropdown-item" href="/stats/0/{{$country}}">All Stats</a>
				  <a class="dropdown-item" href="/stats/1/{{$country}}">Distance</a>
				  <a class="dropdown-item" href="/stats/2/{{$country}}">Altitude Gain</a>
				  <a class="dropdown-item" href="/stats/3/{{$country}}">Average Slope</a>
				  <a class="dropdown-item" href="/stats/4/{{$country}}">Maximum Slope</a>
				  <a class="dropdown-item" href="/stats/5/{{$country}}">Profile Index</a>
				</div>
			  </li>			  
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$countryname}}</a>
				<div class="dropdown-menu">
				  <a class="dropdown-item" href="/stats/{{$stat}}/0">
				<img src="/images/flags/Europe.gif" title="All" class="flag">All</a>
				  <a class="dropdown-item" href="/stats/{{$stat}}/2">
					<img src="/images/flags/Andorra.gif" title="All" class="flag">Andorra</a>
				  <a class="dropdown-item" href="/stats/{{$stat}}/3">
				<img src="/images/flags/Austria.gif" title="All" class="flag">Austria</a>
				  <a class="dropdown-item" href="/stats/{{$stat}}/4">
				<img src="/images/flags/France.gif" title="All" class="flag">France</a>
				  <a class="dropdown-item" href="/stats/{{$stat}}/5833">
				<img src="/images/flags/Great-Britain.gif" title="All" class="flag">Great-Britain</a>
				  <a class="dropdown-item" href="/stats/{{$stat}}/5">
				<img src="/images/flags/Italy.gif" title="All" class="flag">Italy</a>
				<a class="dropdown-item" href="/stats/{{$stat}}/6383">
				<img src="/images/flags/Norway.gif" title="All" class="flag">Norway</a>
				<a class="dropdown-item" href="/stats/{{$stat}}/6">
				<img src="/images/flags/Slovenia.gif" title="All" class="flag">Slovenia</a>
				<a class="dropdown-item" href="/stats/{{$stat}}/7">
				<img src="/images/flags/Spain.gif" title="All" class="flag">Spain</a>
				<a class="dropdown-item" href="/stats/{{$stat}}/8">
				<img src="/images/flags/Switzerland.gif" title="All" class="flag"></a>
				</div>
			  </li>
			</ul>
		</nav>
	</div>	
</main>
@stop
