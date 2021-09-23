<?php
	$profile = \App\Profile::where('FileName', $fileName)->first();

	if ($profile == null) {
		return response(['success' => false], 404);
	}
	
	$expr = 'ABS((HeightDiff - ' . $profile->HeightDiff . ')/10) + ABS(AvgPerc - ' . $profile->AvgPerc . ') + ABS(Distance - ' . $profile->Distance . ')';
	
	$similar = \App\Profile::where('FileName', '<>', $fileName)
		->whereNotNull('HeightDiff')
		->whereNotNull('AvgPerc')
		->whereNotNull('Distance')
		->whereRaw($expr . ' < 10')
		//->orderByRaw('ABS((HeightDiff - ' . $profile->HeightDiff . ')/' . $profile->HeightDiff . ') + ABS((AvgPerc - ' . $profile->AvgPerc . ')/' . $profile->AvgPerc . ') + ABS((Distance - ' . $profile->Distance . ')/' . $profile->Distance . ')')
		->orderByRaw($expr)
		->first();

?>
@if ($similar)
<div class="px-2 pb-2 align-items-baseline d-flex text-small-90 font-weight-light">
	<span class="pr-2 text-small-75">
		Similar climb
		<i class="fas fas-grey fa-info-circle"
		   title="Based on distance, elevation gain and average slope."
		   data-toggle="tooltip"></i> :
	</span>
	<img src="/images/flags/{{$similar->col->Country1}}.gif" title="{{$similar->col->Country1}}" class="flag mr-1">
	@if ($similar->col->Country2)
	<img src="/images/flags/{{$similar->col->Country2}}.gif" title="{{$similar->col->Country2}}" class="flag mr-1">
	@endif
	<div class="text-truncate">
		<a href="/col/{{$similar->col->ColIDString}}{{ $similar->FileName ? '#' . $similar->FileName : '' }}">{{$similar->col->Col}}
		</a>
	</div>
	@if ($similar->Side)
	<div class="ml-1 text-small-75" title="{{$similar->Side}}">
		<img class="direction" src="/images/{{$similar->Side}}.png">
		<span class="text-small-75">{{$similar->Side}}</span>
	</div>
	@endif
	<a class="ml-1" tabindex="0" role="button" data-toggle="modal" data-target="#modalProfile" data-profile="{{$similar->FileName}}" data-col="{{$similar->col->Col}}">
		<i class="fas fas-grey  fa-search-plus"></i>
	</a>
</div>
@endif