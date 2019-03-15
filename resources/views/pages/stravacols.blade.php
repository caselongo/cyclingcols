@extends('layouts.master')

@section('content')
<main role="main" class="bd-content p-3 font-weight-light">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4 text-center">
			<div class="card mb-3">
				<div class="card-header p-2 d-flex align-items-center justify-content-between">
					<span>{{count($cols)}} New Cols Found On Strava</span>
					<a class="btn btn-primary" href="/athlete">
						Close
                    </a>
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
@if (count($cols) == 0)
					Quick go out and climb some cols!
@else
	@foreach($cols as $col)
					<div class="align-items-end d-flex">
						<div class="text-truncate">
							<img src="/images/flags/{{$col->Country1}}.gif" title="{{$col->Country1}}" class="flag mr-1">
		@if ($col->Country2)
							<img src="/images/flags/{{$col->Country2}}.gif" title="{{$col->Country2}}" class="flag mr-1">
		@endif
							{{$col->Col}}
						</div>
						<div class="ml-auto text-small-75 text-right" style="flex: 0 0 70px;">
							{{getHumanDate($col->pivot->ClimbedAt)}}
						</div>
					</div>
	@endforeach
@endif
				</div>
			</div>
        </div>
    </div>
</main>
@endsection