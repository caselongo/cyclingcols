var searchCount;
var firstColIDString;

/*sets the height of the map-canvas so that it always fills the screen height*/
var calculatescrollheight = function() {//added 20151213
    if ($('body').hasClass('mappage')) {	
		$('#div-scroll').css("overflow-y","auto");
	} else {
		var height = $(window).height() - $('.homemenu').height();
		$('#div-scroll').height(height);
	}
}

/*sets the height of the map-canvas so that it always fills the screen height*/
var calculatemapheight = function() {
    if ($('body').hasClass('mappage')) {
        var height = $(window).height() - $('.footer').height() - $('#canvas').offset().top;
	
		$('#canvas').height(height);
		$('#map-canvas').height(height);
    }
}

var calculateprofilemaxwidth = function() {
    if ($('body').hasClass('coltemplate')) {
		$(".profileimage").each(function(){
			$(this).css('max-width',$(this).parent().parent().width() - 135);
			$(this).find("img").css('max-width',$(this).parent().parent().width() - 135);
		});
    }
}

var goToCol = function(colIDString){
	window.location.replace("/col/" + colIDString);
}

$(window).resize(function() {
	calculatescrollheight();
	calculatemapheight();
	calculateprofilemaxwidth();
});

$(document).ready(function() {
	calculatescrollheight();
    calculatemapheight();
	calculateprofilemaxwidth();
	
    /*on keyboard enter press*/
    $(document).keypress(function(e) {
        if (e.which === 13) {
			e.preventDefault();
            if (searchCount == 1 && firstColIDString){	
                if (history.pushState){
					history.pushState(null, null, window.location.href);
				}
				window.location.replace("/col/" + firstColIDString);
			}
        }
    });

    /*select menu headeritem*/
    $(".menuitem").removeClass("selectedtab"); //remove     
    $('.home #menuleft a:nth-child(2) .menuitem').addClass("selectedtab");
	$('.newtemplate #menuleft a:nth-child(3) .menuitem').addClass("selectedtab");
    $('.statstemplate #menuleft a:nth-child(4) .menuitem').addClass("selectedtab");
    $('.helptemplate #menuleft a:nth-child(5) .menuitem').addClass("selectedtab");
    $('.abouttemplate #menuleft a:nth-child(6) .menuitem').addClass("selectedtab");
    $('.mappage #menuleft a:nth-child(7) .menuitem').addClass("selectedtab");

	initAutoComplete();
	
	/*$("#login").on("click",function(){
		 window.location.replace("/login");
	});
	
	$("#logout").on("click",function(){
		window.location.replace("/logout");
	});*/
	
	$(".headeruser").on("mouseenter", function(){
		var loggedIn = $(this).find("#user").length > 0;
		
		if (loggedIn){
			var height = $(this).find(".menuitem").height();
			var width = $(this).find(".menuitem").width();
			
			var dropdown = document.createElement("div");
			$(dropdown)
				.css("top", height + "px")
				.css("width", width + "px")
				.addClass("_dropdown");
			
			$(this).append(dropdown);
				
			var logout = document.createElement("div");
			$(logout).html("<a href=\"/logout\"><i class=\"glyphicon glyphicon-log-out\"></i>&nbsp;Logout</a>");
			var profile = document.createElement("div");
			$(profile).html("<a href=\"/user/cols\"><i class=\"glyphicon glyphicon-list\"></i>&nbsp;My CyclingCols</a>");
			
			$(dropdown).append(logout,profile);
		}
	});
	
	$(".headeruser").on("mouseleave", function(){
		var loggedIn = $(this).find("#user").length > 0;
		
		if (loggedIn){
			$(this).parent().find("._dropdown").remove();
		}
	});
	
	$(function () {
	  $('[data-toggle="popover"]').popover()
	});
});

var initAutoComplete = function(){
	
	var accentMap = {
		"Š": "S",
		"Œ": "OE",
		"Ž": "Z",
		"š": "s",
		"œ": "oe",
		"ž": "z",
		"Ÿ": "Y",
		"Š": "S",
		"À": "A","Á": "A","Â": "A","Ã": "A","Ä": "A","Å": "A",
		"Æ": "AE",
		"Ç": "C",
		"È": "E","É": "E","Ê": "E","Ë": "E",
		"Ì": "I","Í": "I","Î": "I","Ï": "I",
		"Ð": "D",
		"Ñ": "N",
		"Ò": "O","Ó": "O","Ô": "O","Õ": "O","Ö": "O","Ø": "O",
		"Ù": "U","Ú": "U","Û": "U","Ü": "U",
		"Ý": "Y",
		"Þ": "p",
		"ß": "ss",
		"à": "a","á": "a","â": "a","ã": "a","ä": "a","å": "a",
		"æ": "ae",
		"ç": "c",
		"è": "e","é": "e","ê": "e","ë": "e",
		"ì": "i","í": "i","î": "i","ï": "i",
		"ð": "d",
		"ñ": "n",
		"ò": "o","ó": "o","ô": "o","õ": "o","ö": "o","ø": "o",
		"ù": "u","ú": "u","û": "u","ü": "u",
		"ý": "y",
		"-": " ",
		" ": "-"			
	};
  
	var normalize = function( term ) {
		var ret = "";
		for ( var i = 0; i < term.length; i++ ) {
			ret += accentMap[ term.charAt(i) ] || term.charAt(i);
		}
		return ret;
	};
	
	$.getJSON("/ajax/getcolsforsearch.php", function( data ) {
		
		$( "#search-box" ).autocomplete({
			minLength: 2,
			delay: 300,
			appendTo: "#search-box-wrapper",
			//position: { my : "right top", at: "right bottom" },
			source: function( request, response ) {
				var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
				var res = $.grep( data, function( value ) {
					value = value.label || value.value || value;
					return matcher.test( value ) || matcher.test( normalize( value ) );
				})
				
				searchCount = res.length;
				if (searchCount > 0){
					firstColIDString = res[0].ColIDString;
				}
				
				response(res.slice(0,10));
			},
			select: function( event, ui ) {		
				//$("#searchstatus").hide();
				//$( "#searchbox" ).val("");
				//$( "#searchbox" ).val( ui.item.label );
				if (history.pushState) {
					history.pushState(null, null, window.location.href);
				}
				window.location.replace("/col/" + ui.item.ColIDString);
	 
				return false;
			},
			response: function(event, ui) {
				var remark = null;
				
				if (ui.content.length === 0) {
					remark = "No cols found";
				} else {
					remark = searchCount + " cols found" + (searchCount > 10 ? ", showing first 10" : "");
				}

				var remarks = { "isRemark": true, "remark": remark };
				
				ui.content.forEach(function(c){
					c.isRemark = false;
				});
				
				ui.content.unshift(remarks);
			},
			open: function() {
				var ui = $("#search-box");
						
				if (ui.parent().find("#search-box-wrapper").length > 0){
					var width = ui.outerWidth();
					var height = ui.outerHeight();
					var top = ui.position().top;
							
					ui.parent().find("#search-box-wrapper")
						.width(width)
						.css("top", top);
				}
				
				ui.addClass("ui-autocomplete-input-open");
				$(".ui-autocomplete").addClass("list-group");
			},
			close: function() {
				$("#search-box").removeClass("ui-autocomplete-input-open");
			}
		})
		.autocomplete( "instance" )._renderItem = function( ul, item ) {
			if (item.isRemark){
				var html = item.remark;
				return $( "<li>" )
					.append(html)
					.addClass("list-group-item list-group-item-action font-weight-light disabled")
					.appendTo( ul );				
			} else {
				var html = "<a><img class=\"flag\" src=\"/images/flags/" + item.Country1 + ".gif\"/>";
				if (item.Country2){
					html += "<img class=\"flag\" src=\"/images/flags/" + item.Country2 + ".gif\"/>";
				}
				html += "<span class=\"px-1\">" + item.label + "</span>";
				html += "<span class=\"badge badge-altitude font-weight-light\">" + item.Height + "m</span></a>";
				return $( "<li>" )
					.append(html)
					.addClass("list-group-item list-group-item-action font-weight-light")
					.appendTo( ul );
			}
		};
	});
}

/*help page*/
var showInfo = function () {
	hideInfo();
	
	$(this).addClass("selected");
    var id = "#div_" + $(this).find(".infotype").attr("id");
    $(id).addClass("selected");
}

var hideInfo = function () {	
	$(".info").removeClass("selected");
    $(".infotype_row").removeClass("selected");
}

var showInfoType = function () {
	hideInfo();
	
	$(this).addClass("selected");
    var id = "#" + $(this).attr("id").replace("div_", "");
	$(id).parent().addClass("selected");
}

var hideInfoType = function () {
    hideInfo();
}


var showInfo2 = function () {
	hideInfo2();
	
	$(this).addClass("selected2");
    var id = "#div_" + $(this).find(".infotype").attr("id");
    $(id).addClass("selected2");
}

var hideInfo2 = function () {	
	$(".info").removeClass("selected2");
    $(".infotype_row").removeClass("selected2");
}

var showInfoType2 = function () {
	hideInfo2();
	
	$(this).addClass("selected2");
    var id = "#" + $(this).attr("id").replace("div_", "");
	$(id).parent().addClass("selected2");
}

var hideInfoType2 = function () {
    hideInfo2();
}

var showTableProfile = function() {
	var id = $(this).attr("id");
	var idx = id.indexOf("-");
	
	if (idx == -1) return;
	
	var colIDString = id.substr(0,idx);
	var fileName = id.substr(idx+1);
	
	var div = document.createElement("div");
	$(div).addClass("popup_canvas");
	$(div).height($(window).height());
	document.body.appendChild(div);

	var img = document.createElement("img");
	$(img).attr("src","/profiles/" + fileName + ".gif");
	$(img).addClass("profile_popup_img");
	document.body.appendChild(img);

	var div2 = document.createElement("div");
	$(div2).addClass("profile_popup_goto");
	$(div2).html("<a href=\"/col/" + colIDString + "\">Go to col</a>");
	document.body.appendChild(div2);
	
	//setTimeout(function(){
	$(img).load(function() {
		var width = img.width;//img.clientWidth;
		var height = img.height;//img.clientHeight;
		if (width > $(window).width()) width = $(window).width();
		if (height > $(window).height()) height = $(window).height();
		var top = ($(window).height()-height)/2;
		if (top < 30) top = 30;
		var left = ($(window).width()-width)/2;
		
		$(img).css("top",top);
		$(img).css("left",left);
		$(img).css("max-height",$(window).height());
		$(img).css("max-width",$(window).width());
		
		$(div2).css("top",top - 15);
		$(div2).css("left",left);
		$(div2).css("width",img.clientWidth + 2);
		
		$('body').addClass('stop-scrolling');
		
		$(img).add(div).on("click",function(){
			$(img).remove();
			$(div).remove();
			$(div2).remove();
			$('body').removeClass('stop-scrolling');
		});
	});
	//},300);//wait for image being downloaded (otherwise img.width/height = 0)
}


var showTableProfile2 = function(filename) {
	var div = document.createElement("div");
	$(div).addClass("popup_canvas");
	$(div).height($(window).height());
	document.body.appendChild(div);

	var img = document.createElement("img");
	$(img).attr("src","/profiles/" + fileName + ".gif");
	$(img).addClass("profile_popup_img");
	document.body.appendChild(img);

	var div2 = document.createElement("div");
	$(div2).addClass("profile_popup_goto");
	//$(div2).html("<a href=\"/col/" + colIDString + "\">Go to col</a>");
	document.body.appendChild(div2);
	
	//setTimeout(function(){
	$(img).load(function() {
		var width = img.width;//img.clientWidth;
		var height = img.height;//img.clientHeight;
		if (width > $(window).width()) width = $(window).width();
		if (height > $(window).height()) height = $(window).height();
		var top = ($(window).height()-height)/2;
		if (top < 30) top = 30;
		var left = ($(window).width()-width)/2;
		
		$(img).css("top",top);
		$(img).css("left",left);
		$(img).css("max-height",$(window).height());
		$(img).css("max-width",$(window).width());
		
		$(div2).css("top",top - 15);
		$(div2).css("left",left);
		$(div2).css("width",img.clientWidth + 2);
		
		$('body').addClass('stop-scrolling');
		
		$(img).add(div).on("click",function(){
			$(img).remove();
			$(div).remove();
			$(div2).remove();
			$('body').removeClass('stop-scrolling');
		});
	});
	//},300);//wait for image being downloaded (otherwise img.width/height = 0)
}

$(document).ready(function () {
    $(".infotype_row").hover(showInfo2, hideInfo2);
    $(".infotype_row").on("click",showInfo);
    $(".info").hover(showInfoType2, hideInfoType2);
    $(".info").on("click",showInfoType);
	$(".table_row").click(showTableProfile);
	$(".profile_print").click(function() { 
		var title = $(this).parent().attr("id");
		printContent($(this).parent().parent(), title); 
	} );
	 
	//$(document).on("focusout","#searchbox",function(){
	//	$("#searchstatus").hide();
	//});
	
	$('#modalProfile').on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget);
	  var filename = button.data('filename');
	  var col = button.data('col');
	  var side = button.data('side');

	  var modal = $(this);
	  modal.find('.modal-title').text(col);
	  modal.find('.modal-title-secondary').text(side);
	  modal.find('.modal-body img').attr("src","/profiles/" + filename + ".gif");
	})
})

var printContent = function (el, title){
	var divContents = $(el).html();
	var printWindow = window.open('', '', 'height=400,width=800');
	printWindow.document.write('<html><head><title>' + title + '</title>');
	printWindow.document.write('<link rel="stylesheet" href="/css/bootstrap.min.css" type="text/css">');
	printWindow.document.write('<link rel="stylesheet" href="/css/main.css" type="text/css">');
	printWindow.document.write('</head><body>');
	//printWindow.document.write('<div>');
	printWindow.document.write(divContents);
	//printWindow.document.write('</div>');
	printWindow.document.write('</body></html>');
	//printWindow.document.write('<script>');
	//printWindow.document.write('$(document).ready(function() { window.print(); });');
	//printWindow.document.write('</script>');
	printWindow.document.close();
	printWindow.focus();
	
	setTimeout(function() { 
		printWindow.print(); 
		printWindow.close();
	}, 300)
}