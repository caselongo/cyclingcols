@extends('layouts.master')

@section('title')
CyclingCols - My CyclingCols
@stop

@section('content')

<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light">My CyclingCols</h4>
	</div>	
	<nav class="navbar navbar-expand-sm navbar-light border-bottom border-top px-2 py-0" id="nav-stats">
		<ul class="navbar-nav">
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$sorttype->SortType}}</a>
			<div class="dropdown-menu">
@foreach ($sorttypes as $sorttype_)
				<a class="dropdown-item font-weight-light" href="/user/cols/{{$country->URL}}/{{$sorttype_->URL}}">
					<span>{{$sorttype_->SortType}}</span>
				</a>
@endforeach
			</div>
		  </li>			  
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$country->Country}}</a>
			<div class="dropdown-menu">
@foreach ($countries as $country_)
				<a class="dropdown-item font-weight-light" href="/user/cols/{{$country_->URL}}/{{$sorttype->URL}}">
					<img src="/images/flags/{{$country_->Flag}}.gif" class="flag mr-1">{{$country_->Country}}
				</a>
@if ($country_->CountryID == 0)
					<div class="dropdown-divider"></div>
@endif
@endforeach
			</div>
		  </li>
		</ul>
	</nav>

	<div class="container-fluid p-3">
		<div class="card-deck w-100">	
			<div class="card mb-3">
				<div class="card-header p-2">
					<span>Cols Most Recently {{$sorttype->SortType}}</span>
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
@foreach($cols as $col)
					<div class="align-items-end d-flex">
						<div class="text-truncate">
							<img src="/images/flags/{{$col->Country1}}.gif" title="{{$col->Country1}}" class="flag mr-1">
	@if ($col->Country2)
							<img src="/images/flags/{{$col->Country2}}.gif" title="{{$col->Country2}}" class="flag mr-1">
	@endif
							<a href="/col/{{$col->ColIDString}}">{{$col->Col}}</a>
						</div>
	@if ($sorttype->URL == "climbed")
						<div class="col-climbed-date ml-auto text-small-75 text-right" style="flex: 0 0 75px;" data-colidstring="{{$col->ColIDString}}">
	@else
						<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
	@endif
							{{Carbon\Carbon::parse($col->pivot->{$sorttype->Field})->format('d M Y')}}
						</div>
					</div>
@endforeach
@if (count($cols) == 0)
					<span class="text-small-75">No cols in {{$country->Country}} climbed yet.</span>
@endif
				</div>
			</div>
			<!-- -->	
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="card mb-3">
			</div>
		</div>
	</div>
</main>
@stop