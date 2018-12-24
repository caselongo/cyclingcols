
//facebook
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=388989554589033&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
//twitter
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');

$(document).ready(function() {
	$(".profileimage img").on("click",function(){
		var div = document.createElement("div");
		$(div).addClass("popup_canvas");
		document.body.appendChild(div);
		var img = document.createElement("img");
		$(img).attr("src",$(this).attr("src"));
		$(img).addClass("profile_popup_img");
		
		document.body.appendChild(img);
		
		positionImage();
		
		$('body').addClass('stop-scrolling');
		
		$(img).add(div).on("click",function(){
			$(img).remove();
			$(div).remove();
			$('body').removeClass('stop-scrolling');
		});

	});
});

function positionImage(){
	var img = $(".profile_popup_img");
	var width = $(img).width();
	var height = $(img).height();
	if (width > $(window).width()) width = $(window).width();
	if (height > $(window).height()) height = $(window).height();
	
	$(img).css("top",($(window).height()-height)/2);
	$(img).css("left",($(window).width()-width)/2);
	$(img).css("max-height",$(window).height());
	$(img).css("max-width",$(window).width());
}

$(document).on("click",".stats_info",function(){
	$.each($(this).prev().find(".stat_help"),function(i,val){
		var pos = $(this).nextAll(".stat_bar").first().position();
	
		$(val)
			.css("right",$(this).parent().width()+10)
			.css("top",pos.top)
			.toggle(500);
	});
});

$(window).resize(function() {
	$(".stat_help").hide();
	positionImage();
});

function showCovers(colIDString,coverPhotoPosition) {
	//load images
	var w = $(window).width();
	var h = $(window).height();
	var wh = w;
	if (h > w) wh = h;
	var path = "/images/covers/";
	var image2 = ($(".colimage2").length > 0);
	
	if (wh <= 680){	 
		path += "small/";
	} else if (wh <= 1024){	 
		path += "medium/";
	} else if (image2){  
		path += "medium/";
	}
	
	if (coverPhotoPosition == 1){
		$(".colimage").css("background-image","url(\"" + path + colIDString + ".jpg\")");
		if (image2){  
			$(".colimage2").css("background-image","url(\"" + path + colIDString + "_2.jpg\")");
		}
	} else {
		$(".colimage").css("background-image","url(\"" + path + "_Dummy.jpg\")");		
	}
}
			
showAllPassages = function() {
	$(".profrow_hidden").show();
	$("#show_or_hide").attr("src","/images/collapse.png");
	$("#show_or_hide").attr("title","collapse list");
	$("#show_or_hide_a").attr("href","javascript:hideAllPassages()");
	if ($(window).width() >= 992)
	{
		$("#map").hide("slow");
		$("#colsnearby").hide("slow");
		//$("#donate").hide("slow");
		//$("#reclame").hide("slow");
	}
}

hideAllPassages = function() {
	$("#map").show("slow");
	$("#colsnearby").show("slow")
	//$("#donate").show("slow");
	//$("#reclame").show("slow");
	$(".profrow_hidden").hide();
	$("#show_or_hide").attr("src","/images/expand.png");
	$("#show_or_hide").attr("title","expand list");
	$("#show_or_hide_a").attr("href","javascript:showAllPassages()");
}
			
getBanners = function(colid) {	
	var width = $(window).width();
	
	//if (width < 992) return;

	$.ajax({
		url : "/ajax/getbanners.php?colid=" + colid,
		data : "",
		dataType : 'json',
		success : function(data) {
			/*if (data.length > 0){
				var div = document.createElement("div");
				$(div).attr("id","news_ads");
				$('.colimage').append(div);
					
				for(var i = 0; i < 2 && i < data.length; i++){
					var a = document.createElement("a");
					var img = document.createElement("img");
					$(img)							
						.addClass("banner")
						.attr("src","/images/banners/" + data[i].BannerFileName);
					$(a)							
						.addClass("banner")
						.attr("href","http://" + data[i].RedirectURL)
						.attr("target","_blank");
					$(div).append(a);
					$(a).append(img);
				}
			}
			if (data.length > 2){
				var div = document.createElement("div");
				$(div).attr("id","news_ads_right");
				$('.colimage').append(div);
					
				for(var i = 2; i < 4 && i < data.length; i++){
					var a = document.createElement("a");
					var img = document.createElement("img");
					$(img)							
						.addClass("banner")
						.attr("src","/images/banners/" + data[i].BannerFileName);
					$(a)							
						.addClass("banner")
						.attr("href","http://" + data[i].RedirectURL)
						.attr("target","_blank");
					$(div).append(a);
					$(a).append(img);
				}
			}*/
			
			if (data.length > 0){
				var div = document.createElement("div");
				$(div).addClass("ads");
				var firstProfile = $(".leftinfo").children().first();
				$(firstProfile).after(div);
					
				for(var i = 0; i < data.length; i++){
					var a = document.createElement("a");
					var img = document.createElement("img");
					$(img)							
						.addClass("banner")
						.attr("src","/images/banners/" + data[i].BannerFileName);
					$(a)							
						.addClass("banner")
						.attr("href","http://" + data[i].RedirectURL)
						.attr("target","_blank");
					$(div).append(a);
					$(a).append(img);
				}
			}

		}
	})
}
			
getPassages = function(colid) {
	$.ajax({
		url : "/ajax/getpassages.php?colid=" + colid,
		//url : "{{ URL::asset('ajax/')}}/getpassages.php?colid=" + colid,
		data : "",
		dataType : 'json',
		success : function(data) {
			if (data.length > 0) {
			
				for(var i = 0; i < data.length; i++) {	
					var	race = ""; 
					var race_short = "";
				
					switch(parseInt(data[i].EventID)) {
						case 1: race = "Tour de France"; race_short = "Tour"; break;
						case 2: race = "Giro d'Italia"; race_short = "Giro"; break;
						case 3: race = "Vuelta a España"; race_short = "Vuelta"; break;
					}
					
					var person = data[i].Person;
					var person_class = "rider";
					var flag = true;
					if (data[i].Neutralized == "1") {person = "-neutralized-"; flag = false;}
					else if (data[i].Cancelled == "1") {person = "-cancelled-"; flag = false;}
					else if (data[i].NatioAbbr == "") {person = "-cancelled-"; flag = false;}
					
					if (person == null) {person = ""; flag = false;}
					
					var hidden = "profrow_hidden";
					if (i < 5) {hidden = "";}
					
					var html = '<div class="profrow ' + hidden + ' clearfix">';
					html += '<div class="year">' + data[i].Edition + '</div>';
					html += '<div class="race"><i>' + race + '</i></div>'; 
					html += '<div class="race_short" title="' + race + '"><i>' + race_short + '</i></div>'; 
					html += '<div class="rider">' + person + '</div>';
					if (flag == true) {
						html += "<div class='profcountry'><img src='/images/flags/small/" + data[i].NatioAbbr.toLowerCase() + ".gif' title='" + data[i].Natio + "'/></div>";
					}
					html += '</div>'; 
					
					$("#profrows").append(html);
				}
					
				if (data.length <= 5) {
					$("#show_or_hide_a").hide();
				}
				$("#profs").show();
			}
		}
	})
}
	
getColsNearby = function(colid) {
	$.ajax({
		//url : "{{ URL::asset('ajax/')}}/getcolsnearby.php?colid=" + colid,
		url : "/ajax/getcolsnearby.php?colid=" + colid,
		data : "",
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
			
				var html = '<div class="colsnearbyrow">';
				html += '<div class="colnearby_col"><a href="/col/' + data[i].ColIDString + '">' + data[i].Col + '</a>';
				html += '<div class="colnearby_distance"><img src="/images/' + dir + '.png"/>' + dis + ' km</div>';	
				html += '</div></div>';
				
				$("#colsnearbyrows").append(html);
			}
		}
	})
}

getPrevNextCol = function(number) {
	$.ajax({
		url : "/ajax/getprevnextcol.php?number=" + number,
		data : "",
		dataType : 'json',
		success : function(data) {
			var prevIDString;
			var prevCol;
			var nextIDString;
			var nextCol;
			for(var i = 0; i < data.length; i++) {	
				if (data[i].Number == number - 1) {
					prevIDString = data[i].ColIDString;
					prevCol = data[i].Col;
				}
				else if (data[i].Number == number + 1) {
					nextIDString = data[i].ColIDString;
					nextCol = data[i].Col;
				}
			}
			
			if (prevIDString) {
				$(".prevbutton").attr("href","/col/" + prevIDString);
				$(".prevbutton").append(prevCol);
				$(".prevbutton").attr("title","Go to previous col (alphabetical): " + prevCol);
				$(".prevbutton").css("display","inline-block");
			}
			
			if (nextIDString) {
				$(".nextbutton").attr("href","/col/" + nextIDString);
				$(".nextbutton").append(nextCol);
				$(".nextbutton").attr("title","Go to next col (alphabetical): " + nextCol);
				$(".nextbutton").css("display","inline-block");
			}
		}
	})
}

getTopStats = function(colid) {
	$.ajax({
		url : "/ajax/gettopstats.php?colid=" + colid,
		data : "",
		dataType : 'json',
		success : function(data) {
			var statid = 0;
			var rank = 0;
		
			for(var i = 0; i < data.length; i++) {
				if (statid != data[i].StatID || (rank > 1 && data[i].Rank < rank)) {
					rank = data[i].Rank;
					var rankAdd = 'th';
					if (rank == 1) rankAdd = 'st';
					if (rank == 2) rankAdd = 'nd';
					if (rank == 3) rankAdd = 'rd';
					
					var geo = "Europe";
					if (data[i].GeoID > 0) {
						if (data[i].GeoID == data[i].Country1ID) geo = data[i].Country1;
						else if (data[i].GeoID == data[i].Country2ID) geo = data[i].Country2;
					}
					geo = "<img src='/images/flags/" + geo + ".gif' title='" + geo + "'/>";
					var el = $("#profile" + data[i].ProfileID).find(".stat_top_" + data[i].StatID);
					var el2 = document.createElement("div");
					$(el).append($(el2));
					var html = '<a href="/stats/' + data[i].StatID + '/' + data[i].GeoID + '"><span class="glyphicon glyphicon-flash" aria-hidden="true"></span>' + data[i].Rank + rankAdd + ' of ' + geo + '</a>';
					$(el2).html(html);
					if (rank <= 10) $(el2).addClass("stat_top_bold");
					if (data[i].GeoID == 0) $(el2).addClass("stat_top_overall");
					$(el).show();
				
					statid = data[i].StatID;
				}
			}
		}
	})
}