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

foreach($stats as $stat){
?>
	markers.push({
		lat: {{$stat->Latitude/1000000}},
		lng: {{$stat->Longitude/1000000}},
		colIDString: "{{$stat->ColIDString}}",
		col: "{{$stat->Col}}",
		rank: {{$stat->Rank}}
	});
<?php
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
	}));
	map.scrollWheelZoom.disable();
	
	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);
	
	var icon = L.icon({
		iconUrl: '/images/ColRed.png'	,
		iconAnchor: [16,35]
	});
	
	markers.forEach(function(m){
	
		var numberIcon = L.divIcon({
			className: "number-icon",
			iconSize: [32, 37],
			iconAnchor: [16,35],
			html: m.rank 
		});
	
		var markerOptions = {
			icon: numberIcon,
			bubblingMouseEvents: true
		};
		
		var marker = L.marker([m.lat, m.lng], markerOptions).addTo(map);
		
		$(marker._icon).attr("title",m.col);
		
		marker.on("click", function() {
			parent.document.location.href = "/col/" + m.colIDString;
		});
	});
	
	var getTopStats = function(){
		$.ajax({
			type: "GET",
			url : "/service/stats/top/{{$country->URL}}",
			dataType : 'json',
			success : function(data) {
				var top = $("#top");
				
				data.forEach(function(d){
					var div1 = document.createElement("div");
					$(div1)
						.addClass("px-2 pt-1 font-weight-light text-small-75")
						.html("Highest " + d.StatType.StatType);
					var div2 = document.createElement("div");
					$(div2)
						.addClass("px-2 pb-1 font-weight-light border-bottom");
					var a = document.createElement("a");
					$(a).attr("href","/col/" + d.ColIDString);
					var span = document.createElement("span");
					$(span)
						.addClass("pr-1")
						.html(d.Col);
					
					top.append(div1,div2);
					$(div2).append(a);
					$(a).append(span);	
						
					if (d.Side){
						var img = document.createElement("img");
						$(img)
							.addClass("direction")
							.attr("src","/images/" + d.Side + ".png");
						var span1 = document.createElement("span");
						$(span1)
							.addClass("pl-1 text-small-75")
							.html(d.Side);
									
						$(div2).append(img,span1);	
					}
				});
				
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
				<div class="card mb-3">
					<div class="card-header p-2">
						<i class="fas fas-grey fa-{{$stattype->Icon}} no-pointer"></i>
						<span>{{$stattype->StatType}}</span>
					</div>
					<div class="card-body p-2 font-weight-light text-small-90">
						<div>{{$stattype->Description}}</div>
						<div class="mt-2">Here is the top 25 for {{$country->Country}}.</div>
					</div>
				</div>
<?php
	$count = 0;
	
	foreach ($stats as $stat){
		
		$value = formatStat($stattype->StatTypeID, $stat->Value);
?>	
			<div class="card rounded-top-left mb-3">
				<div class="card-header rounded-top-left p-0 d-flex justify-content-around align-items-center">
					<span class="rounded-top-left text-small-90 border-right bg-primary text-light text-center" style='width: 30px'>{{$stat->Rank}}</span>
					<span class="m-auto">{{$value}}</span>
<?php
		if (Auth::user()){
			$col_done_class = "col-done-no-light";
			$col_done_title = "You did not climb this col";
			if ($stat->Done == 1) {
				$col_done_class = "col-done-yes";
				$col_done_title = "You climbed this col";
			}
?>
					<i class="col-done fas fa-check {{$col_done_class}} p-1 text-small-90 no-pointer" title="{{$col_done_title}}"></i>
<?php
		}
?>
				</div>
@if ($stat->CoverPhotoPosition != null)
				<div class="card-img-top card-img-background" onclick='goToCol("{{$stat->ColIDString}}")' style='background-position: 50% {{$stat->CoverPhotoPosition}}%; background-image: url("/images/covers/small/{{$stat->ColIDString}}.jpg")'>
@else
				<div class="card-img-top card-img-background" onclick='goToCol("{{$stat->ColIDString}}")' style='background-position: 50% 28%; background-image: url("/images/covers/small/_dummy.jpg")'>
@endif
@if ($stat->IsNew)
					<div class="card-img-new">New</div>
@endif
					<!--<div class="card-go-to"><small><i class="fas fa-search"></i></small></div>-->
				</div><!--card-img-top-->
				<div class="card-body p-0">
					<h6 class="card-title p-2 m-0 font-weight-light">
						<img src="/images/flags/{{$stat->Country1}}.gif" title="{{$stat->Country1}}" class="flag">
@if ($stat->Country2)
						<img src="/images/flags/{{$stat->Country2}}.gif" title="{{$stat->Country2}}" class="flag flag2">
@endif
						<a href="/col/{{$stat->ColIDString}}">{{$stat->Col}}</a>
						<span class="badge badge-altitude font-weight-light text-small-70">{{$stat->Height}}m</span>
					</h6>
					<!--<div class="w-100 p-1 d-flex bg-dark text-white text-small-75 align-items-center flex-wrap">
					auth
						<div id="col-rating-done" class="w-100 w-sm-50">
							<span class="col-done-value"></span>
							<i class="col-done fas fa-check col-done-no"></i>
						</div>
						<div id="col-rating-user" class="w-100 w-sm-50">
							<span class="col-rating-value"></span>	
							<div class="d-inline-block">
								<i data-rating="1" class="col-rating no-pointer fas fa-star col-rating-no"></i>
								<i data-rating="2" class="col-rating no-pointer fas fa-star col-rating-no"></i>
								<i data-rating="3" class="col-rating no-pointer fas fa-star col-rating-no"></i>
								<i data-rating="4" class="col-rating no-pointer fas fa-star col-rating-no"></i>
								<i data-rating="5" class="col-rating no-pointer fas fa-star col-rating-no"></i>
							</div>
						</div>
					else
						<div class="w-50">
							<a href="/login"/>Login</a> to rate this col
						</div>
					endauth					
					</div>-->
					<div class="card-profile px-2 py-1 text-small-75 border-top d-flex flex-row justify-content-between align-items-baseline">
						<div>
							<span class="category category-{{$stat->Category}}">{{$stat->Category}}</span>
@if ($stat->Side != null)
							<span>{{$stat->Side}}</span>
							<img class="direction" src="/images/{{$stat->Side}}.png">
@endif
							<small>{{$stat->Start}}</small>
						</div>	
						<a tabindex="0" role="button" data-toggle="modal" data-target="#modalProfile" data-profile="{{$stat->FileName}}" data-col="{{$stat->Col}}"><i class="fas fas-grey  fa-search-plus"></i></a>		
					</div>
				</div><!--card-body-->
			</div><!--card-->
<?php	
		$count++;
?>
		<!-- card wrapping, see https://www.codeply.com/go/nIB6oSbv6q -->
		@if ($count == 1)
			<div class="w-100 d-none d-sm-block d-lg-none"><!-- wrap first 1 on sm--></div>
		@endif
		@if ($count > 0 && ($count - 1) % 2 == 0)
			<div class="w-100 d-none d-sm-block d-lg-none"><!-- wrap next 2 on sm--></div>
		@endif
		@if ($count == 2)
			<div class="w-100 d-none d-lg-block"><!-- wrap first 2 on lg or larger--></div>
		@endif
		@if ($count > 0 && ($count - 2) % 3 == 0)
			<div class="w-100 d-none d-lg-block"><!-- wrap next 3 on lg or larger--></div>
		@endif
<?php
	}
	
	for ($i = 0; $i < 3; $i++){
?>
		<!--add some invisible cards to be sure last cards are of equal size-->
		<div class="card card-invisible"></div>
			
<?php
		$count++;
?>
		@if ($count == 1)
			<div class="w-100 d-none d-sm-block d-lg-none"><!-- wrap first 1 on sm--></div>
		@endif
		@if ($count > 0 && ($count - 1) % 2 == 0)
			<div class="w-100 d-none d-sm-block d-lg-none"><!-- wrap next 2 on sm--></div>
		@endif
		@if ($count == 2)
			<div class="w-100 d-none d-lg-block"><!-- wrap first 2 on lg or larger--></div>
		@endif
		@if ($count > 0 && ($count - 2) % 3 == 0)
			<div class="w-100 d-none d-lg-block"><!-- wrap next 3 on lg or larger--></div>
		@endif
<?php
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
				<div class="profs" id="profs">
					<div class="p-2 border-bottom d-flex align-items-center">
						<h6 class="font-weight-light m-0">Top cols in {{$country->Country}}</h6>
					</div>
					<div id="top" class="font-weight-light p-0">
						
					</div>
				</div>
			</div>
		</div>
	</div><!--container-->
</main>
@stop

@include('includes.profilemodal')
