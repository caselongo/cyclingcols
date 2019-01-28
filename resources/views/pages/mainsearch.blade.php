@extends('layouts.master')

@section('title')
CyclingCols - Main
@stop

@section('content')

<!--<div class="overmain">-->
	<style>
	/*.menuitem-user{
		width: 100%;
		max-width: initial;
	}
	.headeruser{
		padding: 0;
		padding-left: 10px;
	}*/
	
	#search {
	  width: 100%;
	  top: 30%;
	  position: absolute;
	  opacity: 0.8;
	  z-index: 100000;
	}
	
	#search-box{
		margin: auto;
		width: 50%;
		top: 30%;
	}
	</style>
    <!--<div class="homemenu">
		<div id="menuleft" class="col-md-10">
			<div class="homelogo">
				<a href="/"><img id="logo_img" src="/images/logo.png" /></a>
			</div>
			<a href="/"><div class="menuitem"><i class="glyphicon glyphicon-home" title="Home"></i><span class="headertext">Home</span></div></a>
			<a href="/new"><div class="menuitem"><i class="glyphicon glyphicon-asterisk" title="New"></i><span class="headertext">New</span></div></a>
			<a href="/stats"><div class="menuitem"><i class="glyphicon glyphicon-stats" title="Stats"></i><span class="headertext">Stats</span></div></a>
			<a href="/help"><div class="menuitem"><i class="glyphicon glyphicon-question-sign" title="Help"></i><span class="headertext">Help</span></div></a>
			<a href="/about"><div class="menuitem"><i class="glyphicon glyphicon-info-sign" title="About"></i><span class="headertext">About</span></div></a>
			<a href="/map"><div class="menuitem"><i class="glyphicon glyphicon-globe" title="Map"></i><span class="headertext">Map</span></div></a>
			<a id="twitter" href="https://twitter.com/cyclingcols" target="_blank">
				<i class="fa fa-twitter fa-lg" title="Follow CyclingCols on Twitter!"></i>
				<i class="fa fa-twitter fa-2x" title="Follow CyclingCols on Twitter!"></i>
				<i class="fa fa-twitter fa-3x" title="Follow CyclingCols on Twitter!"></i>
			</a>
		</div>
		<div class="headeruser col-md-2">
			@auth
				<a href="/logout" class="loginout"><div class="menuitem menuitem-user"><i class="glyphicon glyphicon-log-out" title="Logout"></i><span class="headertext">&nbsp;{{Auth::user()->name}}</span></div></a>
			@endauth
			@guest
				<a href="/login" class="loginout"><div class="menuitem menuitem-user"><i class="glyphicon glyphicon-log-in" title="Login"></i><span class="headertext">&nbsp;Login</span></div></a>
			@endguest
		</div>
	</div>-->
<!--</div>-->
<!--<div id="search" class="ui-front">-->
	<div id="search">
		<input class="form-control" id="search-box" type="search" placeholder="Search a col in Europe...">
		<!--<input type="text" class="searchfield" placeholder="Search a col in Europe..." id="search-box">
		<div id="searchstatus"></div>-->
	</div>
	<!--<div id="searchonmap">
		<a href="/map"><div class="globe" type="submit" title="Search on map"><img src="/images/globeblack.png" alt="" /></div></a>
	</div>-->
<!--</div>-->
<div class="overcontent">
    <div class="col-md-12 scenery" style="padding:0px">
        <div id="phototext" class="phototext"><a href=""></a></div>
        <div id="news_ads">
			<!--<div id="totals">
				2908 cols, 4436 profiles<br/>
				last updated: 26 nov 2014
			</div>-->
        </div>
		<div id="slide">		
        </div>

        <script type="text/javascript" charset="utf-8">
			var images;
			var path;
			
			function getBanners() {	
				var width = $(window).width();
				var height = $(window).height() - $('.footer').height() - $('.homemenu').height();
				var count = parseInt(height/150);
				
				if (width < 992) return;
			
				$.ajax({
					url : "/ajax/getbanners.php?colid=0",
					data : "",
					dataType : 'json',
					success : function(data) {
						for(var i = 0; i < count; i++){
							var a = document.createElement("a");
							var img = document.createElement("img");
							$(img)							
								.addClass("banner")
								.attr("src","/images/banners/" + data[i].BannerFileName);
							$(a)							
								.addClass("banner")
								.attr("href","http://" + data[i].RedirectURL)
								.attr("target","_blank");
							$('#news_ads').append(a);
							$(a).append(img);
						}
					}
				})
			}

			function showSlide(images,nr,init) {
				var col = images[nr].Col
				col += '<img src="/images/flags/' + images[nr].Country1 + '.gif"></img>';
				if (images[nr].Country2) {
					col += ' <img src="/images/flags/' + images[nr].Country2 + '.gif"></img>';
				}
				setSlide(path + images[nr].ColIDString + ".jpg","/col/" + images[nr].ColIDString,col,init);
				if (nr<images.length-1) {
					setTimeout(function(){showSlide(images,nr+1,false)},15000);
				}
				else
				{
					setTimeout(function(){startSlideShow()},15000); //restart slideshow with new slides
				}
			}
			
			setSlide = function(slide_url,href,colname,init) {
				if (init){
					$("#phototext a").attr("href",href);
					$("#phototext a").html(colname);
					$("#slide").backstretch(slide_url, {fade: 1000});				
				} else {			
					setTimeout(function(){$("#phototext a").attr("href",href)},1000);
					setTimeout(function(){$("#phototext a").html(colname);},1000);
					$("#slide").backstretch(slide_url, {fade: 2000});
				}
			}
			
			startSlideShow = function() {
				var w = $(window).width();
				var h = $(window).height();
				var wh = w;
				if (h > w) wh = h;
				path = "/images/covers/";
				
				if (wh <= 1024){	 
					path += "medium/";
				}

				$.ajax({
					url : "/ajax/getphotos.php",
					data : "",
					dataType : 'json',
					success : function(data) {
						images = data;
					
						showSlide(images,0,true);
					}
				})
			}

			function calculateslideshowheight() {
				$h = $(window).height() - $('.footer').outerHeight() - $('.navbar').outerHeight() + 2;
				$('#slide').height($h);
			}
			
			/*function positionSearchBox(){
				var width = $(window).width();
				var height = $(window).height();
				
				var boxWidth = 500;
				var globeWidth = 40;
				var marginLeftRight = 20;
				if (width < boxWidth+globeWidth+2*marginLeftRight){
					boxWidth = width-(globeWidth+2*marginLeftRight);
				}
				var totalWidth = boxWidth+globeWidth;
				var left = (width-totalWidth)/2;
				var top = 0;//(height-30)/2;
				
				var search = $("#searchtext");
				$(search).css("left", left + "px")
					.css("top", top + "px")
					.width(boxWidth + "px");				
				$(search).show();
				var searchonmap = $("#searchonmap");
				$(searchonmap).css("left", left + "px")
					.css("top", top + "px")
					.css("left", (left + boxWidth + 5) + "px");				
				$(searchonmap).show();	
			}*/
			
			$(document).ready(function() {
				calculateslideshowheight();
				//positionSearchBox();						
				startSlideShow();
				getBanners();
			});
			
			$(window).resize(function() {
				calculateslideshowheight();
				//positionSearchBox();
			});
			
			$(document).on("focusin","#searchbox",function(){
				$("#search").css("opacity",1);
			});
			
			$(document).on("focusout","#searchbox",function(){
				$("#search").css("opacity","");
			});
        </script>
    </div>    
</div>
@stop
