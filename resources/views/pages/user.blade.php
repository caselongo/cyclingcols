@extends('layouts.master')

@section('title')
CyclingCols - My CyclingCols
@stop

@section('content')

<?php
	$isOwner = false;
	
	$user_ = Auth::user();
	
	if ($user_){
		$isOwner = ($user->id == $user_->id);	
	}
	
?>

<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light d-inline">Dashboard</h4>
		<span class="border rounded bg-light ml-1 px-2 py-1 font-weight-light">{{$user->name}}</span>
	</div>
	<div class="container-fluid">
		<div class="card-deck w-100">
			<div class="card mb-3">
				<div class="card-header p-2">
					Overview
				</div>
				<div class="card-body p-2 font-weight-light text-small-90 text-center">
					<div class="kpi kpi-1">
						<span class="">{{$climbed_count}}</span>
					</div>
					<div class="mb-3">Cols Climbed</div>
					<div class="kpi kpi-2">
						<span class="">{{$climbed_year_count}}</span>
					</div>
					<div class="mb-3">In {{date("Y")}}</div>
					<div class="kpi kpi-3">
						<span class="">{{$climbed_lastyear_count}}</span>
					</div>
					<div class="mb-1">In {{date("Y") - 1}}</div>
				</div>
			</div>
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<!-- -->
			<div class="card mb-3">
				<div class="card-header p-2 d-flex align-items-end">
					<div class="">Cols Climbed Per Country</div>
					<div class="text-small-90 text-right p-1 ml-auto" style="flex: 0 0 100px;">
						<div class="bar bar-year bar-rounded-left" style="width: 40px;">
							<span class="text-small-60 text-center float-left pl-1">{{date("Y")}}</span>
						</div>
						<div class="bar bar-total bar-rounded-right" style="width: 50px;">
							<span class="text-small-60 text-center float-left pl-1">total</span>
						</div>
					</div>
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
@foreach($countries as $country)
					<div class="align-items-end d-flex">
						<div class="text-truncate">
							<img src="/images/flags/{{$country->Country}}.gif" title="{{$country->Country}}" class="flag mr-1">
							{{$country->Country}}
						</div>
						<div class="ml-auto text-small-90 text-right" style="flex: 0 0 30px;">
							{{$country->col_count_user}}
						</div>
						<div class="text-small-90 text-right p-1" style="flex: 0 0 80px;">
	@if ($country->width_year > 0)
							<div class="bar bar-year
		@if ($country->width > 0)
			bar-rounded-left
		@else
			bar-rounded
		@endif		
							" style="width: {{$country->width_year * 70}}px;"></div>
	@endif
	@if ($country->width > 0)
							<div class="bar bar-total
		@if ($country->width_year > 0)
			bar-rounded-right
		@else
			bar-rounded
		@endif					
						" style="width: {{$country->width * 70}}px;"></div>
	@endif
						</div>
						<div class="text-small-75 text-right" style="flex: 0 0 60px;">
							of {{$country->col_count}}
						</div>
					</div>
@endforeach
				</div>
			</div>	
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="w-100 d-none d-sm-none d-md-block d-lg-none"><!-- wrap every 2 on md--></div>
			<!-- -->
			<div class="card mb-3">
				<div class="card-header p-2 d-flex align-items-center">
					<span>Cols Most Recently Climbed</span>
					<div class="ml-auto" tabindex="0" role="button" data-toggle="modal" data-target="#modal-first">
						<a href="/athlete/{{$user->id}}/cols/eur/climbed"><i id="col-first-all" class="fas fas-grey fa-search-plus" title="Show all"></i></a>
					</div>
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
@if (count($climbed) == 0)
					<span class="text-small-75">No cols climbed yet.</span>
@else
	@foreach($climbed as $climbed_)
					<div class="align-items-end d-flex">
						<div class="text-truncate">
							<img src="/images/flags/{{$climbed_->Country1}}.gif" title="{{$climbed_->Country1}}" class="flag mr-1">
		@if ($climbed_->Country2)
							<img src="/images/flags/{{$climbed_->Country2}}.gif" title="{{$climbed_->Country2}}" class="flag mr-1">
		@endif
							<a href="/col/{{$climbed_->ColIDString}}">{{$climbed_->Col}}</a>
						</div>
		@if ($isOwner)
						<div class="col-climbed-date ml-auto text-small-75 text-right" style="flex: 0 0 75px;" data-colidstring="{{$climbed_->ColIDString}}">
		@else
						<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
		@endif
		@if ($climbed_->pivot->ClimbedAt)
							{{Carbon\Carbon::parse($climbed_->pivot->ClimbedAt)->format('d M Y')}}
		@elseif ($isOwner)
							add date
		@else
							unknown
		@endif
						</div>
					</div>
	@endforeach
@endif
				</div>
			</div>
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="w-100 d-none d-lg-block"><!-- wrap every 3 on lg or larger--></div>
			<!-- -->
			<div class="card mb-3">
				<div class="card-header p-2 d-flex align-items-center">
					<span>Cols Most Recently Claimed</span>
					<div class="ml-auto" tabindex="0" role="button" data-toggle="modal" data-target="#modal-first">
						<a href="/athlete/{{$user->id}}/cols/eur/claimed"><i id="col-first-all" class="fas fas-grey fa-search-plus" title="Show all"></i></a>
					</div>
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
@if (count($claimed) == 0)
					<span class="text-small-75">No cols claimed yet.</span>
@else
	@foreach($claimed as $claimed_)
					<div class="align-items-end d-flex">
						<div class="text-truncate">
							<img src="/images/flags/{{$claimed_->Country1}}.gif" title="{{$claimed_->Country1}}" class="flag mr-1">
		@if ($claimed_->Country2)
							<img src="/images/flags/{{$claimed_->Country2}}.gif" title="{{$claimed_->Country2}}" class="flag mr-1">
		@endif
							<a href="/col/{{$claimed_->ColIDString}}">{{$claimed_->Col}}</a>
						</div>
						<div class="ml-auto text-small-75 text-right" style="flex: 0 0 70px;">
							{{Carbon\Carbon::parse($claimed_->pivot->CreatedAt)->format('d M Y')}}
						</div>
					</div>
	@endforeach
@endif
				</div>
			</div>
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="w-100 d-none d-sm-none d-md-block d-lg-none"><!-- wrap every 2 on md--></div>
			<!-- -->
			<div class="card mb-3 text-center">
				<div class="card-body p-0 font-weight-light text-small-90">
					<div class="p-2 border-bottom">
						<h6 class="font-weight-light m-0">Map</h6>
					</div>
					<div class="p-2">
						<div>Explore your cols in a map <a href="/map">here</a>.</div>
						<div>Make sure this checkbox is turned on and zoom in.</div>
						<div class="d-flex justify-content-around mt-1">
							<div class="command leaflet-control"><div class="leaflet-bar climbed-control d-flex align-items-center justify-content-around" title="Show only climbed cols"><a style="outline: none;"><i class="fas fa-check climbed-control-checked"></i></a></div></div>
						</div>
					</div>
@if ($isOwner)
					<div class="p-2 border-top border-bottom">
						<h6 class="font-weight-light m-0">Strava</h6>
					</div>
					<div class="p-2 text-center">
						<div>Initialize or update your cols list with Strava</div>
						<div class="p-1">
							<a class="btn btn-primary" href="/strava/connect">
								Connect with Strava
                            </a>
						</div>
						<span class="text-small-75">
	@if (!$user->strava_last_updated_at)
							(not done yet)
	@else
							(last time done: {{Carbon\Carbon::parse($user->strava_last_updated_at)->format('d M Y h:m:s')}})
	@endif
						</span>
					</div>
@endif
				</div>
			</div>
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="card mb-3">
			<!-- -->
				<div class="card-header p-2 d-flex align-items-center">
					<span>Highest Cols Climbed</span>
					<div class="ml-auto" tabindex="0" role="button" data-toggle="modal" data-target="#modal-first">
						<a href="/athlete/{{$user->id}}/cols/eur/elevation"><i id="col-first-all" class="fas fas-grey fa-search-plus" title="Show all"></i></a>
					</div>
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
@if (count($highest) == 0)
					<span class="text-small-75">No cols claimed yet.</span>
@else
	@foreach($highest as $highest_)
					<div class="align-items-end d-flex">
						<div class="text-truncate">
							<img src="/images/flags/{{$highest_->Country1}}.gif" title="{{$highest_->Country1}}" class="flag mr-1">
		@if ($highest_->Country2)
							<img src="/images/flags/{{$highest_->Country2}}.gif" title="{{$highest_->Country2}}" class="flag mr-1">
		@endif
							<a href="/col/{{$highest_->ColIDString}}">{{$highest_->Col}}</a>
						</div>
						<div class="ml-auto text-small-75 text-centre badge badge-elevation font-weight-light" style="flex: 0 0 45px;">
							{{$highest_->Height}}m
						</div>
					</div>
	@endforeach
@endif
				</div>
			</div>			
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="w-100 d-none d-sm-none d-md-block d-lg-none"><!-- wrap every 2 on md--></div>
			<div class="w-100 d-none d-lg-block"><!-- wrap every 3 on lg or larger--></div>
			<!-- -->
			<!--add some invisible cards to be sure last cards are of equal size-->
			<div class="card card-invisible"></div>
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="card card-invisible"></div>
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="w-100 d-none d-sm-none d-md-block d-lg-none"><!-- wrap every 2 on md--></div>
		</div>
	</div>
</main>
@stop