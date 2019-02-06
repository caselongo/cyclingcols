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
		map.scrollWheelZoom.disable();
		
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);
		
		var icon = L.icon({
			iconUrl: '/images/ColRed.png',
			iconAnchor: [16,35]
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
					type: "GET",
					url : "/user/col",
					data: {
						"colIDString": "{{$col->ColIDString}}",
						"done": true
					},
					dataType : 'json',
					success : function(data) {							
					}
				});
			}
		});

		$(".col-rating").on("mouseenter",function(){
			$(this).addClass("col-rating-yes-hover").removeClass("col-rating-no-hover");
			$(this).prevAll().addClass("col-rating-yes-hover").removeClass("col-rating-no-hover");
			$(this).nextAll().addClass("col-rating-no-hover").removeClass("col-rating-yes-hover");
		});
		
		$(".col-rating").on("mouseleave",function(){
			$(this).removeClass("col-rating-yes-hover col-rating-no-hover");
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
			
			$.ajax({
				type: "GET",
				url : "/user/col",
				data: {
					"colIDString": "{{$col->ColIDString}}",
					"rating": rating
				},
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
			url : "/col/rating/{{$col->ColIDString}}",
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
	
	var getColsNearby = function(){
		$.ajax({
			type: "GET",
			url : "/col/nearby/{{$col->ColIDString}}",
			dataType : 'json',
			success : function(data) {		
				for(var i = 0; i < data.length; i++) {	
					var dis = parseInt(Math.round(parseFloat(data[i].Distance/1000)));
					var int_dir = parseInt(data[i].Direction); 
					var dir;
					
					if (int_dir <= 22) { dir = "South"; }
					else if (int_dir <= 67) { dir = "South-West"; }
					else if (int_dir <= 112) { dir = "West"; }
					else if (int_dir <= 157) { dir = "North-West"; }
					else if (int_dir <= 202) { dir = "North"; }
					else if (int_dir <= 247) { dir = "North-East"; }
					else if (int_dir <= 292) { dir = "East"; }
					else if (int_dir <= 337) { dir = "South-East"; }
					else { dir = "South"; }
				
					var html = '<div class="d-flex px-2 text-small-90">';
					html += '<div class=""><a href="/col/' + data[i].ColIDString + '">' + data[i].Col + '</a></div>';
					html += '<div class="ml-auto text-small-75 text-right" style="flex-basis: 60px;">' + dis + ' km<img class="direction ml-1" src="/images/' + dir + '.png"/></div>';	
					html += '</div>';
					
					$("#col-nearby").append(html);
				}
			}
		});		
	}
	
	var showFirst = function(el,limit){
		if (el == null) return;
		
		el = $(el);
		if (el.length == 0) return;
		el = el[0];
		
		$(el).empty();	
		
		if (_first_.length > 0){
		
			for(var i = 0; i < _first_.length; i++){
				var d = _first_[i];
				
				var display = "d-flex";
				if (i >= limit) {display = "d-none";}
				
				var html = '<div class="px-2 text-small-90 align-items-baseline ' + display + '">';
				html += '<div class="d-flex text-small-75" style="flex-basis: 80px;">';
				html += '<div class="">' + d.race_short + '</div>'; 
				html += '<div class="pl-1">' + d.Edition + '</div>';
				html += '</div>'; 
				html += '<div class="d-flex w-100 align-items-center">'; 
				html += '<div class="px-1">' + d.person + '</div>';
				if (d.flag == true) {
					html += "<img class=\"flag ml-auto\" src='/images/flags/small/" + d.NatioAbbr.toLowerCase() + ".gif' title='" + d.Natio + "'/>";
				}
				html += '</div></div>'; 
				
				$(el).append(html);	
			}
		} else {
			$(el).html("<span class=\"text-small-75 px-2\">Never climbed in Tour, Giro or Vuelta</span>");	
		}
	}
	
	var _first_ = null;
	
	var getFirst = function() {
		$.ajax({
			type: "GET",
			url : "/col/first/{{$col->ColIDString}}",
			dataType : 'json',
			success : function(data) {	
				if (data.length > 0) {
				
					for(var i = 0; i < data.length; i++) {	
						var d = data[i];
					
						d.race = ""; 
						d.race_short = "";
					
						switch(parseInt(data[i].EventID)) {
							case 1: d.race = "Tour de France"; d.race_short = "Tour"; break;
							case 2: d.race = "Giro d'Italia"; d.race_short = "Giro"; break;
							case 3: d.race = "Vuelta a España"; d.race_short = "Vuelta"; break;
						}
						
						d.person = d.Person;
						d.flag = true;
						if (d.Neutralized == "1") {d.person = "-neutralized-"; d.flag = false;}
						else if (d.Cancelled == "1") {d.person = "-cancelled-"; d.flag = false;}
						else if (d.NatioAbbr == "") {d.person = "-cancelled-"; d.flag = false;}
						
						if (d.person == null) {d.person = ""; d.flag = false;}
					}
					
				}
							
				_first_ = data;
				
				showFirst($("#col-first"),5);
						
				if (data.length <= 5) {
					$("#col-first-all").hide();
				}
			}
		})
	}
	
	var printContent = function (el){
		var title = $(el).attr("id");
		var divContents = $(el).html();
		var printWindow = window.open('', '', 'height=400,width=800');
		printWindow.document.write('<html><head><title>' + title + '</title>');
		printWindow.document.write('<link rel="stylesheet" href="/css/bootstrap.css" type="text/css">');
		printWindow.document.write('<link rel="stylesheet" href="/css/main.css" type="text/css">');
		printWindow.document.write('</head><body>');
		printWindow.document.write(divContents);
		printWindow.document.write('</body></html>');
			
		/*printWindow.document.close();*/
		printWindow.focus();
		
		setTimeout(function() { 
			printWindow.print(); 
			printWindow.close();
		}, 500);
	}
	
	$(document).ready(function() {
		showCovers("{{$col->ColIDString}}",{{$hasCoverPhoto}});
		getColsNearby();
		getFirst();
		//getPrevNextCol({{$col->Number}});
		getTopStats("{{$col->ColIDString}}",null);
		getBanners({{$col->ColID}});
		
		getRating();
			
		$('#modal-first').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);

			var modal = $(this);
			
			showFirst($(".modal-body"),1000);
		});
		
		
		$(".profile-print").click(function() { 
			printContent($(this).parents(".col-box")[0]); 
		});

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
	<!--header-->
	<div class="d-flex w-100 p-0 m-0 bg-white border-bottom justify-content-between align-items-center">
		<div class="d-none d-sm-block px-3 py-2 w-25 text-right">
@if ($col->PanelURL)
			<img class="panel" src="/images/{{$col->PanelURL}}" />
@else
			<span class="px-5"></span>
@endif
		</div>
		<div class="p-2 w-100 w-sm-75">
			<h4 class="font-weight-light m-0 pl-1">{!!html_entity_decode($colname)!!}</h4>
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
	<!--rating-->		
	<div class="cc-col-user w-100 p-2 d-flex bg-dark text-white align-items-center flex-wrap">
		<div id="col-rating-count" class="w-100 w-sm-50 w-md-25">
		</div>
		
		<div id="col-rating-avg" class="w-100 w-sm-50 w-md-25">
			<span class="col-rating-value"></span>
			<div class="d-inline-block">
				<i class="col-rating-avg no-pointer fas fa-star col-rating-avg-no"></i>
				<i class="col-rating-avg no-pointer fas fa-star col-rating-avg-no"></i>
				<i class="col-rating-avg no-pointer fas fa-star col-rating-avg-no"></i>
				<i class="col-rating-avg no-pointer fas fa-star col-rating-avg-no"></i>
				<i class="col-rating-avg no-pointer fas fa-star col-rating-avg-no"></i>
			</div>
		</div>
	@auth
		<div id="col-rating-done" class="w-100 w-sm-50 w-md-25">
			<span class="col-done-value"></span>
			<i class="col-done fas fa-check col-done-no"></i>
		</div>
		<div id="col-rating-user" class="w-100 w-sm-50 w-md-25">
			<span class="col-rating-value"></span>	
			<div class="d-inline-block">
				<i data-rating="1" class="col-rating no-pointer fas fa-star col-rating-no"></i>
				<i data-rating="2" class="col-rating no-pointer fas fa-star col-rating-no"></i>
				<i data-rating="3" class="col-rating no-pointer fas fa-star col-rating-no"></i>
				<i data-rating="4" class="col-rating no-pointer fas fa-star col-rating-no"></i>
				<i data-rating="5" class="col-rating no-pointer fas fa-star col-rating-no"></i>
			</div>
		</div>
	@else
		<div class="w-50">
			<a href="/login"/>Login</a> to rate this col
		</div>
	@endauth
	</div>
	<!--content-->
	<div class="w-100 d-flex align-items-start flex-wrap">
		<div class="w-100 w-md-75 px-3 pb-2"><!--profiles-->
		
		    <div class="font-weight-light text-small-90 py-2">
<?php 

$profile_count = 0; 
$profile_string = "";

foreach($profiles as $profile) {
	$profile_count = $profile_count + 1;
	if ($profile_count > 1) {$profile_string .= " | ";}
	$profile_string .= "<a href='#" . $profile->FileName . "'>" . $profile->Side . " (" . $profile->Start . ")</a>";
}

$profile_string = ": " . $profile_string;
if ($profile_count > 1) {$profile_string = "s" . $profile_string;}
$profile_string = $profile_count . " profile" . $profile_string;
?>
				{!!html_entity_decode($profile_string)!!}
			</div>
<?php
	foreach($profiles as $profile){
		
		$cat_dist = getStatCat(1,$profile->Distance);
		$class_dist = "";
		if ($cat_dist == 2) $class_dist = "color-2";
		else if ($cat_dist == 1) $class_dist = "color-1";
		
		$cat_gain = getStatCat(2,$profile->HeightDiff);
		$class_gain = "";
		if ($cat_gain == 2) $class_gain = "color-2";
		else if ($cat_gain == 1) $class_gain = "color-1";
		
		$cat_avg = getStatCat(3,$profile->AvgPerc);
		$class_avg = "";
		if ($cat_avg == 2) $class_avg = "color-2";
		else if ($cat_avg == 1) $class_avg = "color-1";
		
		$cat_max = getStatCat(4,$profile->MaxPerc);
		$class_max = "";
		if ($cat_max == 2) $class_max = "color-2";
		else if ($cat_max == 1) $class_max = "color-1";
		
		$cat_index = getStatCat(5,$profile->ProfileIdx);
		$class_index = "";
		if ($cat_index == 2) $class_index = "color-2";
		else if ($cat_index == 1) $class_index = "color-1";
?>
			<div id="{{$profile->FileName}}" class="col-box w-100 mb-3">
				<div class="profile-header border-bottom p-2 d-flex align-items-baseline flex-wrap">
					<div class="d-flex align-items-baseline flex-wrap">
						<span class="category category-{{$profile->Category}}">{{$profile->Category}}</span>
						<h6 class="font-weight-light mx-1">{{$col->Col}}</h6>
		@if ($profile->Side != null)
						<span class="text-small-75"><img class="direction mr-1" src="/images/{{$profile->Side}}.png"/>{{$profile->Side}}</span>
		@endif
					</div>
		@if ($profile->Start != null)
					<span class="text-small-75 px-4"><i class="fas fas-grey fa-angle-right no-pointer pr-1"></i>{{$profile->Start}}</span>
		@endif
				</div>
				<div>
					<img class="profile-img" src="/profiles/{{$profile->FileName}}.gif"/>
				</div>
				<div class="profile-footer p-0 text-small-75 d-flex">
					<div class="stat1 px-2 py-1 border m-2" title="Distance">
						<i class="fas fas-grey fa-arrows-alt-h no-pointer {{$class_dist}} pr-1"></i>
						<span>{{formatStat(1,$profile->Distance)}}</span>
					</div>
					<div class="stat2 px-2 py-1 border m-2" title="Altitude Gain">
						<i class="fas fas-grey fa-arrows-alt-v no-pointer {{$class_gain}} pr-1"></i>
						<span>{{formatStat(2,$profile->HeightDiff)}}</span>
					</div>
					<div class="stat3 px-2 py-1 border m-2" title="Average Slope">
						<i class="fas fas-grey fa-location-arrow no-pointer {{$class_avg}} pr-1"></i>
						<span>{{formatStat(3,$profile->AvgPerc)}}</span>
					</div>
					<div class="stat4 px-2 py-1 border m-2" title="Maximum Slope">
						<i class="fas fas-grey fa-bomb no-pointer {{$class_max}} pr-1"></i>
						<span>{{formatStat(4,$profile->MaxPerc)}}</span>
					</div>
					<div class="stat5 px-2 py-1 border m-2" title="Profile Index">
						<i class="fas fas-grey fa-signal no-pointer {{$class_index}} pr-1"></i>
						<span>{{formatStat(5,$profile->ProfileIdx)}}</span>
					</div>
					<div class="px-2 py-1 ml-auto d-none d-lg-inline-block">
						<i class="profile-print fas fas-grey fa-print" title="Print"></i>
					</div>
				</div>
			
			</div>
		
<?php				
	}
?>
		</div>
		<div class="w-100 w-md-25 px-3 pl-md-0 py-3"><!--sidebar-->
			<div class="col-box w-100 mb-3">
				<div id="map" class="col-map">
				</div>
				<div>
					<h6 class="font-weight-light p-2 m-0 border-bottom">Cols Nearby</h6>
					<div id="col-nearby" class="font-weight-light py-1">
					</div>
				</div>				
			</div>
			<div class="col-box w-100 mb-3">
				<div class="profs" id="profs">
					<div class="p-2 border-bottom d-flex align-items-center">
						<h6 class="font-weight-light m-0">First On Top</h6>
						<div class="ml-auto" tabindex="0" role="button" data-toggle="modal" data-target="#modal-first">
							<i id="col-first-all" class="fas fas-grey fa-search-plus" title="show all"></i>
						</div>
					</div>
					<div id="col-first" class="font-weight-light py-1">
					</div>
			</div>
		</div>
	</div>
	
</main>
<div class="modal fade" id="modal-first" tabindex="-1" role="dialog" aria-labelledby="modal-first-label" aria-hidden="true">
	<div class="d-flex align-items-center justify-content-around h-100" style="pointer-events: none">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div>
						<h6 class="modal-title font-weight-light" id="modal-first-label">First On Top</h6>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body p-1 font-weight-light" style="max-height: 80vh; overflow-y: auto;">
				</div>
			</div>
		</div>
	</div>
</div>
@stop