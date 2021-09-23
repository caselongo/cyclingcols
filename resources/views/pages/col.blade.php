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

<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>
<script type="text/javascript">	
		
	$(document).ready(function() {
		initMap();
		
		showCovers("{{$col->ColIDString}}",{{$col->CoverPhotoPosition}});
		
@auth
		getUser();
		//getLists();
			
		$('#btn-unclaim-cancel').on('click', function (event) {
			$('#modal-confirm').modal('hide');
		});
			
		$('#btn-unclaim-okay').on('click', function (event) {	
			_climbed.climbed = false;
			
			showUser();	
			
			deleteUserCol("{{$col->ColIDString}}", function(){
				getUsers();
				$('#modal-confirm').modal('hide');
			});
		});
@endauth

		getColsNearby();
		getFirst($("#col-first"),5);
		getTopStats("{{$col->ColIDString}}",null);
		getBanners("#ads","{{$col->ColIDString}}",3,true);
		getUsers();	
			
		$('#modal-first').on('show.bs.modal', function (event) {
			getFirst($("#modal-first").find(".modal-body"));
		});
		
		
		$(".profile-print").click(function() { 
			printContent($(this).parents(".col-box")[0]); 
		});
		
		dateSelectCallback = function(){
			getUsers();
		};
	});
	
	var initMap = function(){
		
		var lat = {{$col->Latitude/1000000}};
		var lng = {{$col->Longitude/1000000}};

		var mapOptions = {
			attributionControl: false,
			zoomControl: false,
			dragging: false
		};
		var map = L.map('map', mapOptions).setView([lat, lng], 6);
		map.scrollWheelZoom.disable();
		
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);
		
		L.control.attribution({
			prefix: false
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
	
	var showUser = function(){
		/*  user climbed */
		var div = $("#col-climbed");
		if (div.length > 0){
			div = div[0];
			
			var el_i = $(div).find(".col-climbed");
			
			if (el_i.length == 1){
				if (_climbed.climbed){
					$(el_i).addClass("col-climbed-yes-edit").removeClass("col-climbed-no").prop("title","Click to unclaim this col");
				} else {
					$(el_i).addClass("col-climbed-no").removeClass("col-climbed-yes-edit").prop("title","Click to claim this col");
				}		
			}
				
			var span_value = $(div).find(".col-climbed-value");
			var span_date = $(div).find(".col-climbed-date");
			
			if (span_value.length == 1 && span_date.length == 1){	
				if (_climbed.climbed){
					$(span_value).html("You climbed this col on");
					if (_climbed.climbedAtStravaActivityIDs != null){
						$html = _climbed.climbedAtText + '<span class="pointer ml-2" onclick="openActivities(&quot;' + _climbed.climbedAtStravaActivityIDs + '&quot;); return false;"><img src="/images/strava.png"/ class="strava-icon" style="width: 15px"></span>';
						$(".col-climbed-strava").html($html);
					} else if (_climbed.climbedAt){
						$(".col-climbed-date").data("date",_climbed.climbedAtText);
						$(".col-climbed-date").html(getHumanDate(_climbed.climbedAtText));
					} else {
						$(".col-climbed-date").data("date", null);
						$(".col-climbed-date").html("[add date]");
					}
					$(span_date).show();
				} else {
					$(span_value).html("You did not climb this col");
					$(span_date).hide();
				}
			}
		}	
	}
	
	var createUserEventHandlers = function(){
		/* event handlers */
		$(".col-climbed").on("mouseenter",function(){
			$(this).addClass("col-climbed-yes-edit-hover").removeClass("col-climbed-no");
		});
		
		$(".col-climbed").on("mouseleave",function(){
			$(this).addClass("col-climbed-no").removeClass("col-climbed-yes-edit-hover");
		});
		
		$(".col-climbed").on("click",function(){	
			if (!_climbed.climbed){
				_climbed.climbed = true;
				
				showUser();	
				
				saveUserCol("{{$col->ColIDString}}", _climbed.climbedAtText, function(){
					getUsers();
				});
			} else {
				$('#modal-confirm').modal('show');
			}
		});	
	}
	
	var getUser = function(){
		$.ajax({
			type: "GET",
			url : "/service/col/athlete/{{$col->ColIDString}}",
			dataType : 'json',
			success : function(data) {
				if (data){
					_climbed = data;
					if (_climbed.climbedAt) _climbed.climbedAt = new Date(_climbed.climbedAt);
					
					showUser();		
					createUserEventHandlers();
				}
			},
			cache: false
		});
	}
	
	var getLists = function(){
		$.ajax({
			type: "GET",
			url : "/service/col/lists/{{$col->ColIDString}}",
			dataType : 'json',
			success : function(data) {
				$("#col-lists").html(data.html);
		
				$('.listAdd,.listRemove').on('click', function (e) {
					$(e.target).tooltip('hide');

					var slug = e.target.getAttribute("data-list");

					//save new list
					var url = "/service/listcol/";
					if ($(this).hasClass("listAdd")){
						url += "create";
					} else {
						url += "delete";
					}

					$.ajax({
						type: "POST",
						url : url,
						dataType : 'json',
						data: {
							list: slug,
							sectionid: 0,
							col: "{{$col->ColIDString}}"
						},
						success : function(result) {	
							if (result.success){
								getLists();
							}
						}
					});	
				});


			},
			cache: false
		});
	}
	
	var getColsNearby = function(){
		$.ajax({
			type: "GET",
			url : "/service/col/nearby/{{$col->ColIDString}}",
			dataType : 'json',
			success : function(result) {	
				if (result.success){
					$("#col-nearby").html(result.html);		
				}
			}
		});		
	}
	
	var getFirst = function(el,limit) {
		var url = "/service/col/first/{{$col->ColIDString}}";
		
		if (limit){
			url += "/" + limit;
		}
		
		$.ajax({
			type: "GET",
			url: url,
			dataType : 'json',
			success : function(result) {	
				if (result.success){
					$(el).html(result.html);		
				}
				
				if (limit){
					if (result.count > limit) {
						$("#col-first-all").parent().removeClass("d-none");
					}
				}
			}
		})
	}
	
	var _users_ = null;
		
	var getUsers = function() {
		$.ajax({
			type: "GET",
			url : "/service/col/athletes/{{$col->ColIDString}}",
			dataType : 'json',
			success : function(result) {
				if (result.success){
					$("#col-users").html(result.html);		
				}
			}
		});
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
	
	var showCovers = function(colIDString,coverPhotoPosition) {
		//load images
		var w = $(window).width();
		var h = $(window).height();
		var wh = w;
		if (h > w) wh = h;
		var path = "{{env('IMAGES_PATH', '/images2')}}" + "/covers/";
		
		var image2 = ($(".colimage2").length > 0);
		
		if (wh <= 680){	 
			path += "small/";
		} else if (wh <= 1024){	 
			path += "medium/";
		} else if (image2){  
			path += "medium/";
		}
		
		if (coverPhotoPosition){
			$(".colimage").css("background-image","url(\"" + path + colIDString + ".jpg\")");
			if (image2){  
				$(".colimage2").css("background-image","url(\"" + path + colIDString + "_2.jpg\")");
			}
		} else {
			$(".colimage").css("background-image","url(\"" + path + "_Dummy.jpg\")");		
		}
	}

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
	$country1 = "<a href='/stats/_" . $col->ColIDString . "/c'>" . $col->Country1 . "</a>";
	$country2 = "";
	if (!is_null($col->Country2)){
		$country2 = "<a href='/stats/_" . $col->ColIDString . "/cc'>" . $col->Country2 . "</a>";
	}
	
	$region1 = "";
	if (!is_null($col->Region1)){
		$region1 = "<a href='/stats/_" . $col->ColIDString . "/r'>" . $col->Region1 . "</a>";
	}
	$region2 = "";
	if (!is_null($col->Region2)){
		$region2 = "<a href='/stats/_" . $col->ColIDString . "/rr'>" . $col->Region2 . "</a>";
	}
	
	$subregion1 = "";
	if (!is_null($col->SubRegion1)){
		$subregion1 = "<a href='/stats/_" . $col->ColIDString . "/s'>" . $col->SubRegion1 . "</a>";
	}
	$subregion2 = "";
	if (!is_null($col->SubRegion2)){
		$subregion2 = "<a href='/stats/_" . $col->ColIDString . "/ss'>" . $col->SubRegion2 . "</a>";
	}
	
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

	// social
	$social_source = "CyclingCols";
	$social_source_url = "https://www.cyclingcols.com";
	$social_url = "https://www.cyclingcols.com/col/". $col->ColIDString;
	$social_title = $col->Col;
	$social_media = $profiles[0]->FileName . ".gif";
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
			<img class="panel" src="{{env('IMAGES_PATH','/images')}}/{{$col->PanelURL}}" />
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
				<div class="badge badge-elevation mr-2 font-weight-light">{{$col->Height}}m</div>	
				<div class="mr-2 font-weight-light">
					<img src="/images/flags/{{$col->Country1IDString}}.gif" class="flag"/> 
					{!!$country1!!}
				</div>
				@if ($country2)	
				<div class="font-weight-light">
					<img src="/images/flags/{{$col->Country2IDString}}.gif" class="flag"/> 
					{!!$country2!!}
				</div>
				@endif
			</div>
		</div>		
	</div>	
	<!--user-->		
	<div class="w-100 px-3 py-1 d-flex bg-dark text-white text-small-75 align-items-center flex-wrap">
		<div class="w-100 w-sm-50 w-md-25 social">
			<!-- Facebook Share Button -->
			<a class="fbtn share facebook" href="https://www.facebook.com/sharer/sharer.php?u={{$social_url}}" title="Share this col on Facebook" data-toggle="tooltip"><i class="fa fa-facebook"></i></a> 
			
			<!-- Google Plus Share Button -->
			<a class="fbtn share gplus" href="https://plus.google.com/share?url={{$social_url}}" title="Share this col on Google Plus" data-toggle="tooltip"><i class="fa fa-google-plus"></i></a> 
			
			<!-- Twitter Share Button -->
			<a class="fbtn share twitter" href="https://twitter.com/intent/tweet?text={{$social_title}}&amp;url={{$social_url}}&amp;via=cyclingcols" title="Share this col on Twitter" data-toggle="tooltip"><i class="fa fa-twitter"></i></a> 
		
			<!-- Pinterest Share Button -->
			<a class="fbtn share pinterest" href="http://pinterest.com/pin/create/button/?url={{$social_url}}&amp;description={{$social_title}}&amp;media={{$social_media}}" title="Share this col on Pinterest" data-toggle="tooltip"><i class="fa fa-pinterest"></i></a>
		
			<!-- LinkedIn Share Button -->
			<a class="fbtn share linkedin" href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{$social_url}}&amp;title={{$social_title}}&amp;source={{$social_source_url}}/" title="Share this col on LinkedIn" data-toggle="tooltip"><i class="fa fa-linkedin"></i></a>

			<div class="d-inline-block ml-2 pt-1">
				<form class="donate m-0" align="center" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" title="Show your appreciation and support the continuity of CyclingCols." data-toggle="tooltip">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="6ME8CQEG33GT4">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
				</form>
			</div>
		</div>
	@auth
		<div id="col-lists" class="w-100 w-sm-100 w-md-50">
		</div>
		<div id="col-climbed" class="w-100 w-sm-50 w-md-25">
			<i class="col-climbed fas fa-check col-climbed-no" data-toggle="tooltip"></i>						
			<span class="col-climbed-value ml-1"></span>
			<span class="col-climbed-date ml-auto text-small-75 text-right" data-colidstring="{{$col->ColIDString}}"></span>
			<span class="col-climbed-strava ml-auto text-small-75 text-right" style="flex: 0 0 15px;"></span>
		</div>
	@else
		<div class="w-100 w-sm-100 w-md-50 text-center">
		</div>
		<div class="w-100 w-sm-50 w-md-25">
			<a href="/login">Login</a> to claim this col
		</div>
	@endauth
	</div>
	<!--content-->
	<div class="w-100 d-flex align-items-start flex-wrap">
		<div class="w-100 w-lg-75 px-3 pb-2"><!--profiles-->
		
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
		
		$cat_dist5 = getStatCat(13,$profile->Distance5);
		$class_dist5 = "";
		if ($cat_dist5 == 2) $class_dist5 = "color-2";
		else if ($cat_dist5 == 1) $class_dist5 = "color-1";
		
		$cat_dist10 = getStatCat(14,$profile->Distance10);
		$class_dist10 = "";
		if ($cat_dist10 == 2) $class_dist10 = "color-2";
		else if ($cat_dist10 == 1) $class_dist10 = "color-1";
		
		$cat_max1 = getStatCat(15,$profile->MaxPerc1);
		$class_max1 = "";
		if ($cat_max1 == 2) $class_max1 = "color-2";
		else if ($cat_max1 == 1) $class_max1 = "color-1";
		
		$cat_max5 = getStatCat(16,$profile->MaxPerc5);
		$class_max5 = "";
		if ($cat_max5 == 2) $class_max5 = "color-2";
		else if ($cat_max5 == 1) $class_max5 = "color-1";
?>
			<div id="{{$profile->FileName}}" class="col-box w-100 mb-3 container p-0">
				<div class="border-bottom p-2 d-flex align-items-baseline flex-wrap">
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
		
					<i class="profile-print fas fas-grey fa-print ml-auto text-small-75" title="Print" data-toggle="tooltip"></i>
				</div>
				<div>
					<img class="profile-img pointer" src="{{env('PROFILES_PATH','/profiles')}}/{{$profile->FileName}}.gif" data-toggle="modal" data-target="#modalProfile" data-profile="{{$profile->FileName}}" data-mode="light"/>
				</div>
				<div class="p-0 text-small-75 row m-0">
					<div class="col-xl-1 col-sm-2 col-4 px-1 py-2">
						<div class="stat1 px-2 py-1 border text-center" title="Distance" data-toggle="tooltip">
							<i class="fas fas-grey fa-arrows-alt-h no-pointer {{$class_dist}} pr-1"></i>
							<div class="pt-1">{{formatStat(1,$profile->Distance)}}</div>
						</div>
					</div>
					<div class="col-xl-1 col-sm-2 col-4 px-1 py-2">
						<div class="stat2 px-2 py-1 border text-center" title="Elevation Gain" data-toggle="tooltip">
							<i class="fas fas-grey fa-arrows-alt-v no-pointer {{$class_gain}} pr-1"></i>
							<div class="pt-1">{{formatStat(2,$profile->HeightDiff)}}</div>
						</div>
					</div>
					<div class="col-xl-1 col-sm-2 col-4 px-1 py-2">
						<div class="stat3 px-2 py-1 border text-center" title="Elevation Slope" data-toggle="tooltip">
							<i class="fas fas-grey fa-location-arrow no-pointer {{$class_avg}} pr-1"></i>
							<div class="pt-1">{{formatStat(3,$profile->AvgPerc)}}</div>
						</div>
					</div>
					<div class="col-xl-1 col-sm-2 col-4 px-1 py-2">
						<div class="stat4 px-2 py-1 border text-center" title="Maximum Slope" data-toggle="tooltip">
							<i class="fas fas-grey fa-bomb no-pointer {{$class_max}} pr-1"></i>
							<div class="pt-1">{{formatStat(4,$profile->MaxPerc)}}</div>
						</div>
					</div>
					<div class="col-xl-1 col-sm-2 col-4 px-1 py-2">
						<div class="stat5 px-2 py-1 border text-center" title="Profile Index" data-toggle="tooltip">
							<i class="fas fas-grey fa-signal no-pointer {{$class_index}} pr-1"></i>
							<div class="pt-1">{{formatStat(5,$profile->ProfileIdx)}}</div>
						</div>
					</div>
					<div class="col-xl-1 col-sm-2 col-4"></div>
					<div class="col-xl-1 col-sm-2 col-4 px-1 py-2">
						<div class="stat13 px-2 py-1 border text-center" title="Distance Above 5%" data-toggle="tooltip">
							<i class="fas fas-grey fa-arrows-alt-h no-pointer {{$class_dist5}}"></i>
							<div class="d-inline {{$class_dist5}} pr-1 text-small-60">5%</div>
							<div class="pt-1">{{formatStat(13,$profile->Distance5)}}</div>
						</div>
					</div>
					<div class="col-xl-1 col-sm-2 col-4 px-1 py-2">
						<div class="stat14 px-2 py-1 border text-center" title="Distance Above 10%" data-toggle="tooltip">
							<i class="fas fas-grey fa-arrows-alt-h no-pointer {{$class_dist10}}"></i>
							<div class="d-inline {{$class_dist10}} pr-1 text-small-60">10%</div>
							<div class="pt-1">{{formatStat(14,$profile->Distance10)}}</div>
						</div>
					</div>
					<div class="col-xl-1 col-sm-2 col-4 px-1 py-2">
						<div class="stat15 px-2 py-1 border text-center" title="Steepest 1km" data-toggle="tooltip">
							<i class="fas fas-grey fa-bomb no-pointer {{$class_max1}}"></i>
							<div class="d-inline {{$class_max1}} pr-1 text-small-60">1k</div>
							<div class="pt-1">{{formatStat(15,$profile->MaxPerc1)}}</div>
						</div>
					</div>
					<div class="col-xl-1 col-sm-2 col-4 px-1 py-2">
						<div class="stat16 px-2 py-1 border text-center" title="Steepest 5km" data-toggle="tooltip">
							<i class="fas fas-grey fa-bomb no-pointer {{$class_max5}}"></i>
							<div class="d-inline {{$class_max5}} pr-1 text-small-60">5k</div>
							<div class="pt-1">{{formatStat(16,$profile->MaxPerc5)}}</div>
						</div>
					</div>
				</div>
                @include('sub.profilesimilar', ['fileName' => $profile->FileName])
			</div>
		
<?php				
	}
?>
		</div>
		<div class="w-100 w-lg-25 px-3 pl-md-0 py-3"><!--sidebar-->
			<div class="col-box w-100 mb-1">
				<div id="map" class="col-map">
				</div>
			</div>
			<div class="w-100 mb-1 text-small-75 text-muted text-center font-weight-light p-1">
				Location incorrect? Or something else missing? Let me know at <a href="mailto:info@cyclingcols.com">info@cyclingcols.com</a>.
			</div>
			<div class="col-box w-100 mb-2">
				<div>
					<h6 class="font-weight-light p-2 m-0 border-bottom">Cols Nearby</h6>
					<div id="col-nearby" class="font-weight-light px-2 py-1">
					</div>
				</div>				
			</div>
			<div id="ads" class="w-100 mb-2 text-center p-1">			
			</div>
			<div class="col-box w-100 mb-3">
				<div class="p-2 border-bottom d-flex align-items-center">
					<h6 class="font-weight-light m-0">First On Top</h6>
					<div class="ml-auto d-none" tabindex="0" role="button" data-toggle="modal" data-target="#modal-first">
						<i id="col-first-all" class="fas fas-grey fa-search-plus" title="show all" data-toggle="tooltip"></i>
					</div>
				</div>
				<div id="col-first" class="font-weight-light px-2 py-1">
				</div>
			</div>			
			<div class="col-box w-100 mb-3">
				<div class="p-2 border-bottom d-flex align-items-center">
					<h6 class="font-weight-light m-0">Most Recently Climbed By</h6>
				</div>
				<div id="col-users" class="font-weight-light px-2 py-1">
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
@auth
<div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="modal-confirm-label" aria-hidden="true">
	<div class="d-flex align-items-center justify-content-around h-100 text-small-90 text-center" style="pointer-events: none">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div>
						<h6 class="modal-title font-weight-light" id="modal-confirm-label">Unclaim This Col?</h6>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body p-1 font-weight-light" style="max-height: 80vh; overflow-y: auto;">
					<div class="p-2">Are you sure you want to unclaim this col?</div>
					<div class="d-flex justify-content-around">
						<button type="button" class="btn btn-primary" id="btn-unclaim-okay">
							Okay
						</button>
						<button type="button" class="btn btn-secondary" id="btn-unclaim-cancel">
							Cancel
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endauth
@stop

@include('includes.profilemodal')
