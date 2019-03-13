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
				<div class="card-header p-2 d-flex align-items-center">
					<span>Most Cols Climbed</span>
					<div class="ml-auto" tabindex="0" role="button" data-toggle="modal" data-target="#modal-first">
						<a href="/athletes/most"><i id="col-first-all" class="fas fas-grey fa-search-plus" title="Show all"></i></a>
					</div>
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
@foreach($users_most as $users_most_)
					<div class="align-items-end d-flex">
						<div class="text-truncate">
							<a href="/user/{{$users_most_->id}}">{{$users_most_->name}}</a>
						</div>
						<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
							{{$users_most_->cols}}
						</div>
					</div>
@endforeach
				</div>
			</div>
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<!-- -->
			<div class="card mb-3">
			</div>
			<!-- -->
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 1 on sm--></div>
			<div class="w-100 d-none d-sm-none d-md-block d-lg-none"><!-- wrap every 2 on md--></div>
			<!-- -->
			<div class="card mb-3">
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