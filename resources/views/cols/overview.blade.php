@extends('layouts.master')

@section('title')
CyclingCols - My CyclingCols
@stop

@section('content')
<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light">My CyclingCols</h4>
	</div>
	<div class="container-fluid">
		<div class="card-deck w-100">
			<div class="card mb-3">
				<div class="card-header p-2">
					Overview
				</div>
				<div class="card-body p-2 font-weight-light text-small-90 text-center">
					<div class="kpi kpi-1">
						<span class="">{{$done_count}}</span>
					</div>
					<div class="mb-3">Cols Climbed</div>
					<div class="kpi kpi-2">
						<span class="">{{$done_year_count}}</span>
					</div>
					<div class="mb-3">This Year</div>
					<div class="kpi kpi-3">
						<span class="">{{$done_lastyear_count}}</span>
					</div>
					<div class="mb-1">Last Year</div>
				</div>
			</div>
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
			<div class="w-100 d-none d-sm-block d-lg-none"><!-- wrap every 2 on sm--></div>
			<div class="card mb-3">
				<div class="card-header p-2">
					<span>Last Cols Climbed</span>
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
@foreach($done as $done_)
					<div class="align-items-end d-flex">
						<div class="text-truncate">
							<img src="/images/flags/{{$done_->Country1}}.gif" title="{{$done_->Country1}}" class="flag mr-1">
	@if ($done_->Country2)
							<img src="/images/flags/{{$done_->Country2}}.gif" title="{{$done_->Country2}}" class="flag mr-1">
	@endif
							<a href="col/{{$done_->ColIDString}}">{{$done_->Col}}</a>
						</div>
						<div class="ml-auto text-small-75 text-right" style="flex: 0 0 65px;">{{Carbon\Carbon::parse($done_->pivot->CreatedAt)->format('d M Y')}}</div>
					</div>
@endforeach
				</div>
				<div class="card-footer text-muted d-flex align-items-center">
					<span class="text-small-75">{{$done_count}} cols climbed</span>
					<div class="ml-auto" tabindex="0" role="button" data-toggle="modal" data-target="#modal-first">
						<a href="/user/climbed"><i id="col-first-all" class="fas fas-grey fa-search-plus" title="show all"></i></a>
					</div>
				</div>
			</div>
			<!-- -->
			<div class="w-100 d-none d-lg-block"><!-- wrap every 3 on lg or larger--></div>
			<div class="card mb-3">
				<div class="card-header p-2">
					<span>Highest Rated</span>
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
@foreach($ratings as $rating)
					<div class="align-items-end d-flex">
						<div class="text-truncate">
							<img src="/images/flags/{{$rating->Country1}}.gif" title="{{$rating->Country1}}" class="flag mr-1">
	@if ($rating->Country2)
							<img src="/images/flags/{{$rating->Country2}}.gif" title="{{$rating->Country2}}" class="flag mr-1">
	@endif
							<a href="col/{{$rating->ColIDString}}">{{$rating->Col}}</a>
						</div>
						<div class="ml-auto text-small-75 text-right" style="flex: 0 0 90px;">
							<i class="col-rating no-pointer fas fa-star @if ($rating->pivot->Rating == 5) col-rating-yes @else col-rating-no @endif"></i>
							<i class="col-rating no-pointer fas fa-star @if ($rating->pivot->Rating >= 4) col-rating-yes @else col-rating-no @endif"></i>
							<i class="col-rating no-pointer fas fa-star @if ($rating->pivot->Rating >= 3) col-rating-yes @else col-rating-no @endif"></i>
							<i class="col-rating no-pointer fas fa-star @if ($rating->pivot->Rating >= 2) col-rating-yes @else col-rating-no @endif"></i>
							<i class="col-rating no-pointer fas fa-star col-rating-yes"></i>
						</div>
					</div>
@endforeach
				</div>
				<div class="card-footer text-muted d-flex align-items-center">
					<span class="text-small-75">{{$rating_count}} cols rated</span>
					<div class="ml-auto" tabindex="0" role="button" data-toggle="modal" data-target="#modal-first">
						<a href="/user/rated"><i id="col-first-all" class="fas fas-grey fa-search-plus" title="show all"></i></a>
					</div>
				</div>
			</div>
			<!-- -->
			<div class="w-100 d-none d-sm-block d-lg-none"><!-- wrap every 2 on sm--></div>
			<!--add some invisible cards to be sure last cards are of equal size-->
			<div class="card card-invisible"></div>
			<div class="card card-invisible"></div>
		</div>
	</div>
</main>
@stop