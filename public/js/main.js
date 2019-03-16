var searchCount;
var firstColIDString;

var dateSelectCallback = null;

$(document).ready(function() {

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})
	
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

	/* init autocomplete */
	initAutoComplete();
	
	/* init modal ride */
	$('#modalRide').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var fileName = button.data('ride');
		var date = button.data('date');
 
		var modal = $(this);
		
		modal.find('.date').text(date);				
		modal.find('.ride-img').attr("src","/tours/" + fileName + ".gif");	
	});
	
	/* init modal profile */
	$('#modalProfile').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var fileName = button.data('profile');
		var col = button.data('col');
 
		var modal = $(this);
		modal.find('.modal-content').hide();
				
		$.ajax({
			type: "GET",
			url : "/service/col/profile/" + fileName,
			dataType : 'json',
			success : function(data) {
				modal.find('.category').removeClass("category-1 category-2 category-3 category-4 category-5").addClass("category-" + data.Category).text(data.Category);
				modal.find('.modal-title').text(col);
				if (data.Side){
					modal.find('.modal-title-secondary').html("<img class=\"direction mr-1\" src=\"/images/" + data.Side + ".png\"/>" + data.Side);
				} else {
					modal.find('.modal-title-secondary').text("");  
				}
				modal.find('.profile-img').attr("src","/profiles/" + data.FileName + ".gif");
				
				modal.find('.stat1 span').html(data.DistanceFormatted);
				modal.find('.stat1 i').removeClass("color-1 color-2");
				if (data.DistanceCat <= 2) modal.find('.stat1 i').addClass("color-" + data.DistanceCat);
				modal.find('.stat2 span').html(data.HeightDiffFormatted);
				modal.find('.stat2 i').removeClass("color-1 color-2");
				if (data.HeightDiffCat <= 2) modal.find('.stat2 i').addClass("color-" + data.HeightDiffCat);
				modal.find('.stat3 span').html(data.AvgPercFormatted);
				modal.find('.stat3 i').removeClass("color-1 color-2");
				if (data.AvgPercCat <= 2) modal.find('.stat3 i').addClass("color-" + data.AvgPercCat);
				modal.find('.stat4 span').html(data.MaxPercFormatted);
				modal.find('.stat4 i').removeClass("color-1 color-2");
				if (data.MaxPercCat <= 2) modal.find('.stat4 i').addClass("color-" + data.MaxPercCat);
				modal.find('.stat5 span').html(data.ProfileIdxFormatted);
				modal.find('.stat5 i').removeClass("color-1 color-2");
				if (data.ProfileIdxCat <= 2) modal.find('.stat5 i').addClass("color-" + data.ProfileIdxCat);
				
				modal.find('.modal-footer').attr("id",data.FileName);
				
				getTopStats(null,data.FileName);
				
				modal.find('.modal-content').show();
			}
		});
	})
	
	$(".col-climbed-date").on("click", function(e){
		var this_ = $(this);
		//var date = new Date(this_.html());
		var date = $(this).data("date");
		var colIDString = this_.data("colidstring");
		
		var onSelect = function(dateText, inst){
			saveUser(this_.data("colidstring"), dateText);
			
			var dd = isToday(dateText);
			var dd1 = isYesterday(dateText);
			//this_.html(dateText);
			$(".col-climbed-date[data-colidstring='" + colIDString + "']").data("date",dateText);
			$(".col-climbed-date[data-colidstring='" + colIDString + "']").html(getHumanDate(dateText));
			
			if (dateSelectCallback) dateSelectCallback();
		};
		
		var options = {
			changeMonth: true,
			changeYear: true,
			dateFormat: "dd M yy",
			maxDate: "+0d"
		};
		
		var pos = e;
		
		$(this).datepicker( "dialog", date, onSelect, options, pos );
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
	
	$.ajax({
		type: "GET",
		url : "/service/cols/search",
		dataType : 'json',
		success : function(data) {
			
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
						.addClass("list-group-item list-group-item-action p-1 font-weight-light disabled")
						.appendTo( ul );				
				} else {
					var html = "<a><img class=\"flag\" src=\"/images/flags/" + item.Country1 + ".gif\"/>";
					if (item.Country2){
						html += "<img class=\"flag ml-1\" src=\"/images/flags/" + item.Country2 + ".gif\"/>";
					}
					html += "<span class=\"px-1\">" + item.label + "</span>";
					html += "<span class=\"badge badge-altitude font-weight-light\">" + item.Height + "m</span></a>";
					return $( "<li>" )
						.append(html)
						.addClass("list-group-item list-group-item-action p-1 font-weight-light")
						.appendTo( ul );
				}
			};
		}
	});
}

var getTopStats = function(colIDString,profileFileName) {
	var url = null;
	if (profileFileName){
		url = "/service/col/profile/top/" + profileFileName;
	} else if (colIDString){
		url = "/service/col/top/" + colIDString;
	}
	
	if (!url) return;
	
	$.ajax({
		type: "GET",
		url : url,
		dataType : 'json',
		success : function(data) {	
			var profileid = 0;
			var stattypeid = 0;
			var rank = 0;
			
			var el_ = null;
		
			for(var i = 0; i < data.length; i++) {
				if (data[i].FileName){
					if (profileid != data[i].ProfileID){
						el_ = $("#" + data[i].FileName);
						el_.find(".topstat").remove();
					}
					
					if (profileid != data[i].ProfileID || stattypeid != data[i].StatTypeID || (rank > 1 && data[i].Rank < rank)) {
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
						geo = geo.toLowerCase();
						var geo_img = "<img src='/images/flags/" + geo + ".gif' class='flag pr-1' title='" + geo + "'/>";
						var el = el_.find(".stat" + data[i].StatTypeID);
						var el2 = document.createElement("div");
						$(el2).addClass("topstat");
						$(el).append(el2);
						var html = '<a href="/stats/' + data[i].stat_url + '/' + data[i].country_url + '">' + geo_img + data[i].Rank + rankAdd + '</a>';
						$(el2).html(html);
						$(el).show();
					}		
						
					profileid = data[i].ProfileID;	
					stattypeid = data[i].StatTypeID;
				}
			}
		}
	})
}

var formatDate = function(d) {
	if (d == null) return "";

	var day = d.getDate();
	var monthNames = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]; 
	var month = monthNames[d.getMonth()];
	var year = d.getFullYear();
	if (day < 10) {
		day = "0" + day;
	}
	
	var date = day + " " + month + " " + year;

	return date;
};	

var isToday = function(date){
	if (typeof date === 'string'){
		date = new Date(date);
	}
	
	var today = new Date();
	
    return date.getFullYear() == today.getFullYear()
        && date.getMonth() == today.getMonth()
        && date.getDate() == today.getDate();
}

var isYesterday = function(date){
	if (typeof date === 'string'){
		date = new Date(date);
	}
	
	var yesterday = new Date();
	yesterday.setDate(yesterday.getDate() - 1);
	
    return date.getFullYear() == yesterday.getFullYear()
        && date.getMonth() == yesterday.getMonth()
        && date.getDate() == yesterday.getDate();
}

var getHumanDate = function(dateText){	
	if (isToday(dateText)) return "today";
	else if (isYesterday(dateText)) return "yesterday";
	else return dateText;
}

/** user **/
	
var saveUser = function(colIDString, climbedAtText, callback){
	$.ajax({
		type: "POST",
		url : "/service/col/athlete/save/" + colIDString,
		data: {
			climbedAt: climbedAtText,

		},
		dataType : 'json',
		success : function(data) {		
			if (callback) callback();
		},
		error: function(err){
			
		}
	});
}

