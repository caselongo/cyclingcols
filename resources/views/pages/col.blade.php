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
<div id="fb-root"></div>
<div class="colpage">
	@if($col->CoverPhotoPosition)
		@if($col->CoverPhotoPosition2)
		<div class="colimage col-xs-12 col-md-6" style='background-position: 50% {{ $col->CoverPhotoPosition}}%'></div>
		<div class="colimage2 hidden-xs hidden-sm col-md-6" style='background-position: 50% {{ $col->CoverPhotoPosition2}}%'></div>
		<!--<div class="colimage col-xs-12 col-md-6" style='background-image: url("/images/covers/medium/{{$col->ColIDString}}.jpg"); background-position: 50% {{ $col->CoverPhotoPosition}}%'></div>
		<div class="colimage2 hidden-xs hidden-sm col-md-6" style='background-image: url("/images/covers/medium/{{$col->ColIDString}}.jpg"); background-position: 50% {{ $col->CoverPhotoPosition2}}%'></div>
		-->
		<!--<div class="colimage hidden-xs hidden-sm col-md-6" style='background-image: url("/images/covers/{{$col->ColIDString}}.jpg"); background-position: 50% {{ $col->CoverPhotoPosition}}%'></div>
		<div class="colimage2 hidden-xs hidden-sm col-md-6" style='background-image: url("/images/covers/{{$col->ColIDString}}.jpg"); background-position: 50% {{ $col->CoverPhotoPosition2}}%'></div>
		-->
		@else
		<!--<div class="colimage hidden-xs hidden-sm col-md-12" style='background-image: url("/images/covers/{{$col->ColIDString}}.jpg"); background-position: 50% {{ $col->CoverPhotoPosition}}%'></div>
		-->
		<!--<div class="colimage col-xs-12" style='background-image: url("/images/covers/{{$col->ColIDString}}.jpg"); background-position: 50% {{ $col->CoverPhotoPosition}}%'></div>
		-->
		<div class="colimage col-xs-12" style='background-position: 50% {{ $col->CoverPhotoPosition}}%'></div>
		@endif
	@else
    <!--<div class="colimage col-xs-12" style='background-image: url("/images/covers/_Dummy.jpg"); background-position: 50% 28%'></div>
	-->
	<div class="colimage col-xs-12" style='background-position: 50% 28%'>
		<div class="nocolimage">No photo available yet. You're welcome to send your own photo to <a href="mailto:cyclingcols@gmail.com">cyclingcols@gmail.com</a>!</div>
	</div>
	@endif

    <div class="coltitlesection col-xs-12">
		<div class="col-md-3 col-sm-3 hidden-xs coltitleleft">
			@if ($col->PanelURL)
			<div class="colpanel">
				<img src="/images/{{$col->PanelURL}}" />
			</div>
			@endif
		</div>
		<div class="col-md-6 col-sm-6 col-xs-12 coltitle">
			<h2 class="colname">{!!html_entity_decode($colname)!!}</h2>
			@if ($double_name)	
			<span class="colheight moveup">
			@else
			<span class="colheight">
			@endif
			{{$col->Height}}m</span>
			@if (strlen($aliases_str) > 0)
			<h5>({{$aliases_str}})</h5>
			@endif
			<h4><img src="/images/flags/{{$col->Country1}}.gif"/> {{$country1}}</h4>
			@if ($country2)	
			<h4><img src="/images/flags/{{$col->Country2}}.gif"> {{$country2}}</h4>
			@endif
		</div>
		@if (!is_null($user))
		<div class="col-md-3 col-sm-3 col-xs-12 coluser">
			<div>
				Done <div class="col_done {{$col_done_class}} glyphicon glyphicon-check"></div>
			</div>
			<div>
				Beauty
				@for ($i = 1; $i <= 5; $i++)
					<div class="col_rating 
					@if ($i <= $rating)     
						col_rating_yes
					@else 
						col_rating_no
					@endif
					glyphicon glyphicon-star" data-rating="{{$i}}">
					</div>
				@endfor
			</div>	
		</div>
		@endif
		<div class="col-xs-12 coltitlebottom">
			<div class="col-xs-12 col-sm-6 social">
				<div class="fb-like" 
					data-href="http://www.cyclingcols.com/col/{{$col->ColIDString}}"
					data-layout="button" 
					data-action="like" 
					data-show-faces="false" 
					data-share="true"
				>
				</div>
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="{{URL::asset('col/')}}/{{$col->ColIDString}}" data-via="cyclingcols">Tweet</a>
				<form class="donate" align="center" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" title="Show your appreciation and support the continuity of CyclingCols.">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="6ME8CQEG33GT4">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
				</form>
				</div>
			<div class="hidden-xs col-sm-6 colprevnext">
				<a class="nextbutton">
					<div class="glyphicon glyphicon-arrow-right"></div>          
				</a>
				<a class="prevbutton">
					<div class="glyphicon glyphicon-arrow-left"></div>
				</a>
			</div>
		</div>    
	</div>

	
    <div class="col-md-12 nrprofiles">
<?php 

$profile_count = 0; 
$profile_string = "";

foreach($profiles as $profile) {
	$profile_count = $profile_count + 1;
	if ($profile_count > 1) {$profile_string .= " | ";}
	$profile_string .= "<a href='#profile" . $profile->ProfileID . "'>" . $profile->Side . " (" . $profile->Start . ")</a>";
}

$profile_string = ": " . $profile_string;
if ($profile_count > 1) {$profile_string = "s" . $profile_string;}
$profile_string = $profile_count . " profile" . $profile_string;
?>
        <p>{!!html_entity_decode($profile_string)!!}</p>
    </div>

	<div>
        <div class="col-md-8 leftinfo">
<?php
$profile_count = 0;
$max_bar_width = 50;

foreach($profiles as $profile) {
	$profile_count = $profile_count + 1;
	
	//find stat colors
	$distance_cat = 0;
	if($profile->Distance < 50) {$distance_cat = 5;} 
	elseif($profile->Distance < 100) {$distance_cat = 4;} 
	elseif($profile->Distance < 150) {$distance_cat = 3;} 
	elseif($profile->Distance < 200) {$distance_cat = 2;} 
	else {$distance_cat = 1;}
	$distance_width = ($profile->Distance/300)*$max_bar_width;
	if ($distance_width > $max_bar_width) {$distance_width = $max_bar_width;}
	
	$heightdiff_cat = 0;
	if($profile->HeightDiff < 400) {$heightdiff_cat = 5;} 
	elseif($profile->HeightDiff < 800) {$heightdiff_cat = 4;} 
	elseif($profile->HeightDiff < 1300) {$heightdiff_cat = 3;} 
	elseif($profile->HeightDiff < 1800) {$heightdiff_cat = 2;} 
	else {$heightdiff_cat = 1;}
	$heightdiff_width = ($profile->HeightDiff/2300)*$max_bar_width;
	if ($heightdiff_width > $max_bar_width) {$heightdiff_width = $max_bar_width;}
	
	$avgperc_cat = 0;
	if($profile->AvgPerc < 40) {$avgperc_cat = 5;} 
	elseif($profile->AvgPerc < 60) {$avgperc_cat = 4;} 
	elseif($profile->AvgPerc < 80) {$avgperc_cat = 3;} 
	elseif($profile->AvgPerc < 100) {$avgperc_cat = 2;} 
	else {$avgperc_cat = 1;}
	$avgperc_width = ($profile->AvgPerc/120)*$max_bar_width;
	if ($avgperc_width > $max_bar_width) {$avgperc_width = $max_bar_width;}
		
	$maxperc_cat = 0;
	if($profile->MaxPerc < 60) {$maxperc_cat = 5;} 
	elseif($profile->MaxPerc < 80) {$maxperc_cat = 4;} 
	elseif($profile->MaxPerc < 100) {$maxperc_cat = 3;} 
	elseif($profile->MaxPerc < 120) {$maxperc_cat = 2;} 
	else {$maxperc_cat = 1;}
	$maxperc_width = ($profile->MaxPerc/180)*$max_bar_width;
	if ($maxperc_width > $max_bar_width) {$maxperc_width = $max_bar_width;}
		
	$profileidx_cat = 0;
	if($profile->ProfileIdx < 300) {$profileidx_cat = 5;} 
	elseif($profile->ProfileIdx < 500) {$profileidx_cat = 4;} 
	elseif($profile->ProfileIdx < 700) {$profileidx_cat = 3;} 
	elseif($profile->ProfileIdx < 900) {$profileidx_cat = 2;} 
	else {$profileidx_cat = 1;} 
	$profileidx_width = ($profile->ProfileIdx/1400)*$max_bar_width;
	if ($profileidx_width > $max_bar_width) {$profileidx_width = $max_bar_width;}
	
	$profileIDString = $col->ColIDString;
	if ($profile->Side) {
		$profileIDString .= "_" . $profile->Side;
	}	
?>
	        <div id="profile{{$profile->ProfileID}}">
                <div id="{{$profileIDString}}" class="profile">
                    <div class="profiletitle">
                        <h4 class="col-xs-11">{{$col->Col}}
@if ($profile->SideID > 0)
						<img src="/images/{{$profile->Side}}.png")}}' title='{{$profile->Side}}'/><span class="profile_side">{{$profile->Side}}</span>
@endif
						<br/>
						<span class="profile_start">{{$profile->Start}}</span></h4>
                        <div class="col-xs-1" style="padding: 0px;">
                            <div class="category c{{$profile->Category}}" title="Category {{$profile->Category}}">{{$profile->Category}}</div>
                        </div>
                    </div>
                    <!--<div class="col-xs-12 profilestats">
						<div class="profilestat_wrapper">Distance <span class="profilestat c{{$distance_cat}}">{{number_format($profile->Distance/10,1)}} km</span></div>
                        <div class="profilestat_wrapper">Altitude Gain <span class="profilestat c{{$heightdiff_cat}}">{{$profile->HeightDiff}}m</span></div>
                        <div class="profilestat_wrapper">Average Slope <span class="profilestat c{{$avgperc_cat}}">{{number_format($profile->AvgPerc/10,1)}}%</span></div>
                        <div class="profilestat_wrapper">Maximum Slope <span class="profilestat c{{$maxperc_cat}}">{{number_format($profile->MaxPerc/10,1)}}%</span></div>
                        <div class="profilestat_wrapper">Profile Index <span class="profilestat c{{$profileidx_cat}}">{{$profile->ProfileIdx}}</span></div>
                    </div>-->

					<div class="profileimage clearfix">
						<!--<img align="left" style="margin: 0px 0px 0px 0px" src="{{ URL::asset('profiles/' . $profile->FileName . '.gif') }}"/>-->
						<img align="left" src="/profiles/{{$profile->FileName}}.gif" />
					</div>
					<div class="profilestats clearfix">
						<div class="stats_wrapper clearfix">
							<!---->
							<div class="stat_help"><a href="/help">Total Distance</a><i class="glyphicon glyphicon-play"></i></div>
							<a href="/stats/1/0"><img class="stat_icon" src="/images/{{statNameShort(1)}}.png" title="{{statName(1)}}" /></a>
							<div class="stat_bar profilestat c{{$distance_cat}}" style="width:{{$distance_width}}px;" title="{{statName(1)}}"></div>
							<div class="stat_value">{{formatStat(1,$profile->Distance)}}</div>
							<div class="stat_top stat_top_1"></div>	
							<!---->
							<div class="stat_help"><a href="/help">Altitude Gain</a><i class="glyphicon glyphicon-play"></i></div>
							<a href="/stats/2/0"><img class="stat_icon" src="/images/{{statNameShort(2)}}.png" title="{{statName(2)}}" /></a>
							<div class="stat_bar profilestat c{{$heightdiff_cat}}" style="width:{{$heightdiff_width}}px;" title="{{statName(2)}}"></div>
							<div class="stat_value">{{formatStat(2,$profile->HeightDiff)}}</div>
							<div class="stat_top stat_top_2"></div>	
							<!---->
							<div class="stat_help"><a href="/help">Average Slope</a><i class="glyphicon glyphicon-play"></i></div>
							<a href="/stats/3/0"><img class="stat_icon" src="/images/{{statNameShort(3)}}.png" title="{{statName(3)}}" /></a>
							<div class="stat_bar profilestat c{{$avgperc_cat}}" style="width:{{$avgperc_width}}px;" title="{{statName(3)}}"></div>
							<div class="stat_value">{{formatStat(3,$profile->AvgPerc)}}</div>
							<div class="stat_top stat_top_3"></div>	
							<!---->
							<div class="stat_help"><a href="/help">Maximum Slope</a><i class="glyphicon glyphicon-play"></i></div>
							<a href="/stats/4/0"><img class="stat_icon" src="/images/{{statNameShort(4)}}.png" title="{{statName(4)}}" /></a>
							<div class="stat_bar profilestat c{{$maxperc_cat}}" style="width:{{$maxperc_width}}px;" title="{{statName(4)}}"></div>
							<div class="stat_value">{{formatStat(4,$profile->MaxPerc)}}</div>
							<div class="stat_top stat_top_4"></div>	
							<!---->
							<div class="stat_help"><a href="/help">Profile Index</a><i class="glyphicon glyphicon-play"></i></div>
							<a href="/stats/5/0"><img class="stat_icon" src="/images/{{statNameShort(5)}}.png" title="{{statName(5)}}" /></a>
							<div class="stat_bar profilestat c{{$profileidx_cat}}" style="width:{{$profileidx_width}}px;" title="{{statName(5)}}"></div>		
							<div class="stat_value">{{formatStat(5,$profile->ProfileIdx)}}</div>
							<div class="stat_top stat_top_5"></div>	
						</div>
						<div class="stats_info"><i class="glyphicon glyphicon-question-sign" title="Help"></i></div>
					</div>
					

					<div class="profile_print">
						<span class="glyphicon glyphicon-print" title="print"></span>
					</div>
                </div>
            </div>
<?php
}
?>
        </div>

        <!--<div class="col-sm-4 rightposition">-->
            <div class="col-md-4 rightinfo">
                <div id="map" class="colmap">
                </div>
				<div class="colsnearby" id="colsnearby">
					<div class="colsnearbytitle">
						<h4>Cols Nearby</h4>
					</div>
					<div id="colsnearbyrows" class="colsnearbyrows clearfix">
					</div>
				</div>
                <!--<div id="donate" class="support">
                    <div class="supporttitle">
					<form class="donate" align="center" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="6ME8CQEG33GT4">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
					</form>
					</div>
					<div class="supporttext">
					If you enjoy the services of CyclingCols, you can thank by making a donation. This will promote the continuity and development of CyclingCols.
					</div>
				</div>-->
				<div class="profs" id="profs">
					<div class="profstitle">
						<h4>First On Top
						<a id="show_or_hide_a" href="javascript:showAllPassages()"><img align="right" id="show_or_hide" width="20" src="/images/expand.png" title="expand list"/></a>						
						</h4>
					</div>
				<div id="profrows" class="profrows">
				</div>
            </div>
        <!--</div>-->
    </div>
</div>

@stop