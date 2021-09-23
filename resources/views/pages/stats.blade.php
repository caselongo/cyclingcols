@extends('layouts.master')

@section('title')
CyclingCols - Stats
@stop

@section('content')

@if (count($stats) > 0)
<link rel="stylesheet" href="/css/leaflet.fullscreen.css" type="text/css">
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>
<script src="/js/leaflet.fullscreen.min.js" type="text/javascript"></script>
<script type="text/javascript">	
	initMap = function(){
		var markers = [];
		
<?php

$rankpos_ = 0;
$rank_ = 0;
$stattypeid_ = 0;
$value_ = 0;

foreach($stats as $stat){
	if ($stattypeid_ != $stat->StatTypeID || $value_ != $stat->Value){
		$stattypeid_ = $stat->StatTypeID;
		$value_ = $stat->Value;
		$rank_ = $stat->Rank;
	} else {
		$stat->Rank = $rank_;
	}
	
	/* add to map */
	if ($stat->Rank == 1 || $stattype->StatTypeID > 0){
		$icon = "";
		$title = "";
		$isPrimary = true;
		
		if ($stattype->StatTypeID == 0){
			foreach($stattypes as $stattype_){
				$isPrimary = $stattype_->IsPrimary;
				
				if ($stattype_->StatTypeID == $stat->StatTypeID){
					$title = "";
					if ($stattype_->Type == 1) $title = "Highest";
					else if ($stattype_->Type == 2) $title = "Most";
					
					$icon = $stattype_->Icon;
					$title = $title . " " . $stattype_->StatType . ": ";
					break;
				}	
			}
		}
		
		if ($isPrimary && $stat->ColID > 0){
?>
	markers.push({lat:{{$stat->Latitude/1000000}},lng:{{$stat->Longitude/1000000}},colIDString:"{{$stat->ColIDString}}",fileName:"{{$stat->FileName}}",col:"{{$stat->Col}}",rank:"{{$stat->Rank}}",icon:"{{$icon}}",title:"{{$title}}"});
<?php
		}
	}
}
?>
	if (markers.length > 0){	
		$("#map").removeClass("d-none");

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
		
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);
		
		var icon = L.icon({
			iconUrl: '/images/ColRed.png'	,
			iconAnchor: [16,35]
		});
		
		markers.forEach(function(m){
			var html = m.rank;
			
			if (m.icon) html = "<i class='fas fa-" + m.icon + " m-1'></i>";
		
			var numberIcon = L.divIcon({
				className: "number-icon",
				iconSize: [32, 37],
				iconAnchor: [16,35],
				html: html				
			});
		
			var markerOptions = {
				icon: numberIcon,
				bubblingMouseEvents: true
			};
			
			var marker = L.marker([m.lat, m.lng], markerOptions).addTo(map);
			
			$(marker._icon).attr("title", m.title + "<br/>" + m.col);
			initToolTip($(marker._icon),$("#map"));
			
			marker.on("click", function() {
				parent.document.location.href = "/col/" + m.colIDString + "#" + m.fileName;
			});
		});
	}
}
</script>
@else
<script type="text/javascript">	
	initMap = function(){}
</script>
@endif
<script type="text/javascript">	
window.onload = function(){
	initMap();
	
	var getTopStats = function(){
		$.ajax({
			type: "GET",
			url : "/service/stats/top/{{$country->URL}}",
			dataType : 'json',
			success : function(result) {
				if (result.success){
					$("#top").html(result.html);		
				}	
			}
		});
	}
	
	$(document).ready(function() {
		getTopStats();
	});
}
</script>

<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light">Stats</h4>
	</div>	
	<nav class="navbar navbar-expand-sm navbar-light border-bottom border-top px-2 py-0" id="nav-stats">
		<ul class="navbar-nav">
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$stattype->StatType}}</a>
			<div class="dropdown-menu">
@foreach ($stattypes as $stattype_)
				<a class="dropdown-item font-weight-light" href="/stats/{{$stattype_->URL}}/{{$geourl}}">
					<i class="fas fas-grey fa-{{$stattype_->Icon}} no-pointer"></i>
					<span>{{$stattype_->StatType}}</span>
				</a>
@if ($stattype_->StatTypeID == 0)
					<div class="dropdown-divider"></div>
@endif
@endforeach
			</div>
		  </li>			  
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$country->Country}}</a>
			<div class="dropdown-menu">
@foreach ($countries as $country_)
				<a class="dropdown-item font-weight-light" href="/stats/{{$stattype->URL}}/{{$country_->URL}}">
					<img src="/images/flags/{{$country_->Flag}}.gif" class="flag mr-1">{{$country_->Country}}
				</a>
@if ($country_->CountryID == 0)
					<div class="dropdown-divider"></div>
@endif
@endforeach
			</div>
		  </li>		  
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$region->Region}}</a>
			<div class="dropdown-menu">
@foreach ($regions as $region)
				<a class="dropdown-item font-weight-light" href="/stats/{{$stattype->URL}}/{{$region->URL}}">
					{{$region->Region}}
				</a>
@if ($region->RegionID == 0)
					<div class="dropdown-divider"></div>
@endif
@endforeach
			</div>
		  </li>	  
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$subregion->SubRegion}}</a>
			<div class="dropdown-menu">
@foreach ($subregions as $subregion)
				<a class="dropdown-item 
	@if	($subregion->SubRegionID > 0)		
				dropdown-item-inline 
	@endif
				font-weight-light" href="/stats/{{$stattype->URL}}/{{$subregion->URL}}" title="{{$subregion->SubRegion}}">
					{{$subregion->SubRegion}}
				</a>
@if ($subregion->SubRegionID == 0)
					<div class="dropdown-divider"></div>
@endif
@endforeach
			</div>
		  </li>
		</ul>
	</nav>
	<div class="w-100 d-flex align-items-start flex-wrap">
		<div class="w-100 w-md-75 mb-3 p-3 pr-md-0"><!-- w-75 -->
			<div class="card-deck w-100">
<?php
	$card_count = 0;
	$stattype_current = $stattype;
?>
@if (count($stats) == 0)
	<div class="card mb-3">
		<div class="card-body p-2 font-weight-light text-small-90">No data</div>
	</div>
<?php
	$card_count++;
?>

@else
<?php
	$stattypeid = 0;
	$rank_prev = 0;
	//$value_prev = "";
	
	foreach ($stats as $stat){
			
		if ($stat->StatTypeID != $stattypeid){
			if ($stattypeid > 0){
?>
					</div><!--card-body-->
				</div><!--card-->
				
		@if ($card_count > 0)
				<div class="w-100 d-block d-lg-none"><!-- wrap each 1 on md--></div>
		@endif
		@if ($card_count > 0 && ($card_count % 2) == 0)
				<div class="w-100 d-none d-lg-block"><!-- wrap each 2 on lg and larger--></div>
		@endif
<?php			
			}		
				
			$stattypeid = $stat->StatTypeID;
			$card_count++;
			
			foreach($stattypes as $stattype_){
			if ($stattype_->StatTypeID == $stattypeid){
				$stattype_current = $stattype_;
				break;
			}
		}
?>
				<div class="card mb-3">
					<div class="card-header p-2 d-flex justify-content-between align-items-baseline">
						<div>
							<i class="fas fas-grey fa-{{$stattype_current->Icon}} no-pointer"></i>
							<span>{{$stattype_current->StatType}}</span>
						</div>
											
		@if ($stattype->StatTypeID == 0)		
						<a href="/stats/{{$stattype_current->URL}}/{{$country->URL}}"><i id="col-first-all" class="fas fas-grey fa-search-plus" title="Show all" data-toggle="tooltip"></i></a>
		@endif
					</div>
					<div class="card-body p-2 font-weight-light text-small-90">
<?php
		}
		
		
		$value = formatStat($stat->StatTypeID, $stat->Value);
?>	
						<div class="align-items-end d-flex">
							<div class="text-small-90 text-right mr-1" style="flex: 0 0 15px;">
		@if ($rank_prev != $stat->Rank)
							{{$stat->Rank}}
		@endif
							</div>
							<div class="text-truncate">
		@if ($stat->Country1)
								<img src="/images/flags/{{$stat->Country1IDString}}.gif" title="{{$stat->Country1}}" data-toggle="tooltip" class="flag mr-1">
		@endif
		@if ($stat->Country2)
								<img src="/images/flags/{{$stat->Country2IDString}}.gif" title="{{$stat->Country2}}" data-toggle="tooltip" class="flag mr-1">
		@endif
		@if ($stat->Col)
								<a href="/col/{{$stat->ColIDString}}{{ $stat->FileName ? '#' . $stat->FileName : '' }}">{{$stat->Col}}</a>
		@endif		
		@if ($stat->Country)
								<img src="/images/flags/{{$stat->CountryIDString}}.gif" title="{{$stat->Country}}" data-toggle="tooltip" class="flag mr-1">
		@endif
		@if ($stat->SubRegion)
								{{$stat->SubRegion}}
		@elseif ($stat->Region)
								{{$stat->Region}}
		@elseif ($stat->Country)
								{{$stat->Country}}
		@endif
		
							</div>
		@if ($stat->Side)
							<div class="ml-1 text-small-75" style="flex: 0 0 40px;" title="{{$stat->Side}}" data-toggle="tooltip">
								<img class="direction" src="/images/{{$stat->Side}}.png">
								<!--<span class="pl-1 text-small-75">{{$stat->Side}}</span>-->
							</div>
		@endif
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 45px;">{{$value}}</div>
						
<?php
		if (Auth::user() && $stat->ColID > 0){
			$col_climbed_class = "col-climbed-no-light";
			$col_climbed_title = "You did not climb this col";
			if ($stat->Climbed) {
				$col_climbed_class = "col-climbed-yes";
				$col_climbed_title = "You climbed this col";
			}
?>
							<i class="col-done fas fa-check {{$col_climbed_class}} pl-1 py-1 text-small-90 no-pointer" title="{{$col_climbed_title}}" data-toggle="tooltip"></i>
<?php
		}
?>
						</div>
<?php
		
		$rank_prev = $stat->Rank;	
	}
				
	/* close last card */
	if ($stattypeid > 0){
?>
					</div><!--card-body-->
				</div><!--card-->
<?php			
	}
?>
@endif
<?php	

	if ($stattype->StatTypeID > 0 && $stats_countries != ""){
?>
		@if ($card_count > 0)
				<div class="w-100 d-block d-lg-none"><!-- wrap each 1 on md--></div>
		@endif
		@if ($card_count > 0 && ($card_count % 2) == 0)
				<div class="w-100 d-none d-lg-block"><!-- wrap each 2 on lg and larger--></div>
		@endif

		{!!$stats_countries!!}
<?php
		$card_count++;
	}	
	if ($stattype->StatTypeID > 0 && $stats_regions != ""){
?>
		@if ($card_count > 0)
				<div class="w-100 d-block d-lg-none"><!-- wrap each 1 on md--></div>
		@endif
		@if ($card_count > 0 && ($card_count % 2) == 0)
				<div class="w-100 d-none d-lg-block"><!-- wrap each 2 on lg and larger--></div>
		@endif

		{!!$stats_regions!!}
<?php
		$card_count++;
	}	

	if ($stattype->StatTypeID > 0 && $stats_subregions != ""){	
?>
		@if ($card_count > 0)
				<div class="w-100 d-block d-lg-none"><!-- wrap each 1 on md--></div>
		@endif
		@if ($card_count > 0 && ($card_count % 2) == 0)
				<div class="w-100 d-none d-lg-block"><!-- wrap each 2 on lg and larger--></div>
		@endif

		{!!$stats_subregions!!}
<?php
		$card_count++;
	}
	
	for ($i = 0; $i < 2; $i++){
?>
		@if ($card_count > 0)
				<div class="w-100 d-block d-lg-none"><!-- wrap each 1 on md--></div>
		@endif
		@if ($card_count > 0 && ($card_count % 2) == 0)
				<div class="w-100 d-none d-lg-block"><!-- wrap each 2 on lg and larger--></div>
		@endif				
				<!--add some invisible cards to be sure last cards are of equal size-->
				<div class="card card-invisible"></div>
			
<?php
		$card_count++;
	}
?>
			</div><!--card-deck-->
		</div><!-- w-75 -->
		<div class="w-100 w-md-25 px-3 pl-md-0 py-3"><!--sidebar-->
@if (count($stats) > 0)
			<div class="col-box w-100 mb-3">
				<div id="map" class="col-map d-none">
				</div>			
			</div>			
@endif
			<div class="col-box w-100 mb-3">
				<div class="p-2 border-bottom d-flex align-items-center">
					<h6 class="font-weight-light m-0">Top Cols In {{$country->Country}}</h6>
				</div>
				<div id="top" class="font-weight-light p-0">	
				</div>
			</div>
		</div>
	</div><!--container-->
</main>
@stop

@include('includes.profilemodal')
