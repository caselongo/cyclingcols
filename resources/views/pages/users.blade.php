@extends('layouts.master')

@section('title')
CyclingCols - My CyclingCols
@stop

@section('content')
<script type="text/javascript">		
	$(document).ready(function() {
		getBanners("#ads","home",4,true);
	});
</script>

<?php
	$user = Auth::user();
?>

<main role="main" class="bd-content">
    <div class="header px-4 py-3 row m-0">
		<div class="col-xs-12 col-md-4 px-0 pt-1">
			<h4 class="font-weight-light d-inline">All Athletes</h4>
		</div>
		<div class="col-xs-12 col-md-4 px-0 pt-1"></div>
		<div class="col-xs-12 col-md-4 px-0 pr-sm-3 pt-1">
			<input id="search-athlete" class="search-input form-control mr-sm-2 px-2 py-1 font-weight-light" type="search" placeholder="Search an athlete...">
			<div id="search-athlete-wrapper" class="search-input-wrapper ui-front"></div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="card-deck w-100">
			<div class="card mb-3">
				<div class="card-header p-2">
					Overview
				</div>
				<div class="card-body p-0 font-weight-light text-small-90 text-center">
					<div class="pt-2">
						<div class="kpi kpi-1">
							<span class="">{{$total}}</span>
						</div>
						<div>Cols Climbed</div>
					</div>
					<div class="pt-2">
						<div class="kpi kpi-2">
							<span class="">{{$total_year}}</span>
						</div>
						<div>In {{date("Y")}}</div>
					</div>
					<div class="pt-2">
						<div class="kpi kpi-3">
							<span class="">{{$total_lastyear}}</span>
						</div>
						<div class="mb-1">In {{date("Y") - 1}}</div>
					</div>
					<div class="border-top"></div>
					<div class="p-2 d-flex align-items-center">
						<div class="kpi kpi-4">
							<span class="">{{$cols}}</span>
						</div>
						<div class="ml-2">
							Different Cols Climbed
							<span class="text-small-75">(of {{App\Col::count()}})</span>
						</div>
					</div>
					<div class="border-top"></div>
					<div class="p-2 d-flex align-items-center">
						<div class="kpi kpi-4">
							<span class="">{{$users}}</span>
						</div>
						<div class="ml-2">Athletes Registered</div>
					</div>
					<div class="px-2 pb-2 text-small-75 text-left">
						You are following <span class="font-weight-normal">{{$users_following}}</span> of them, while <span class="font-weight-normal">{{$users_followed}}</span> of them {{$users_followed == 1 ? "is" : "are"}} following you.
					</div>
				</div>
			</div>
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<!-- -->
			<div class="card mb-3">
				<div class="card-header p-2 d-flex justify-content-between align-items-baseline">
					<div>
						<span>Last Cols Climbed</span>
						<span class="text-small-75 text-secondary">by athletes you are following</span>
					</div>
				</div>
				<div class="card-body p-0 pb-1 font-weight-light text-small-90">
<?php
if (count($following) == 0){
?>
					<div class="text-small-75 p-2">Nothing to show here yet. Either you are not following anyone or the athletes you are following did not climb any cols.</div>
<?php
} else {

	$userid = 0;
	$date = "";
	$count = 0;
	$count_col = 0;
	$count_date = 0;
	$count_user = 0;

	foreach ($following as $following_){
		$date_ = getHumanDate($following_->ClimbedAt);
		$userid_ = $following_->id;
		
		if ($count > 0 && ($date_ != $date || $userid_ != $userid)){
			
			if ($count_date + $count_date > 15) break;
			
?>
		@if ($count_col > 1)
						<div class="ml-1 text-small-75">and {{$count_col - 1}} other col{{$count_col - 1 > 1 ? 's' : ''}}</div>
		@endif
					</div>		
<?php	
		}	
		
		if ($date_ != $date){
?>
					<div class="px-2 py-1 {{$count > 0 ? 'border-top mt-1' : ''}}">
						<div class="text-small-75 font-weight-light m-0">{{$date_}}</div>
					</div>
<?php
			
			$count_date++;
			$userid = 0;
			$count_col = 0;
		}
		
		if ($userid_ != $userid){
?>
					<div class="px-2 py-0 align-items-end d-flex">
						<div>
							<a href="/athlete/{{$following_->slug}}">{{$following_->name}}</a>
							<span class="text-small-75">climbed</span>
						</div>
						<img src="/images/flags/{{$following_->Country1}}.gif" title="{{$following_->Country1}}" data-toggle="tooltip" class="flag mx-1">
		@if ($following_->Country2)
						<img src="/images/flags/{{$following_->Country2}}.gif" title="{{$following_->Country2}}" data-toggle="tooltip" class="flag mr-1">
		@endif					
						<div class="text-truncate">
							<a href="/col/{{$following_->ColIDString}}">{{$following_->Col}}</a>
						</div>		
<?php				
			$count_user++;
			$date = $date_;	
			$userid = $userid_;	
			$count_col = 0;
		}
		
		$count++;			
		$count_col++;
	}
		
	if ($count > 0)	{
?>
		@if ($count_col > 1)
						<div class="ml-1 text-small-75">and {{$count_col - 1}} other col {{$count_col - 1 > 1 ? 's' : ''}}</div>
		@endif

					</div>	
<?php
	}
}
?>
				</div>
			</div>
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="w-100 d-none d-sm-none d-md-block d-lg-none"><!-- wrap every 2 on md--></div>
			<!-- -->
			<div class="card mb-3">
				<div class="card-header p-2 d-flex align-items-center">
					<span>Most Cols Climbed</span>
				</div>
				<div class="card-body p-0 font-weight-light text-small-90">
					<div class="p-2 border-bottom d-flex align-items-center">
						<h6 class="font-weight-light m-0">Alltime</h6>
						<div class="ml-auto" tabindex="0" role="button">
							<a href="/athletes/athletes/eur/all/all"><i class="fas fas-grey fa-search-plus" title="Show all" data-toggle="tooltip"></i></a>
						</div>
					</div>
					<div class="p-2">
<?php
$user_found = false;

foreach($users_most as $users_most_) {
	if ($users_most_->id == $user->id) $user_found = true;

?>
						<div class="align-items-baseline d-flex">
							<div class="text-truncate">
								<a href="/athlete/{{$users_most_->slug}}">
									<span class="">{{$users_most_->name}}</span>
								</a>
							</div>
							<div class="text-primary text-small-75 text-right" style="flex: 0 0 15px;">
	@if ($users_most_->id == $user->id)
								<i class="fas fa-user" title="That's you!" data-toggle="tooltip"></i> 
	@elseif ($users_most_->followedByMe())
								<i class="fas fa-check" title="Following" data-toggle="tooltip"></i> 
	@endif
							</div>
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
								{{$users_most_->cols}}
							</div>
						</div>
<?php
}
?>
@if (!$user_found)
						<div class="align-items-baseline d-flex">
							<div class="text-truncate mt-1 border-top">
								<a href="/athlete/{{$user->slug}}">
									<span class="">{{$user->name}}</span>
								</a>
							</div>
							<div class="text-primary text-small-75 text-right" style="flex: 0 0 15px;">
								<i class="fas fa-user" title="That's you!" data-toggle="tooltip"></i> 
							</div>
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
								{{$users_most_me}}
							</div>
						</div>	
@endif
					</div>
					<div class="p-2 border-bottom border-top d-flex align-items-center">
						<h6 class="font-weight-light m-0">In {{date("Y")}}</h6>
						<div class="ml-auto" tabindex="0" role="button">
							<a href="/athletes/athletes/eur/{{date('Y')}}/all"><i class="fas fas-grey fa-search-plus" title="Show all" data-toggle="tooltip"></i></a>
						</div>
					</div>
					<div class="p-2">
<?php
$user_found = false;

foreach($users_most_year as $users_most_year_) {
	if ($users_most_year_->id == $user->id) $user_found = true;

?>
						<div class="align-items-baseline d-flex">
							<div class="text-truncate">
								<a href="/athlete/{{$users_most_year_->slug}}">
									<span class="">{{$users_most_year_->name}}</span>
								</a>
							</div>
							<div class="text-primary text-small-75 text-right" style="flex: 0 0 15px;">
	@if ($users_most_year_->id == $user->id)
								<i class="fas fa-user" title="That's you!" data-toggle="tooltip"></i> 
	@elseif ($users_most_year_->followedByMe())
								<i class="fas fa-check" title="Following" data-toggle="tooltip"></i> 
	@endif
							</div>
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
								<span class="">{{$users_most_year_->cols}}</span>
							</div>
						</div>
<?php
}
?>
@if (!$user_found)
						<div class="align-items-baseline d-flex">
							<div class="text-truncate mt-1 border-top">
								<a href="/athlete/{{$user->slug}}">
									<span class="">{{$user->name}}</span>
								</a>
							</div>
							<div class="text-primary text-small-75 text-right" style="flex: 0 0 15px;">
								<i class="fas fa-user" title="That's you!" data-toggle="tooltip"></i> 
							</div>
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
								{{$users_most_year_me}}
							</div>
						</div>	
@endif
					</div>
					<div class="p-2 border-bottom border-top d-flex align-items-center">
						<h6 class="font-weight-light m-0">Following</h6>
						<div class="ml-auto" tabindex="0" role="button">
							<a href="/athletes/athletes/eur/all/following"><i class="fas fas-grey fa-search-plus" title="Show all" data-toggle="tooltip"></i></a>
						</div>
					</div>
					<div class="p-2">
<?php
$user_found = false;

foreach($users_most_following as $users_most_following_) {
	if ($users_most_following_->id == $user->id) $user_found = true;

?>
						<div class="align-items-baseline d-flex">
							<div class="text-truncate">
								<a href="/athlete/{{$users_most_following_->slug}}">{{$users_most_following_->name}}</a>
							</div>
							<div class="text-primary text-small-75 text-right" style="flex: 0 0 15px;">
	@if ($users_most_following_->id == $user->id)
								<i class="fas fa-user" title="That's you!" data-toggle="tooltip"></i> 
	@elseif ($users_most_following_->followedByMe())
								<i class="fas fa-check" title="Following" data-toggle="tooltip"></i> 
	@endif
							</div>
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
								{{$users_most_following_->cols}}
							</div>
						</div>
<?php
}
?>
@if (!$user_found)
						<div class="align-items-baseline d-flex">
							<div class="text-truncate mt-1 border-top">
								<a href="/athlete/{{$user->slug}}">
									<span class="">{{$user->name}}</span>
								</a>
							</div>
							<div class="text-primary text-small-75 text-right" style="flex: 0 0 15px;">
								<i class="fas fa-user" title="That's you!" data-toggle="tooltip"></i> 
							</div>
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
								{{$users_most_me}}
							</div>
						</div>	
@endif
					</div>
				</div>
			</div>
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="w-100 d-none d-lg-block"><!-- wrap every 3 on lg or larger--></div>
			<!-- -->
			<div class="card mb-3">
				<div class="card-header p-2 d-flex justify-content-between align-items-baseline">
					<div>
						<span>Most Climbed Col Per Country</span>
					</div>
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
@foreach ($countries as $country)
					<div class="align-items-end d-flex">
						<img src="/images/flags/{{$country->Country}}.gif" title="{{$country->Country}}" class="flag mr-1">
						<div class="text-truncate">
	@if ($country->Col)
							<a href="/col/{{$country->ColIDString}}">{{$country->Col}}</a>
	@else
							<span class="text-small-75">none</span>
	@endif
						</div>
						<div class="ml-auto text-small-75 text-right" style="flex: 0 0 45px;">{{$country->Users}}</div>
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
					<span>Most Climbed Cols</span>
				</div>
				<div class="card-body p-0 font-weight-light text-small-90">
					<div class="p-2 border-bottom d-flex align-items-center">
						<h6 class="font-weight-light m-0">Alltime</h6>
						<div class="ml-auto" tabindex="0" role="button">
							<a href="/athletes/cols/eur/all/all"><i class="fas fas-grey fa-search-plus" title="Show all" data-toggle="tooltip"></i></a>
						</div>
					</div>
					<div class="p-2">
@foreach($cols_most as $cols_most_)
						<div class="align-items-end d-flex">
							<div class="text-truncate">
								<img src="/images/flags/{{$cols_most_->Country1}}.gif" title="{{$cols_most_->Country1}}" data-toggle="tooltip" class="flag mr-1">
	@if ($cols_most_->Country2)
								<img src="/images/flags/{{$cols_most_->Country2}}.gif" title="{{$cols_most_->Country2}}" data-toggle="tooltip" class="flag mr-1">
	@endif
								<a href="/col/{{$cols_most_->ColIDString}}">{{$cols_most_->Col}}</a>
							</div>
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
								{{$cols_most_->users}}
							</div>
						</div>
@endforeach
					</div>
					<div class="p-2 border-bottom border-top d-flex align-items-center">
						<h6 class="font-weight-light m-0">In {{date("Y")}}</h6>
						<div class="ml-auto" tabindex="0" role="button">
							<a href="/athletes/cols/eur/{{date('Y')}}/all"><i class="fas fas-grey fa-search-plus" title="Show all" data-toggle="tooltip"></i></a>
						</div>
					</div>
					<div class="p-2">
@foreach($cols_most_year as $cols_most_year_)
						<div class="align-items-end d-flex">
							<div class="text-truncate">
								<img src="/images/flags/{{$cols_most_year_->Country1}}.gif" title="{{$cols_most_year_->Country1}}" data-toggle="tooltip" class="flag mr-1">
	@if ($cols_most_year_->Country2)
								<img src="/images/flags/{{$cols_most_year_->Country2}}.gif" title="{{$cols_most_year_->Country2}}" data-toggle="tooltip" class="flag mr-1">
	@endif
								<a href="/col/{{$cols_most_year_->ColIDString}}">{{$cols_most_year_->Col}}</a>
							</div>
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
							{{$cols_most_year_->users}}
							</div>
						</div>
@endforeach
					</div>
					<div class="p-2 border-bottom border-top d-flex align-items-center">
						<h6 class="font-weight-light m-0">Following</h6>
						<div class="ml-auto" tabindex="0" role="button">
							<a href="/athletes/cols/eur/all/following"><i class="fas fas-grey fa-search-plus" title="Show all" data-toggle="tooltip"></i></a>
						</div>
					</div>
					<div class="p-2">
@foreach($cols_most_following as $cols_most_following_)
						<div class="align-items-end d-flex">
							<div class="text-truncate">
								<img src="/images/flags/{{$cols_most_following_->Country1}}.gif" title="{{$cols_most_following_->Country1}}" data-toggle="tooltip" class="flag mr-1">
	@if ($cols_most_following_->Country2)
								<img src="/images/flags/{{$cols_most_following_->Country2}}.gif" title="{{$cols_most_following_->Country2}}" data-toggle="tooltip" class="flag mr-1">
	@endif
								<a href="/col/{{$cols_most_following_->ColIDString}}">{{$cols_most_following_->Col}}</a>
							</div>
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
							{{$cols_most_following_->users}}
							</div>
						</div>
@endforeach
					</div>
				</div>
			</div>
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<!--add some invisible cards to be sure last cards are of equal size-->
			<div id="ads" class="card card-invisible text-center mb-3"></div>
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="w-100 d-none d-sm-none d-md-block d-lg-none"><!-- wrap every 2 on md--></div>
			<div class="w-100 d-none d-lg-block"><!-- wrap every 3 on lg or larger--></div>
		</div>
	</div>
</main>
@stop