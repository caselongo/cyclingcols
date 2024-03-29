@extends('layouts.master')

@section('title')
CyclingCols - My CyclingCols
@stop

@section('content')
<script type="text/javascript">		
	$(document).ready(function() {
		getBanners("#ads","home",5,true);
	});
</script>

<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light d-inline">Popular Cols</h4>
	</div>	
	<nav class="navbar navbar-expand-sm navbar-light border-bottom border-top px-2 py-0" id="nav-stats">
		<ul class="navbar-nav">
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$year->Year}}</a>
			<div class="dropdown-menu">
@foreach ($years as $year_)
				<a class="dropdown-item font-weight-light" href="/athletes/cols/{{$country->URL}}/{{$year_->URL}}/{{$athlete->URL}}">
					<span>{{$year_->Year}}</span>
				</a>
@endforeach
			</div>
		  </li>			  
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$country->Country}}</a>
			<div class="dropdown-menu">
@foreach ($countries as $country_)
				<a class="dropdown-item font-weight-light" href="/athletes/cols/{{$country_->URL}}/{{$year->URL}}/{{$athlete->URL}}">
					<img src="/images/flags/{{$country_->Flag}}.gif" class="flag mr-1">{{$country_->Country}}
				</a>
@if ($country_->CountryID == 0)
				<div class="dropdown-divider"></div>
@endif
@endforeach
			</div>
		  </li>			  
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$athlete->Type}}</a>
			<div class="dropdown-menu">
@foreach ($athletes as $athlete_)
				<a class="dropdown-item font-weight-light" href="/athletes/cols/{{$country->URL}}/{{$year->URL}}/{{$athlete_->URL}}">
					<span>{{$athlete_->Type}}</span>
				</a>
@endforeach
			</div>
		  </li>
		</ul>
	</nav>

	<div class="container-fluid p-3">
		<div class="card-columns-2">	
			<div class="card mb-3">
				<div class="card-header p-2">
					Most Climbed Cols
					<span class="text-small-75">
@if ($year->URL != "all")
						{{$year->Title}}
@endif
@if ($country->URL != "eur")
						in {{$country->Country}}<img src="/images/flags/{{$country->Country}}.gif" class="flag mx-1">
@endif
@if ($athlete->URL != "all")
						{{$athlete->Title}}
@endif
					</span>
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
@foreach($set as $col)
					<div class="align-items-end d-flex align-items-baseline">
						<img src="/images/flags/{{$col->Country1}}.gif" class="flag mr-1">
	@if ($col->Country2)
						<img src="/images/flags/{{$col->Country2}}.gif" class="flag mr-1">
	@endif
						<div class="text-truncate">
							<a href="/col/{{$col->ColIDString}}">{{$col->Col}}</a>
						</div>
	@if ($col->climbedByMe())
						<i class="fas fa-check text-small-75 text-secondary ml-1" title="Climbed by you" data-toggle="tooltip"></i>
	@endif
						<div class="ml-auto text-small-75 text-right font-weight-light" style="flex: 0 0 45px;">
							{{$col->count}}
						</div>
					</div>
@endforeach
@if (count($set) == 0)
					<span class="text-small-75">Nothing to show here.</span>
@endif
				</div>
				<div class="card-footer text-muted px-3 pt-3">
					{{$set->links()}}
				</div><!--card-footer-->
			</div>
			<!-- -->	
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div id="ads" class="card card-invisible mb-3 text-center">
			</div>
		</div>
	</div>
</main>
@stop