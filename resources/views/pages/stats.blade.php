@extends('layouts.master')

@section('title')
CyclingCols - Stats
@stop

@section('content')

<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>
<script type="text/javascript">	
	window.onload = function(){
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
	
	if ($stat->Rank == 1 || $stattype->StatTypeID > 0){
		$icon = "";
		$title = "";
		
		if ($stattype->StatTypeID == 0){
			foreach($stattypes as $stattype_){
				if ($stattype_->StatTypeID == $stat->StatTypeID){
					$icon = $stattype_->Icon;
					$title = 'Highest ' . $stattype_->StatType . ": ";
					break;
				}	
			}
		}
		
?>
	markers.push({
		lat: {{$stat->Latitude/1000000}},
		lng: {{$stat->Longitude/1000000}},
		colIDString: "{{$stat->ColIDString}}",
		fileName: "{{$stat->FileName}}",
		col: "{{$stat->Col}}",
		rank: "{{$stat->Rank}}",
		icon: "{{$icon}}",
		title: "{{$title}}"
	});
<?php
	}
}
?>

	var mapOptions = {
		attributionControl: false,
		zoomControl: true,
		dragging: true
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
		
		$(marker._icon).attr("title", m.title + m.col);
		
		marker.on("click", function() {
			parent.document.location.href = "/col/" + m.colIDString + "#" + m.fileName;
		});
	});
	
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

/*
var createRatingEventHandlers = function(){
if (Auth::user())
	$(".col-done").on("mouseenter",function(){
		$(this).addClass("col-done-yes-hover").removeClass("col-done-no-light");
	});
	
	$(".col-done").on("mouseleave",function(){
		$(this).addClass("col-done-no-light").removeClass("col-done-yes-hover");
	});
	
	$(".col-done").on("click",function(){	
		if ($(this).hasClass("col-done-yes")) return;
		$(this).toggleClass("col-done-yes col-done-no-light");
		
		var col = $(this).data('col');
		
		colsDone.push(col);
			
		$.ajax({
			type: "GET",
			url : "/user/col",
			data: {
				"colIDString": col,
				"done": true
			},
			dataType : 'json',
			success : function(data) {							
			}
		});
	});
endif		
}
			
$(document).ready(function() {
	createRatingEventHandlers();
});*/

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
				<a class="dropdown-item font-weight-light" href="/stats/{{$stattype_->URL}}/{{$country->URL}}">
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
		</ul>
	</nav>
	<div class="w-100 d-flex align-items-start flex-wrap">
		<div class="w-100 w-md-75 mb-3 p-3 pr-md-0"><!-- w-75 -->
			<div class="card-deck w-100">
<?php
	$card_count = 0;
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
						<a href="/stats/{{$stattype_current->URL}}/{{$country->URL}}"><i id="col-first-all" class="fas fas-grey fa-search-plus" title="show all"></i></a>
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
								<img src="/images/flags/{{$stat->Country1}}.gif" title="{{$stat->Country1}}" class="flag mr-1">
		@if ($stat->Country2)
								<img src="/images/flags/{{$stat->Country2}}.gif" title="{{$stat->Country2}}" class="flag mr-1">
		@endif
								<a href="/col/{{$stat->ColIDString}}{{ $stat->FileName ? '#' . $stat->FileName : '' }}">{{$stat->Col}}
								</a>
							</div>
		@if ($stat->Side)
							<div class="ml-1 text-small-75" style="flex: 0 0 40px;" title="{{$stat->Side}}">
								<img class="direction" src="/images/{{$stat->Side}}.png">
								<!--<span class="pl-1 text-small-75">{{$stat->Side}}</span>-->
							</div>
		@endif
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 45px;">{{$value}}</div>
						
<?php
		if (Auth::user()){
			$col_climbed_class = "col-climbed-no-light";
			$col_climbed_title = "You did not climb this col";
			if ($stat->Climbed) {
				$col_climbed_class = "col-climbed-yes";
				$col_climbed_title = "You climbed this col";
			}
?>
							<i class="col-done fas fa-check {{$col_climbed_class}} pl-1 py-1 text-small-90 no-pointer" title="{{$col_climbed_title}}"></i>
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
	
	if ($stattype->StatTypeID > 0 && $stats_other){
		$card_count++;
?>
				<div class="card mb-3">
					<div class="card-header p-2 d-flex justify-content-between align-items-baseline">
						<div>
							<i class="fas fas-grey fa-{{$stattype_current->Icon}} no-pointer"></i>
							<span>Highest {{$stattype_current->StatType}} Per Country</span>
						</div>
					</div>
					<div class="card-body p-2 font-weight-light text-small-90">
<?php
	
		foreach ($stats_other as $stat_other){
			$countryid_ = $stat_other->Country1ID;
			$country_ = $stat_other->Country1;
			
			if ($stat_other->GeoID == $stat_other->Country2ID){
				$countryid_ = $stat_other->Country2ID;
				$country_ = $stat_other->Country2;
			}	

			$value_ = formatStat($stat_other->StatTypeID, $stat_other->Value);			
?>
						<div class="align-items-end d-flex">
							<div class="text-truncate">
								<img src="/images/flags/{{$country_}}.gif" title="{{$country_}}" class="flag mr-1">
								<a href="/col/{{$stat_other->ColIDString}}{{ $stat_other->FileName ? '#' . $stat_other->FileName : '' }}">{{$stat_other->Col}}
								</a>
							</div>
		@if ($stat_other->Side)
							<div class="ml-1 text-small-75" style="flex: 0 0 40px;" title="{{$stat_other->Side}}">
								<img class="direction" src="/images/{{$stat_other->Side}}.png">
								<!--<span class="pl-1 text-small-75">{{$stat->Side}}</span>-->
							</div>
		@endif
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 45px;">{{$value_}}</div>
						</div>

					
<?php		
		}
?>
					</div><!--card-body-->
				</div><!--card-->
<?php
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
			<div class="col-box w-100 mb-3">
				<div id="map" class="col-map">
				</div>			
			</div>			
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
