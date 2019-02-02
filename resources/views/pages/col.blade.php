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

	$(document).ready(function() {
		showCovers("{{$col->ColIDString}}",{{$hasCoverPhoto}});
		getColsNearby({{$col->ColID}});
		getPassages({{$col->ColID}});
		getPrevNextCol({{$col->Number}});
		getTopStats({{$col->ColID}});
		getBanners({{$col->ColID}});
		
		$(".col_done").on("mouseenter",function(){
			$(this).addClass("col_done_yes_hover").removeClass("col_done_no");
		});
		
		$(".col_done").on("mouseleave",function(){
			$(this).addClass("col_done_no").removeClass("col_done_yes_hover");
		});
		
		$(".col_done").on("click",function(){	
			var el = $(this);
			el.addClass("col_done_yes").removeClass("col_done_no col_done_yes_hover");	
			
			$.ajax({
				type: "POST",
				url : "/ajax/col/{{$col->ColID}}",
				data: {"done": true},
				dataType : 'json',
				success : function(data) {							
				}
			});
		});
		
		$(".col_rating").on("mouseenter",function(){
			$(this).addClass("col_rating_yes_hover").removeClass("col_rating_no_hover");//.removeClass("col_rating_no");
			$(this).prevAll().addClass("col_rating_yes_hover").removeClass("col_rating_no_hover");//.removeClass("col_rating_no");
			$(this).nextAll().addClass("col_rating_no_hover").removeClass("col_rating_yes_hover");//.removeClass("col_rating_no");
		});
		
		$(".col_rating").on("mouseleave",function(){
			$(this).removeClass("col_rating_yes_hover col_rating_no_hover");
			//$(this).prevAll().addClass("col_rating_no").removeClass("col_rating_yesno");
			$(this).siblings().removeClass("col_rating_yes_hover col_rating_no_hover");
		});
		
		$(".col_rating").on("click",function(){
			var rating = $(this).attr("data-rating");
			
			if (rating < 1) return;
			if (rating > 5) return;
			
			var el = $(this);
			el.addClass("col_rating_yes").removeClass("col_rating_no col_rating_no_hover col_rating_yes_hover");
			el.prevAll().addClass("col_rating_yes").removeClass("col_rating_no col_rating_no_hover col_rating_yes_hover");
			el.nextAll().addClass("col_rating_no").removeClass("col_rating_yes col_rating_no_hover col_rating_yes_hover");	
			
			$.ajax({
				type: "POST",
				url : "/ajax/col/{{$col->ColID}}",
				data: {"rating": rating},
				dataType : 'json',
				success : function(data) {			
				}
			});
		});
	});

</script>
<style>
.col_done {
	display: inline-block;
	font-size: 20px;
	cursor: pointer;
}
.col_done_no {
	color: #666;
}
.col_done_yes_hover {
	color: #ff8000;
}
.col_done_yes {
	color: #f00;
}
.col_rating {
	display: inline-block;
	font-size: 20px;
	cursor: pointer;
}
.col_rating_no {
	color: #666;
}
.col_rating_yes {
	color: #f00;
}
.col_rating_no_hover {
	color: #666!important;
}
.col_rating_yes_hover {
	color: #ff8000!important;
}
</style>
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
	$col_done_class = "col_done_no";
	if (!is_null($user) && !is_null($usercol)){
		if ($usercol->pivot->Done == 1){
			$col_done_class = "col_done_yes";
		}
	}
	
	//rating
	$rating = 0;
	if (!is_null($user) && !is_null($usercol)){
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
	<div class="d-flex w-100 p-0 m-0 border-bottom">
		<div class="d-none d-sm-block p-3">
@if ($col->PanelURL)
			<img class="panel" src="/images/{{$col->PanelURL}}" />
@else
			<span class="px-5"></span>
@endif
		</div>
		<div class="p-2">
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
		
		
	</div>
</main>
@stop