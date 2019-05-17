@extends('layouts.master')

@section('title')
	@if (is_null($list))
		CyclingCols - Lists
	@else
		CyclingCols - Lists - {{$list->Name}}
	@endif
@stop

@section('content')

<link rel="stylesheet" href="/css/leaflet.fullscreen.css" type="text/css">
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>
<script src="/js/leaflet.fullscreen.min.js" type="text/javascript"></script>
<script type="text/javascript">	
	window.onload = function(){
		var markers = [];
		
<?php

$rankpos_ = 0;
$rank_ = 0;
$stattypeid_ = 0;
$value_ = 0;

if (!is_null($sections)){
	foreach($sections as $sections_){
		foreach($sections_->cols()->orderBy('Sort')->get() as $col){
			if ($col->ColID > 0){
?>
		markers.push({lat:{{$col->col->Latitude/1000000}},lng:{{$col->col->Longitude/1000000}},colIDString:"{{$col->col->ColIDString}}",title:"{{$col->col->Col}}"});
<?php
			}
		}
	}
}
?>

	var mapOptions = {
		attributionControl: false,
		zoomControl: true,
		dragging: true,
		fullscreenControl: true   
	};
	var map = L.map('map', mapOptions);//.setView([lat, lng], 4
	map.fitBounds(markers.map(function(m){
		return [m.lat, m.lng];
	}),{padding: [20,20]});
	map.scrollWheelZoom.disable();
	
	/*map.on('fullscreenchange', function () {
		if (map.isFullscreen()) {
			markers.forEach(function(m){
				$(m.marker._icon).attr("title", m.title);
				initToolTip($(m.marker._icon));
			});			
		}
	});*/
	
	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);
	
	var icon = L.icon({
		iconUrl: '/images/ColRed.png'	,
		iconAnchor: [16,35]
	});
	
	markers.forEach(function(m){
		var html = m.rank;
	
		var markerOptions = {
			icon: icon,
			bubblingMouseEvents: true
		};
		
		var marker = L.marker([m.lat, m.lng], markerOptions).addTo(map);
		m.marker = marker;
		
		$(marker._icon).attr("title", m.title);
		initToolTip($(marker._icon),$("#map"));
		
		marker.on("click", function() {
			parent.document.location.href = "/col/" + m.colIDString
		});
	});
}
</script>

<main role="main" class="bd-content">
    <div class="header px-4 py-3 d-flex align-items-baseline">
        <h4 class="font-weight-light">Lists</h4>
@if (!is_null($list))
			<span class="border rounded bg-light ml-2 px-2 py-1 font-weight-light">{{$list->Name}}</span>
@endif
	</div>	
	<div class="w-100 d-flex align-items-start flex-wrap">
		<div class="w-100 w-lg-25 p-3"><!--sidebar-->
@foreach($lists as $lists_)
			<a class="d-block" href="/list/{{$lists_->Slug}}">{{$lists_->Name}}</a>
@endforeach
		</div>		
		<div class="w-100 w-md-50 w-lg-50 p-3"><!-- w-75 -->
@if (!is_null($list))
	@foreach($sections as $sections_)
			<div class="card mb-1">
		@if (!is_null($sections_->Name))
				<div class="card-header p-2">
					{{$sections_->Name}}
				</div>
		@endif
				<div class="card-body px-2 py-1 font-weight">
		@foreach($sections_->cols()->orderBy('Sort')->get() as $col)
<?php
			$climbed = null;
			$col_ = $col->col;
			if (is_null($col_)){
				$col->ColID = 0;
			}
?>
					<div class="font-weight-light text-small-90 d-flex justify-content-between mt-1 align-items-baseline">
			@if ($col->Category)
						<div class="list-category mr-1" title="Category" data-toggle="tooltip">{{$col->Category}}</div>
			@endif
			@if ($col->ColID == 0 || $col->ShowColName)
						<span class="mr-1">{{$col->Col}}</span>
			@endif
			@if ($col->ColID > 0)
<?php
			if (Auth::user()){
				$climbed = $col_->climbedByMe();
			}
?>
				@if ($col->PPartial)
						(~ <a href="/col/{{$col_->ColIDString}}" title="Only partially climbed" data-toggle="tooltip">{{$col_->Col}}</a>)
				@else
					@if ($col->ShowColName)
						<span class="mr-1">=</span>
					@endif
						<a href="/col/{{$col_->ColIDString}}">{{$col_->Col}}</a>
				@endif
				<?php
$profile = null;
				if ($col->ProfileID > 0){
					$profile = \App\Profile::where('ProfileID','=',$col->ProfileID)->first();
					
				}

?>				
				@if (!is_null($profile))
					@if (!is_null($profile->Side))
						<div>
							<span class="text-small-75 ml-1">{{$profile->Side}}</span>
							<img class="direction" src="/images/{{$profile->Side}}.png">
						</div>
					@endif
						<div class="ml-2">
							<a tabindex="0" role="button" data-toggle="modal" data-target="#modalProfile" data-profile="{{$profile->FileName}}" data-col="{{$col_->Col}}" data-remarks="{{$col->Remarks}}">
								<i class="fas fas-grey  fa-search-plus"></i>
							</a>
						</div>
				@endif

			@endif
			

<?php
		if (is_null($climbed)){
?>
						<span class="ml-auto"></span>
<?php
		} else {
			if ($climbed){
				$col_climbed_class = "col-climbed-yes";
				$col_climbed_title = "You climbed this col";
			} else {
				$col_climbed_class = "col-climbed-no-light";
				$col_climbed_title = "You did not climb this col";
			}
?>
						<i class="col-done fas fa-check {{$col_climbed_class}} pl-1 py-1 text-small-90 no-pointer ml-auto" title="{{$col_climbed_title}}" data-toggle="tooltip"></i>
<?php
		}
?>					
					</div>
			@if ($list->EventID > 0)
<?php
	$last = $col->lastPassage($list->EventID);
?>
				@if (!is_null($last))
						<div class="d-flex font-weight-light ml-4 align-items-end">
							<span class="text-small-75 mr-1">Last time in {{$last->eventShort()}}: {{$last->Edition}}</span>
							<div class="text-small-75 mr-1">{{$last->Person}}</div>
							<img class="flag flag-small" src='/images/flags/small/{{strtolower($last->NatioAbbr)}}.gif' title='{{$last->Natio}}'/>
						</div>
				@endif
			@endif
		@endforeach
				</div>
			</div>
	@endforeach
@endif
		</div><!-- w-75 -->
		
		<div class="w-100 w-md-50 w-lg-25 p-3"><!--sidebar-->
@if (!is_null($list))
			<div class="card mb-3">
				<div class="card-header p-2">
					Most Cols In This List Climbed
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
	@foreach($users as $user)
					<div class="align-items-baseline d-flex">
		@auth
						<div class="text-truncate">
							<a href="/athlete/{{$user->slug}}">{{$user->name}}</a>
						</div>
						<div class="text-primary text-small-75 text-right" style="flex: 0 0 15px;">
			@if ($user->id == Auth::user()->id)
							<i class="fas fa-user" title="That's you!" data-toggle="tooltip"></i> 
			@elseif ($user->followedByMe())
							<i class="fas fa-check" title="Following" data-toggle="tooltip"></i> 
			@endif
						</div>
		@else
						<div class="text-truncate">{{$user->name}}</div>		
		@endauth
						<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
								{{$user->count}}
						</div>
					</div>									
	@endforeach
	@if (count($users) == 0)
					<span class="text-small-75">Nothing to show here.</span>
	@endif
				</div>				
				<div class="card-footer text-muted">
					<span class="text-small-75">{{$list->colCount()}} cols in this list</span>
				</div><!--card-footer-->
			</div>
			<div class="col-box w-100 mb-1 h-100">
				<div id="map" class="col-map">
				</div>			
			</div>	
@endif
		</div>
	</div><!--container-->
</main>
@stop

@include('includes.profilemodal')