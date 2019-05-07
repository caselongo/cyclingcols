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

<?php
	$isOwner = false;
	
	$user_ = Auth::user();
	
	if ($user_){
		$isOwner = ($user->id == $user_->id);	
	}
?>

<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light d-inline">Cols</h4>
		<span class="border rounded bg-light ml-1 px-2 py-1 font-weight-light">{{$user->name}}</span>
	</div>	
	<nav class="navbar navbar-expand-sm navbar-light border-bottom border-top px-2 py-0" id="nav-stats">
		<ul class="navbar-nav">
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$sorttype->SortType}}</a>
			<div class="dropdown-menu">
@foreach ($sorttypes as $sorttype_)
				<a class="dropdown-item font-weight-light" href="/athlete/{{$user->slug}}/cols/{{$country->URL}}/{{$sorttype_->URL}}">
					<span>{{$sorttype_->SortType}}</span>
				</a>
@endforeach
			</div>
		  </li>			  
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$country->Country}}</a>
			<div class="dropdown-menu">
@foreach ($countries as $country_)
				<a class="dropdown-item font-weight-light" href="/athlete/{{$user->slug}}/cols/{{$country_->URL}}/{{$sorttype->URL}}">
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
		<div class="card-columns-2">	
			<div class="card mb-3">
				<div class="card-header p-2">
					<span>All Cols - {{$sorttype->SortType}}</span>
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
					<div class="align-items-end d-flex justify-content-begin">
						<div class="ml-auto text-small-75 text-centre border-bottom" style="flex: 0 0 45px;">elevation</div>
						<div class="ml-1 text-small-75 text-right border-bottom" style="flex: 0 0 70px;">claimed</div>
						<div class="ml-1 text-small-75 text-right border-bottom" style="flex: 0 0 75px;">climbed</div>
						<div class="ml-1 text-small-75 text-centre font-weight-light" style="flex: 0 0 15px;"></div>
					</div>
@foreach($cols as $col)
					<div class="align-items-end d-flex">
						<img src="/images/flags/{{$col->Country1}}.gif" title="{{$col->Country1}}" data-toggle="tooltip" class="flag mr-1">
	@if ($col->Country2)
						<img src="/images/flags/{{$col->Country2}}.gif" title="{{$col->Country2}}" data-toggle="tooltip" class="flag mr-1">
	@endif
						<div class="text-truncate">
							<a href="/col/{{$col->ColIDString}}">{{($col->{$sorttype->NameField})}}</a>
						</div>
						<div class="ml-auto text-small-75 text-centre badge badge-elevation font-weight-light" style="flex: 0 0 45px;">
							{{$col->Height}}m
						</div>
						<div class="ml-1 text-small-75 text-right" style="flex: 0 0 70px;">
							{{getHumanDate($col->pivot->CreatedAt)}}
						</div>
	@if ($isOwner)
						<div class="col-climbed-date ml-1 text-small-75 text-right" style="flex: 0 0 75px;" data-colidstring="{{$col->ColIDString}}" data-date="{{getDate_dMY($col->pivot->ClimbedAt)}}">
	@else
						<div class="ml-1 text-small-75 text-right" style="flex: 0 0 75px;">
	@endif
	@if ($col->pivot->ClimbedAt)
							{{getHumanDate($col->pivot->ClimbedAt)}}
	@elseif ($isOwner)
							add date
	@else
							unknown
	@endif
						</div>
						<div class="ml-1 text-small-75 text-centre font-weight-light" style="flex: 0 0 15px;">
@if ($col->pivot->StravaActivityIDs != null)
							<div class="pointer" onclick="openActivities('{{$col->pivot->StravaActivityIDs}}'); return false;">
								<img src="/images/strava.png"/ class="w-100 strava-icon">
							</div>
@endif
						</div>
					</div>
@endforeach
@if (count($cols) == 0)
					<span class="text-small-75">No cols in {{$country->Country}} climbed yet.</span>
@endif
				</div>
				<div class="card-footer text-muted px-3 pt-3">
					{{$cols->links()}}
				</div><!--card-footer-->
			</div>
			<!-- -->	
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>		
			<div class="card card-invisible mb-3">		
				<div class="card-deck">
<?php
	function showRandomCol($col,$header,$count){
		
		$coverPhoto = "_Dummy";
		$coverPhotoPosition = 50;
		if ($col->CoverPhotoPosition){
			$coverPhoto = $col->ColIDString;
			$coverPhotoPosition = $col->CoverPhotoPosition;
		}
		
		$dateString = getHumanDate(Carbon\Carbon::parse($col->pivot->ClimbedAt));
?>			
		@if ($count > 0 && $count % 2 == 0)
					<div class="w-100 d-block"><!-- wrap every 2--></div>
		@endif	
					<div class="card mb-3">
						<div class="card-header p-2">
							<span>{{$header}}</span>
						</div>					
						<div class="card-img-top card-img-background" onclick='goToCol("{{$col->ColIDString}}")' style='background-position: 50% {{$coverPhotoPosition}}%; background-image: url("{{env('IMAGES_PATH','\images')}}/covers/small/{{$coverPhoto}}.jpg")'>
						</div>	
						<div class="card-body p-0">
							<h6 class="card-title p-2 m-0 font-weight-light">
								<img src="/images/flags/{{$col->Country1}}.gif" title="{{$col->Country1}}" data-toggle="tooltip" class="flag">
		@if ($col->Country2)
								<img src="/images/flags/{{$col->Country2}}.gif" title="{{$col->Country2}}" data-toggle="tooltip" class="flag flag2">
		@endif
								<a href="/col/{{$col->ColIDString}}">{{$col->Col}}</a>
								<span class="badge badge-elevation font-weight-light text-small-70">{{$col->Height}}m</span>
							</h6>
						</div>
						<div class="card-footer text-muted">
							<span class="text-small-75">Climbed At {{$dateString}}</span>
						</div>				
					</div>
<?php	
	}

	$count = 0;
	
	$years = [];
	$count_ = 0;

	foreach($diff_years as $diff_years_){
		$years_ = $diff_years_->years;
		
		if (!in_array($years_,$years)){
			switch($years_){
				case 0:
					$ago = "Climbed Today";
					break;
				case 1:
					$ago = "Climbed  1 Year Ago";
					break;
				default:
					$ago = "Climbed " . $years_ . " Years Ago";
					break;
			}

			showRandomCol($diff_years_, $ago, $count);

			$years[] = $years_;
			$count++;
			$count_++;
			
			if ($count_ >= 3) break;
		}
	}

	$days = [];
	$count_ = 0;

	foreach($diff_days as $diff_days_){
		$days_ = $diff_days_->days;
		
		if (!in_array($days_,$days)){
			$ago = "Climbed " . $days_ . " Days Ago";

			showRandomCol($diff_days_, $ago, $count);

			$days[] = $days_;
			$count++;
			$count_++;
			
			if ($count_ >= 3) break;
		}
	}

	$months = [];
	$count_ = 0;

	foreach($diff_months as $diff_months_){
		$months_ = $diff_months_->months;
		if ($months_ < 0) $months_ += 12;
		
		if (!in_array($months_,$months)){
			$ago = "Climbed " . $months_ . " Months Ago";

			showRandomCol($diff_months_, $ago, $count);

			$months[] = $months_;
			$count++;
			$count_++;
			
			if ($count_ >= 3) break;
		}
	}
?>
	@if ($count % 2 > 0)
					<div class="card card-invisible"></div>
	@endif		
					<div class="w-100 d-block"><!-- wrap --></div>
					<div id="ads" class="card card-invisible mb-3 text-center">
					</div>
				</div><!--card-deck-->
			</div><!-- card -->
		</div><!-- card-columns -->
	</div><!-- container -->
</main>
@stop