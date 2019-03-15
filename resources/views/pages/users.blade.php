@extends('layouts.master')

@section('title')
CyclingCols - My CyclingCols
@stop

@section('content')

<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light d-inline">All Athletes</h4>
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
						<div>{{date("Y")}}</div>
					</div>
					<div class="pt-2">
						<div class="kpi kpi-3">
							<span class="">{{$total_lastyear}}</span>
						</div>
						<div class="mb-1">{{date("Y") - 1}}</div>
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
				</div>
			</div>
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<!-- -->
			<div class="card mb-3">
				<div class="card-header p-2 d-flex align-items-center">
					<span>Most Cols Climbed</span>
					<div class="ml-auto" tabindex="0" role="button" data-toggle="modal" data-target="#modal-first">
						<a href="/athletes/mostcols"><i id="col-first-all" class="fas fas-grey fa-search-plus" title="Show all"></i></a>
					</div>
				</div>
				<div class="card-body p-0 font-weight-light text-small-90">
					<div class="p-2 border-bottom">
						<h6 class="font-weight-light m-0">Alltime</h6>
					</div>
					<div class="p-2">
@foreach($users_most as $users_most_)
						<div class="align-items-end d-flex">
							<div class="text-truncate">
								<a href="/athlete/{{$users_most_->id}}">{{$users_most_->name}}</a>
							</div>
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
								{{$users_most_->cols}}
							</div>
						</div>
@endforeach
					</div>
					<div class="p-2 border-bottom border-top">
						<h6 class="font-weight-light m-0">{{date("Y")}}</h6>
					</div>
					<div class="p-2">
@foreach($users_most_year as $users_most_year_)
						<div class="align-items-end d-flex">
							<div class="text-truncate">
								<a href="/athlete/{{$users_most_year_->id}}">{{$users_most_year_->name}}</a>
							</div>
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
								{{$users_most_year_->cols}}
							</div>
					</div>
@endforeach
					</div>
				</div>
			</div>
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="w-100 d-none d-sm-none d-md-block d-lg-none"><!-- wrap every 2 on md--></div>
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
						<div class="text-truncate">
							<img src="/images/flags/{{$country->Country}}.gif" title="{{$country->Country}}" class="flag mr-1">
	@if ($country->Col)
							<a href="/col/{{$country->ColIDString}}">{{$country->Col}}</a>
	@else
							<span class="text-small-75">none</span>
	@endif
						</div>
						<div class="ml-auto text-small-75 text-right" style="flex: 0 0 45px;">{{$country->Users}}</div>
					</div>			
@endforeach
				</div><!--card-body-->
			</div><!--card-->
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="w-100 d-none d-sm-none d-md-block d-lg-none"><!-- wrap every 2 on md--></div>
			<div class="w-100 d-none d-lg-block"><!-- wrap every 3 on lg or larger--></div>
			<!-- -->
			<div class="card mb-3">
				<div class="card-header p-2 d-flex align-items-center">
					<span>Most Climbed Cols</span>
					<div class="ml-auto" tabindex="0" role="button" data-toggle="modal" data-target="#modal-first">
						<a href="/athletes/colsmost"><i id="col-first-all" class="fas fas-grey fa-search-plus" title="Show all"></i></a>
					</div>
				</div>
				<div class="card-body p-0 font-weight-light text-small-90">
					<div class="p-2 border-bottom">
						<h6 class="font-weight-light m-0">Alltime</h6>
					</div>
					<div class="p-2">
@foreach($cols_most as $cols_most_)
						<div class="align-items-end d-flex">
							<div class="text-truncate">
								<img src="/images/flags/{{$cols_most_->Country1}}.gif" title="{{$cols_most_->Country1}}" class="flag mr-1">
	@if ($cols_most_->Country2)
								<img src="/images/flags/{{$cols_most_->Country2}}.gif" title="{{$cols_most_->Country2}}" class="flag mr-1">
	@endif
								<a href="/col/{{$cols_most_->ColIDString}}">{{$cols_most_->Col}}</a>
							</div>
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
								{{$cols_most_->users}}
							</div>
						</div>
@endforeach
					</div>
					<div class="p-2 border-bottom border-top">
						<h6 class="font-weight-light m-0">{{date("Y")}}</h6>
					</div>
					<div class="p-2">
@foreach($cols_most_year as $cols_most_year_)
						<div class="align-items-end d-flex">
							<div class="text-truncate">
								<img src="/images/flags/{{$cols_most_year_->Country1}}.gif" title="{{$cols_most_year_->Country1}}" class="flag mr-1">
	@if ($cols_most_year_->Country2)
								<img src="/images/flags/{{$cols_most_year_->Country2}}.gif" title="{{$cols_most_year_->Country2}}" class="flag mr-1">
	@endif
								<a href="/col/{{$cols_most_year_->ColIDString}}">{{$cols_most_year_->Col}}</a>
							</div>
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
							{{$cols_most_year_->users}}
							</div>
						</div>
@endforeach
					</div>
				</div>
			</div>
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="w-100 d-none d-sm-none d-md-block d-lg-none"><!-- wrap every 2 on md--></div>
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