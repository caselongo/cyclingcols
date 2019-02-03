@extends('layouts.master')

@section('title')
CyclingCols - {{$col->Col}}
@stop

@section('og_title')
CyclingCols - {{$col->Col}}
@stop

@section('og_site_name')
CyclingCols
@stop

@section('og_description')
{{$col->Height}}m, {{$col->Country1}}
@if($col->Country2)
/{{$col->Country2}}
@endif
@stop

@section('og_url')
http://www.cyclingcols.com/col/{{$col->ColIDString}}
@stop

@section('og_image')
http://www.cyclingcols.com/profiles/{{$profiles->first()->FileName}}.gif
@stop

@include('includes.functions')

@section('content')

<?php
	$hasCoverPhoto = 1;
	if (is_null($col->CoverPhotoPosition)) $hasCoverPhoto = 0;
?>
<script src="/js/col.js" type="text/javascript"></script>
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>
<script type="text/javascript">	
	window.onload = function(){
		
		var lat = {{$col->Latitude/1000000}};
		var lng = {{$col->Longitude/1000000}};

		var mapOptions = {
			attributionControl: false,
			zoomControl: false,
			dragging: false
		};
		var map = L.map('map', mapOptions).setView([lat, lng], 4);
		
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);
		
		/*L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			minZoom: 4,
			maxZoom: 4,
			id: 'mapbox.streets',
			accessToken: 'pk.eyJ1IjoiY3ljbGluZ2NvbHMiLCJhIjoiY2pudTdycTc4MDc2ZTNyb2kyMTUzampjcCJ9.PUlrY1MZeyqtE8_WKj7Smw'
		}).addTo(map);*/
		
		var icon = L.icon({
			iconUrl: '/images/ColRed.png',
			//iconSize: [38, 95],
			iconAnchor: [16,35]
			//popupAnchor: [-3, -76],
			//shadowUrl: 'my-icon-shadow.png',
			//shadowSize: [68, 95],
			//shadowAnchor: [22, 94]
		});
		
		var markerOptions = {
			icon: icon,
			bubblingMouseEvents: true
		};
		var marker = L.marker([lat, lng], markerOptions).addTo(map);
		
		map.on('click', onMapClick);
		
		function onMapClick(e) {
			parent.document.location.href = "/map/col/{{$col->ColIDString}}";
		}
	}
	
	var _rating_ = null;
	
	var showRating = function(){
		/* nr of users */
		$("#col-rating-count").html(_rating_.done_count + " users climbed this col");
		
		/* avg rating */
		var div = $("#col-rating-avg div");
		if (div.length > 0){
			div = div[0];
			
			var el_i = $(div).find("i");
			
			for (var i = 0; i < el_i.length; i++){
				if (Math.round(_rating_.rating_avg) >= i + 1){
					$(el_i[i]).addClass("col-rating-avg-yes").removeClass("col-rating-avg-no");
				} else {
					$(el_i[i]).addClass("col-rating-avg-no").removeClass("col-rating-avg-yes");
				}
			}
		}	
		
		var span = $("#col-rating-avg span");
		if (span.length > 0){
			span = span[0];
			$(span).html("Average rating " + (Math.round(_rating_.rating_avg * 10)/10) +  "/5");
		}	

@if (Auth::user())
		/*  user done */
		var div = $("#col-rating-done");
		if (div.length > 0){
			div = div[0];
			
			var el_i = $(div).find("i");
			
			if (el_i.length == 1){
				if (_rating_.done == 1){
					$(el_i).addClass("col-done-yes").removeClass("col-done-no");
				} else {
					$(el_i).addClass("col-done-no").removeClass("col-done-yes");
				}		
			}
				
			var span = $(div).find("span");
			
			if (span.length == 1){	
				if (_rating_.done == 1){
					$(span).html("You climbed this col");
				} else {
					$(span).html("You did not climb this col");
				}
			}
		}
				
		/* user rating */
		var div = $("#col-rating-user div");
		if (div.length > 0){
			div = div[0];
			
			var el_i = $(div).find("i");
			
			for (var i = 0; i < el_i.length; i++){
				if (_rating_.rating >= i + 1){
					$(el_i[i]).addClass("col-rating-yes").removeClass("col-rating-no");
				} else {
					$(el_i[i]).addClass("col-rating-no").removeClass("col-rating-yes");
				}
			}
		}	
		
		var span = $("#col-rating-user span");
		if (span.length > 0){
			span = span[0];
			if (_rating_.rating > 0){
				$(span).html("Your rating " + _rating_.rating +  "/5");
			} else {
				$(span).html("Your rating");
			}
		}	

@endif			
	}
	
	var createRatingEventHandlers = function(){
@if (Auth::user())
		/* event handlers */
		$(".col-done").on("mouseenter",function(){
			$(this).addClass("col-done-yes-hover").removeClass("col-done-no");
		});
		
		$(".col-done").on("mouseleave",function(){
			$(this).addClass("col-done-no").removeClass("col-done-yes-hover");
		});
		
		$(".col-done").on("click",function(){	
			if (_rating_.done == 0){
				_rating_.done_count++;
				_rating_.done = 1;
				
				showRating();	
				
				$.ajax({
					type: "POST",
					url : "/ajax/col/{{$col->ColID}}",
					data: {"done": true},
					dataType : 'json',
					success : function(data) {							
					}
				});
			}
		});

		$(".col-rating").on("mouseenter",function(){
			$(this).addClass("col-rating-yes-hover").removeClass("col-rating-no-hover");//.removeClass("col-rating-no");
			$(this).prevAll().addClass("col-rating-yes-hover").removeClass("col-rating-no-hover");//.removeClass("col-rating-no");
			$(this).nextAll().addClass("col-rating-no-hover").removeClass("col-rating-yes-hover");//.removeClass("col-rating-no");
		});
		
		$(".col-rating").on("mouseleave",function(){
			$(this).removeClass("col-rating-yes-hover col-rating-no-hover");
			//$(this).prevAll().addClass("col-rating-no").removeClass("col-rating-yesno");
			$(this).siblings().removeClass("col-rating-yes-hover col-rating-no-hover");
		});
		
		$(".col-rating").on("click",function(){
			var rating = $(this).attr("data-rating");
			if (!rating) return;
			
			rating = +rating;
			
			if (rating < 1) return;
			if (rating > 5) return;
			
			
			var rating_sum = _rating_.rating_count * _rating_.rating_avg;
			
			if (_rating_.rating > 0){
				rating_sum -= _rating_.rating ;
				rating_sum += rating;
			} else {
				rating_sum += rating;
				_rating_.rating_count++;
			}
			
			_rating_.rating = rating;
			_rating_.rating_avg = rating_sum/_rating_.rating_count;
			
			showRating();	
			
			/*var el = $(this);
			el.addClass("col-rating-yes").removeClass("col-rating-no col-rating-no-hover col-rating-yes-hover");
			el.prevAll().addClass("col-rating-yes").removeClass("col-rating-no col-rating-no-hover col-rating-yes-hover");
			el.nextAll().addClass("col-rating-no").removeClass("col-rating-yes col-rating-no-hover col-rating-yes-hover");	
			el.parent().parent().find(".col-rating-value").html("Your rating " + rating + "/5");*/
			
			$.ajax({
				type: "POST",
				url : "/ajax/col/{{$col->ColID}}",
				data: {"rating": rating},
				dataType : 'json',
				success : function(data) {			
				}
			});
		});
@endif			
	}
	
	
	var getRating = function(){
		$.ajax({
			type: "GET",
			url : "/rating/{{$col->ColIDString}}",
			dataType : 'json',
			success : function(data) {		
				if (data.length > 0){					
					_rating_ = data[0];
					_rating_.done_count = +_rating_.done_count;
					_rating_.done = +_rating_.done;
					_rating_.rating_count = +_rating_.rating_count;
					_rating_.rating = +_rating_.rating;
					_rating_.rating_avg = +_rating_.rating_avg;
					
					showRating();		
					createRatingEventHandlers();
				}
			}
		});
	}

	$(document).ready(function() {
		showCovers("{{$col->ColIDString}}",{{$hasCoverPhoto}});
		getColsNearby({{$col->ColID}});
		getPassages({{$col->ColID}});
		getPrevNextCol({{$col->Number}});
		getTopStats({{$col->ColID}});
		getBanners({{$col->ColID}});
		
		getRating();
		

		

	});

</script>

<?php
	$double_name = false;
	$colname = $col->Col;	
	
	// if slash is multi-language separator then replace slash by break
	if (strpos($col->Aliases,$col->Col) == false) {
		$colname = str_replace('/','<br/>',$colname);
		$double_name = true;
	}
	
	//aliases
	$aliases = explode(';',$col->Aliases);
	$aliases_str = "";
	for($i = 0; $i < count($aliases); $i++)
	{
		if (strlen($aliases[$i]) > 0)
		{
			if (!strstr($col->Col,$aliases[$i]))
			{
				if (strlen($aliases_str) > 0) { $aliases_str .= ", "; }
				$aliases_str .= $aliases[$i];
			}
		}
	}

	//create country string(s)
	$country1 = $col->Country1;
	$country2 = $col->Country2;
	
	$region1 = $col->Region1;
	$region2 = $col->Region2;
	
	$subregion1 = $col->SubRegion1;
	$subregion2 = $col->SubRegion2;
	
	if ($country2)
	{
		if ($region2)
		{
			$country2 .= ", " . $region2;
			
			if ($subregion2)
			{
				$country2 .= " (" . $subregion2 . ")";
			}
		}
	}
	
	if ($region1)
	{
		$country1 .= ", " . $region1;
	
		if ($subregion1)
		{
			$country1 .= " (" . $subregion1;
			
			if ($subregion2 && !$region2)
			{
				$country1 .= ", ". $subregion2 . ")";
			}
			else
			{
				$country1 .= ")";
			}
		}
		
		if ($region2 && !$country2)
		{
			$country1 .= ", " . $region2;
			
			if ($subregion2)
			{
				$country1 .= " (" . $subregion2 . ")";
			}		
		}	
	}
	
	//done
	$done = false;
	$col_done_class = "col_done_no";
	if (Auth::user() && !is_null($usercol)){
		if ($usercol->pivot->Done == 1){
			$done = true;
			$col_done_class = "col-done-yes";
		}
	}
	
	//rating
	$rating = 0;
	$avg_rating = 3.8;
	if (Auth::user() && !is_null($usercol)){
		$rating = $usercol->pivot->Rating;
	}
?>

<main role="main" class="bd-content">
	<div class="d-flex w-100 p-0 m-0 border-bottom">
@if($col->CoverPhotoPosition)
	@if($col->CoverPhotoPosition2)
		<div class="colimage w-100 w-xs-50 p-0" style='background-position: 50% {{ $col->CoverPhotoPosition}}%'></div>
		<div class="colimage2 w-100 w-xs-50 p-0 d-none d-sm-block border-left" style='background-position: 50% {{ $col->CoverPhotoPosition2}}%'></div>
	@else
		<div class="colimage w-100 p-0" style='background-position: 50% {{ $col->CoverPhotoPosition}}%'></div>
	@endif
@else
		<div class="colimage w-100 p-0 d-flex align-items-center justify-content-around" style='background-position: 50% 28%'>
			<small>No photo available yet. You're welcome to send your own photo to <a href="mailto:cyclingcols@gmail.com">cyclingcols@gmail.com</a>!</small>
		</div>
@endif
	</div>
	<div class="d-flex w-100 p-0 m-0 border-bottom justify-content-between align-items-center">
		<div class="d-none d-sm-block px-3 py-2 w-25">
@if ($col->PanelURL)
			<img class="panel" src="/images/{{$col->PanelURL}}" />
@else
			<span class="px-5"></span>
@endif
		</div>
		<div class="p-2 w-100 w-sm-75">
			<h4 class="font-weight-light m-0 p-1">{!!html_entity_decode($colname)!!}</h4>
			@if (strlen($aliases_str) > 0)
			<div class="line-height-1 px-1 pb-1"><small class="text-secondary">({{$aliases_str}})</small></div>
			@endif		
			<div class="d-flex align-items-baseline flex-wrap p-1">
				<div class="badge badge-altitude mr-2 font-weight-light">{{$col->Height}}m</div>	
				<div class="mr-2 font-weight-light">
					<img src="/images/flags/{{$col->Country1}}.gif" class="flag"/> 
					{{$country1}}
				</div>
				@if ($country2)	
				<div class="font-weight-light">
					<img src="/images/flags/{{$col->Country2}}.gif" class="flag"/> 
					{{$country2}}
				</div>
				@endif
			</div>
		</div>		
	</div>			
	<div class="cc-col-user w-100 p-2 d-flex bg-dark text-white align-items-center">
		<div id="col-rating-count" class="w-25">
			25 users climbed this col
		</div>
		
		<div id="col-rating-avg" class="w-25">
			<div class="d-inline-block">
				<i class="col-rating-avg no-pointer fas fa-star col-rating-avg-no"></i>
				<i class="col-rating-avg no-pointer fas fa-star col-rating-avg-no"></i>
				<i class="col-rating-avg no-pointer fas fa-star col-rating-avg-no"></i>
				<i class="col-rating-avg no-pointer fas fa-star col-rating-avg-no"></i>
				<i class="col-rating-avg no-pointer fas fa-star col-rating-avg-no"></i>
			</div>
			<span class="col-rating-value"></span>
		</div>
	@auth
		<div id="col-rating-done" class="w-25">
			<i class="col-done fas fa-check col-done-no"></i>
			<span class="col-done-value ml-1"></span>
		</div>
		<div id="col-rating-user" class="w-25">
			<div class="d-inline-block">
				<i data-rating="1" class="col-rating no-pointer fas fa-star col-rating-no"></i>
				<i data-rating="2" class="col-rating no-pointer fas fa-star col-rating-no"></i>
				<i data-rating="3" class="col-rating no-pointer fas fa-star col-rating-no"></i>
				<i data-rating="4" class="col-rating no-pointer fas fa-star col-rating-no"></i>
				<i data-rating="5" class="col-rating no-pointer fas fa-star col-rating-no"></i>
			</div>
			<span class="col-rating-value"></span>	
		</div>
	@else
		<div class="w-50">
			<a href="/login"/>Login</a> to rate this col
		</div>
	@endauth
	</div>
</main>
@stop