@extends('layouts.master')

@section('title')
CyclingCols - Stats
@stop

@include('includes.functions')

@section('content')

<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light">Stats</h4>
	</div>		
	<div class="container-fluid">
		<nav class="navbar navbar-expand-sm navbar-light" style="display: none">	
			<ul class="navbar-nav">
			  <li class="nav-item">
				<a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Statistic</a>
			  </li>
			  <li class="nav-item 
				@if ($stattype->id == 0) active @endif
				">
				<a class="nav-link" href="/stats/0/{{$country->id}}">All</a>
			  </li>
			  <li class="nav-item
				@if ($stattype->id == 1) active @endif
				">
				<a class="nav-link" href="/stats/1/{{$country->id}}">Distance</a>
			  </li>
			  <li class="nav-item 
				@if ($stattype->id == 2) active @endif
				">
				<a class="nav-link" href="/stats/2/{{$country->id}}">Altitude Gain</a>
			  </li>
			  <li class="nav-item 
				@if ($stattype->id == 3) active @endif
				">
				<a class="nav-link" href="/stats/3/{{$country->id}}">Average Slope</a>
			  </li>
			  <li class="nav-item 
				@if ($stattype->id == 4) active @endif
				">
				<a class="nav-link" href="/stats/4/{{$country->id}}">Maximum Slope</a>
			  </li>
			  <li class="nav-item 
				@if ($stattype->id == 5) active @endif
				">
				<a class="nav-link" href="/stats/5/{{$country->id}}">Profile Index</a>
			  </li>
			</ul>
		</nav>
		<nav class="navbar navbar-expand-sm navbar-light" style="display: none">	
			<ul class="navbar-nav">
			  <li class="nav-item">
				<a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Country</a>
			  </li>
			  <li class="nav-item 
				@if ($country->id == 0) active @endif
				">
				<a class="nav-link" href="/stats/{{$stattype->id}}/0">
				<!--<img src="/images/flags/Europe.gif" title="All" class="flag">-->All</a>
			  </li>
			  <li class="nav-item 
				@if ($country->id == 2) active @endif
				">
				<a class="nav-link" href="/stats/{{$stattype->id}}/2">
				<!--<img src="/images/flags/Andorra.gif" title="All" class="flag">-->Andorra</a>
			  </li>
			  <li class="nav-item 
				@if ($country->id == 3) active @endif
				">
				<a class="nav-link" href="/stats/{{$stattype->id}}/3">
				<!--<img src="/images/flags/Austria.gif" title="All" class="flag">-->Austria</a>
			  </li>
			  <li class="nav-item 
				@if ($country->id == 4) active @endif
				">
				<a class="nav-link" href="/stats/{{$stattype->id}}/4">
				<!--<img src="/images/flags/France.gif" title="All" class="flag">-->France</a>
			  </li>
			  <li class="nav-item 
				@if ($country->id == 5833) active @endif
				">
				<a class="nav-link" href="/stats/{{$stattype->id}}/5833">
				<!--<img src="/images/flags/Great-Britain.gif" title="All" class="flag">-->Great-Britain</a>
			  </li>
			  <li class="nav-item 
				@if ($country->id == 5) active @endif
				">
				<a class="nav-link" href="/stats/{{$stattype->id}}/5">
				<!--<img src="/images/flags/Italy.gif" title="All" class="flag">-->Italy</a>
			  </li>
			  <li class="nav-item 
				@if ($country->id == 6383) active @endif
				">
				<a class="nav-link" href="/stats/{{$stattype->id}}/6383">
				<!--<img src="/images/flags/Norway.gif" title="All" class="flag">-->Norway</a>
			  </li>
			  <li class="nav-item 
				@if ($country->id == 6) active @endif
				">
				<a class="nav-link" href="/stats/{{$stattype->id}}/6">
				<!--<img src="/images/flags/Slovenia.gif" title="All" class="flag">-->Slovenia</a>
			  </li>
			  <li class="nav-item 
				@if ($country->id == 7) active @endif
				">
				<a class="nav-link" href="/stats/{{$stattype->id}}/7">
				<!--<img src="/images/flags/Spain.gif" title="All" class="flag">-->Spain</a>
			  </li>
			  <li class="nav-item 
				@if ($country->id == 8) active @endif
				">
				<a class="nav-link" href="/stats/{{$stattype->id}}/8">
				<!--<img src="/images/flags/Switzerland.gif" title="All" class="flag">-->Switzerland</a>
			  </li>
			</ul>
		</nav>
		
		
		<nav class="navbar navbar-expand-sm navbar-light">
			<ul class="nav">
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$stattype->name}}</a>
				<div class="dropdown-menu">
				  <a class="dropdown-item" href="/stats/all/{{$country->url}}">All Stats</a>
				  <a class="dropdown-item" href="/stats/distance/{{$country->url}}">Distance</a>
				  <a class="dropdown-item" href="/stats/altitudegain/{{$country->url}}">Altitude Gain</a>
				  <a class="dropdown-item" href="/stats/averageslope/{{$country->url}}">Average Slope</a>
				  <a class="dropdown-item" href="/stats/maximumslope/{{$country->url}}">Maximum Slope</a>
				  <a class="dropdown-item" href="/stats/profileindex/{{$country->url}}">Profile Index</a>
				</div>
			  </li>			  
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$country->name}}</a>
				<div class="dropdown-menu">
				  <a class="dropdown-item" href="/stats/{{$stattype->url}}/all">
				<img src="/images/flags/Europe.gif" title="All" class="flag">All</a>
				  <a class="dropdown-item" href="/stats/{{$stattype->url}}/and">
					<img src="/images/flags/Andorra.gif" title="All" class="flag">Andorra</a>
				  <a class="dropdown-item" href="/stats/{{$stattype->url}}/aut">
				<img src="/images/flags/Austria.gif" title="All" class="flag">Austria</a>
				  <a class="dropdown-item" href="/stats/{{$stattype->url}}/fra">
				<img src="/images/flags/France.gif" title="All" class="flag">France</a>
				  <a class="dropdown-item" href="/stats/{{$stattype->url}}/gbr">
				<img src="/images/flags/Great-Britain.gif" title="All" class="flag">Great-Britain</a>
				  <a class="dropdown-item" href="/stats/{{$stattype->url}}/ita">
				<img src="/images/flags/Italy.gif" title="All" class="flag">Italy</a>
				<a class="dropdown-item" href="/stats/{{$stattype->url}}/nor">
				<img src="/images/flags/Norway.gif" title="All" class="flag">Norway</a>
				<a class="dropdown-item" href="/stats/{{$stattype->url}}/slo">
				<img src="/images/flags/Slovenia.gif" title="All" class="flag">Slovenia</a>
				<a class="dropdown-item" href="/stats/{{$stattype->url}}/spa">
				<img src="/images/flags/Spain.gif" title="All" class="flag">Spain</a>
				<a class="dropdown-item" href="/stats/{{$stattype->url}}/swi">
				<img src="/images/flags/Switzerland.gif" title="All" class="flag">Switzerland</a>
				</div>
			  </li>
			</ul>
		</nav>
	</div>	
</main>
@stop
