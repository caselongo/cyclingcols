@foreach ($top as $top_)
	<div class="px-2 pt-1 font-weight-light text-small-75">Highest {{$top_->StatType->StatType}}</div>
	<div class="px-2 pb-1 font-weight-light border-bottom align-items-end d-flex">
		<div class="text-truncate">
			<a href="/col/{{$top_->ColIDString}}{{ $top_->FileName ? '#' . $top_->FileName : '' }}">{{$top_->Col}}</a>
		</div>
	@if ($top_->Side)
		<div class="ml-1 text-small-75" style="flex: 0 0 40px;" title="{{$top_->Side}}">
			<img class="direction" src="/images/{{$top_->Side}}.png">
			<!--<span class="pl-1 text-small-75">South-West</span>-->
		</div>
	@endif
	</div>
@endforeach