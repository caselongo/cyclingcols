@extends('layouts.master')

@section('title')
CyclingCols - Main
@stop

@section('content')


<main role="main" class="bd-content p-0 overflow-hidden">
    <div class="d-flex w-100">
        <div id="ads-wrapper" class="position-absolute d-flex flex-column justify-content-end p-1">
        </div>
		<div id="slide" class="d-flex w-100 justify-content-around">		
			<div id="search" class="ui-front">
				<input class="form-control font-weight-light px-2 py-1" id="search-col" type="search" placeholder="Search a col in Europe...">
			</div>
			<div id="phototext" class="phototext font-weight-light"><a href=""></a></div>
        </div>

        <script type="text/javascript" charset="utf-8">
			var images;
			var path;
			
			function getBanners_() {	
				var width = $(window).width();
				var height = $(window).height() - $('footer').height() - $('header').height();
				var count = parseInt(height/150);
				
				if (width < 992) return;
				
				$("#ads-wrapper").css("height", height);
				
				getBanners("#ads-wrapper","home",count);
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
					url : "/service/cols/photos",
					data : "",
					dataType : 'json',
					success : function(data) {
						images = data;
					
						showSlide(images,0,true);
					}
				})
			}

			function calculateslideshowheight() {
				$h = $(window).height() - $('.footer').outerHeight() - $('.navbar').outerHeight();
				$('#slide').height($h);
			}
			
			$(document).ready(function() {
				calculateslideshowheight();
				startSlideShow();
				getBanners_();
			});
			
			$(window).resize(function() {
				calculateslideshowheight();
			});
			
			$(document).on("focusin","#searchbox",function(){
				$("#search").css("opacity",1);
			});
			
			$(document).on("focusout","#searchbox",function(){
				$("#search").css("opacity","");
			});
        </script>
    </div>    
</main>
@stop
