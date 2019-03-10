@foreach ($nearby as $nearby_)
<?php
	$dis = round($nearby_->Distance/1000);
	$dir = round($nearby_->Direction/1000);
	
	if ($dir <= 22) $dir_ = "South";
	else if ($dir <= 67) $dir_ = "South-West";
	else if ($dir <= 112) $dir_ = "West";
	else if ($dir <= 157) $dir_ = "North-West";
	else if ($dir <= 202) $dir_ = "North";
	else if ($dir <= 247) $dir_ = "North-East";
	else if ($dir <= 292) $dir_ = "East";
	else if ($dir <= 337) $dir_ = "South-East";
	else $dir_ = "South";
?>
	<div class="text-small-90 d-flex">
		<div class="text-truncate" title="{{$nearby_->Col}}">
			<a href="/col/{{$nearby_->ColIDString}}">{{$nearby_->Col}}</a>
		</div>
		<div class="ml-auto text-small-75 text-right" style="flex: 0 0 60px;">
			{{$dis}} km
			<img class="direction ml-1" src="/images/{{$dir_}}.png"/>
		</div>
	</div>
@endforeach