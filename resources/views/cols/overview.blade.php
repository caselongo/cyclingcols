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
					Cols Claimed Per Country
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
						<div class="text-small-90 text-right" style="flex: 0 0 80px;">
	@if ($country->width > 0)
							<div class="bar" style="width: {{$country->width * 70}}px;"></div>
	@endif
						</div>
						<div class="text-small-75 text-right" style="flex: 0 0 60px;">
							of {{$country->col_count}}
						</div>
					</div>
@endforeach
				</div>
			</div>	
			<div class="card mb-3">
				<div class="card-header p-2">
					<span>Last Cols Claimed</span>
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
					<span class="text-small-75">{{$done_count}} cols claimed</span>
					<div class="ml-auto" tabindex="0" role="button" data-toggle="modal" data-target="#modal-first">
						<a href="/user/claimed"><i id="col-first-all" class="fas fas-grey fa-search-plus" title="show all"></i></a>
					</div>
				</div>
			</div>
			<div class="card mb-3">
				<div class="card-header p-2">
					Cols Done This Year
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
					Total: 234
					Last Year: 13
					Rank: 45
				</div>
			</div>		
		</div>
	</div>
</main>
@stop