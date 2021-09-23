var colSearchCount;
var colSearchId;
var colElement = "#search-col";
var colElementWrapper = "#search-col-wrapper";

var athleteSearchId;
var athleteSearchCount;
var athleteElement = "#search-athlete";
var athleteElementWrapper = "#search-athlete-wrapper";

var dateSelectCallback = null;

$(document).ready(function() {

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	setTimeout(function(){
		initToolTip($('[data-toggle="tooltip"]'));
	}, 1000);
	
    /*on keyboard enter press*/
    $(document).keypress(function(e) {
        if (e.which === 13) {
			e.preventDefault();
            if (colSearchCount == 1 && colSearchId){	
                if (history.pushState){
					history.pushState(null, null, window.location.href);
				}
				window.location.replace("/col/" + colSearchId);
			}
        }
    });

	/* init autocomplete */
	var callback = function(ui){
		if (history.pushState) {
			history.pushState(null, null, window.location.href);
		}
		window.location.replace("/col/" + ui.item.ColIDString);
	}

	initColSearch(colElement,colElementWrapper,callback);
	
	if ($(athleteElement).length > 0){
		initAthleteSearch();
	}
	
	/* init modal ride */
	$('#modalRide').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var fileName = button.data('ride');
		var date = button.data('date');
 
		var modal = $(this);
		
		modal.find('.date').text(date);	

		var img = modal.find('.ride-img');
		img.attr("src",img.data("path") + "/" + fileName + ".gif");	
	});
	
	$('#modalProfile').draggable({
		handle: ".modal-dialog"
	}); 
	
	/* init modal profile */
	$('#modalProfile').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var fileName = button.data('profile');
		var col = button.data('col');
		var remarks = button.data('remarks');
		var modeLight = button.data('mode') == "light";
 
		var modal = $(this);
		modal.find('.modal-content').hide();
				
		$.ajax({
			type: "GET",
			url : "/service/col/profile/" + fileName,
			dataType : 'json',
			success : function(data) {
				if (!modeLight){
					modal.find('.category').removeClass("category-1 category-2 category-3 category-4 category-5").addClass("category-" + data.Category).text(data.Category);
					modal.find('.modal-title').text(col);
					if (data.Side){
						modal.find('.modal-title-secondary').html("<img class=\"direction mr-1\" src=\"/images/" + data.Side + ".png\"/>" + data.Side);
					} else {
						modal.find('.modal-title-secondary').text("");  
					}
					if (remarks){
						modal.find('.modal-remarks').text(remarks); 
					} else {
						modal.find('.modal-remarks').text(""); 
						}
					
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

					modal.find('.modal-header').show();
					modal.find('.modal-footer').addClass("d-flex").removeClass("d-none");
				} else {
					modal.find('.modal-header').hide();
					modal.find('.modal-footer').addClass("d-none").removeClass("d-flex");
				}
				
				var img = modal.find('.profile');
				img.attr("src",img.data("path") + "/" + data.FileName + ".gif");
				
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
			saveUserCol(this_.data("colidstring"), dateText);
			
			var dd = isToday(dateText);
			var dd1 = isYesterday(dateText);
			//this_.html(dateText);
			$(".col-climbed-date[data-colidstring='" + colIDString + "']").data("date",dateText);
			$(".col-climbed-date[data-colidstring='" + colIDString + "']").html(getHumanDate(dateText));
			
			if (dateSelectCallback) dateSelectCallback();
		};
		
		var beforeShow = function(input, inst){
			$(input).hide();
		};
		
		var options = {
			changeMonth: true,
			changeYear: true,
			yearRange: "1970:+0",
			dateFormat: "dd M yy",
			maxDate: "+0d",
			beforeShow: beforeShow
		};
		
		var pos = e;
		
		$(this).datepicker( "dialog", date, onSelect, options, pos );
	});

});

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
	return ret.toLowerCase();
};

var initColSearch = function(elSearch, elWrapper, callback){
	
	$.ajax({
		type: "GET",
		url : "/service/cols/search",
		dataType : 'json',
		success : function(data) {
			
			$(elSearch).autocomplete({
				minLength: 2,
				delay: 300,
				appendTo: elWrapper,
				//position: { my : "right top", at: "right bottom" },
				source: function( request, response ) {
					var matcher = new RegExp( $.ui.autocomplete.escapeRegex(  normalize( request.term ) ), "i" );
					var res = $.grep( data, function( value ) {
						value = value.label || value.value || value;
						return matcher.test( normalize( value ) );
					})
					
					colSearchCount = res.length;
					if (colSearchCount > 0){
						colSearchId = res[0].ColIDString;
					}
					
					response(res.slice(0,10));
				},
				select: function( event, ui ) {	
					if (callback){
						callback(ui);
					}	

					/*if (history.pushState) {
						history.pushState(null, null, window.location.href);
					}
					window.location.replace("/col/" + ui.item.ColIDString);*/
		 
					return false;
				},
				response: function(event, ui) {
					var remark = null;
					
					if (ui.content.length === 0) {
						remark = "No cols found";
					} else {
						remark = colSearchCount + " cols found" + (colSearchCount > 10 ? ", showing first 10" : "");
					}

					var remarks = { "isRemark": true, "remark": remark };
					
					ui.content.forEach(function(c){
						c.isRemark = false;
					});
					
					ui.content.unshift(remarks);
				},
				open: function() {
					var ui = $(colElement);
							
					if (ui.parent().find(colElementWrapper).length > 0){
						var width = ui.outerWidth();
						var height = ui.outerHeight();
						var top = ui.position().top;
								
						ui.parent().find(colElementWrapper)
							.width(width)
							.css("top", top);
					}
					
					ui.addClass("ui-autocomplete-input-open");
					$(".ui-autocomplete").addClass("list-group");
				},
				close: function() {
					$(colElement).removeClass("ui-autocomplete-input-open");
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
					html += "<span class=\"badge badge-elevation font-weight-light\">" + item.Height + "m</span></a>";
					return $( "<li>" )
						.append(html)
						.addClass("list-group-item list-group-item-action p-1 font-weight-light")
						.appendTo( ul );
				}
			};
		}
	});
}

var initAthleteSearch = function(){
	
	$.ajax({
		type: "GET",
		url : "/service/athletes/search",
		dataType : 'json',
		success : function(data) {
			
			$(athleteElement).autocomplete({
				minLength: 2,
				delay: 300,
				appendTo: athleteElementWrapper,
				//position: { my : "right top", at: "right bottom" },
				source: function( request, response ) {
					var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
					var res = $.grep( data, function( value ) {
						value = value.name;
						return matcher.test( value ) || matcher.test( normalize( value ) );
					})
					
					athleteSearchCount = res.length;
					if (athleteSearchCount > 0){
						athleteSearchId = res[0].slug;
					}
					
					response(res.slice(0,10));
				},
				select: function( event, ui ) {		
					if (history.pushState) {
						history.pushState(null, null, window.location.href);
					}
					window.location.replace("/athlete/" + ui.item.slug);
		 
					return false;
				},
				response: function(event, ui) {
					var remark = null;
					
					if (ui.content.length === 0) {
						remark = "No athletes found";
					} else {
						remark = athleteSearchCount + " athletes found" + (athleteSearchCount > 10 ? ", showing first 10" : "");
					}

					var remarks = { "isRemark": true, "remark": remark };
					
					ui.content.forEach(function(c){
						c.isRemark = false;
					});
					
					ui.content.unshift(remarks);
				},
				open: function() {
					var ui = $(athleteElement);
							
					if (ui.parent().find(athleteElementWrapper).length > 0){
						var width = ui.outerWidth();
						var height = ui.outerHeight();
						var top = ui.position().top;
								
						ui.parent().find(athleteElementWrapper)
							.width(width)
							.css("top", top);
					}
					
					ui.addClass("ui-autocomplete-input-open");
					$(".ui-autocomplete").addClass("list-group");
				},
				close: function() {
					$(athleteElement).removeClass("ui-autocomplete-input-open");
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
					var html = "<span class=\"px-1\">";	
					if (item.following){
						html += "<i class=\"fas fas-grey fa-check text-small-75 mr-1\"></i>";
					}
					html += item.name + "</span>";
					
					return $( "<li>" )
						.append(html)
						.addClass("list-group-item list-group-item-action p-1 font-weight-light")
						.appendTo( ul );
				}
			};
		}
	});
}

var getBanners = function(selector,colIDString,count,contact) {	
	var url = "/service/banners?col=" + colIDString;
	if (count){
		url += "&cnt=" + count;
	}
	if (contact != null){
		url += "&ct=" + contact;
	}

	$.ajax({
		type: "GET",
		url: url,
		dataType : 'json',
		success : function(result) {	
			if (result.success){
				$(selector).html(result.html);		
			}
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
				//rank = 0;
				
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
						var geoIDString = "Europe";
						var flag = true;
						var hide = false;
						if (data[i].GeoID > 0) {
							if (data[i].GeoID == data[i].Country1ID){
								geo = data[i].Country1;
								geoIDString = data[i].Country1IDString;
							} else if (data[i].GeoID == data[i].Country2ID && data[i].Country2ID > 0) {
								geo = data[i].Country2;
								geoIDString = data[i].Country2IDString;
							} else if (data[i].GeoID == data[i].Region1ID && data[i].Region1ID > 0) {
								geo = data[i].Region1;
								geoIDString = geo;
								flag = false;
								if (rank > 3) hide = true;
							}
							else if (data[i].GeoID == data[i].Region2ID && data[i].Region2ID > 0) {
								geo = data[i].Region2;
								geoIDString = geo;
								flag = false;
								if (rank > 3) hide = true;
							}
							else if (data[i].GeoID == data[i].SubRegion1ID && data[i].SubRegion1ID > 0) {
								geo = data[i].SubRegion1;
								geoIDString = geo;
								flag = false;
								if (rank > 3) hide = true;
							}
							else if (data[i].GeoID == data[i].SubRegion2ID && data[i].SubRegion2ID > 0) {
								geo = data[i].SubRegion2;
								geoIDString = geo;
								flag = false;
								if (rank > 3) hide = true;
							}
						}
						
						if (!hide){
							var rank = data[i].Rank + rankAdd;
							if (!flag){
								rank = "(" + rank + ")";
							}
							var geo_img = "";
							if (flag) geo_img = "<img src='/images/flags/" + geoIDString + ".gif' class='flag pr-1'/>";
							var el = el_.find(".stat" + data[i].StatTypeID);
							var el2 = document.createElement("div");
							$(el2).addClass("topstat");
							$(el2).attr("title", geo);
							$(el).append(el2);
							var html = '<a href="/stats/' + data[i].stat_url + '/' + data[i].geo_url + '" title="' + geo + '">' + geo_img + rank + '</a>';
							$(el2).html(html);
							$(el).show();
						}
					}		
						
					profileid = data[i].ProfileID;	
					stattypeid = data[i].StatTypeID;
				}
			}
		}
	});
}
	
var initToolTip = function(el,container){
	if (!container) container = false;
	
	$(el).tooltip({
		html: true,
		container: container,
		template: '<div class="tooltip" role="tooltip"><div class="arrow shadow"></div><div class="tooltip-inner font-weight-light text-small-75 shadow"></div></div>'
	});		
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
	
var saveUserCol = function(colIDString, climbedAtText, callback){
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
	
var deleteUserCol = function(colIDString, callback){
	$.ajax({
		type: "POST",
		url : "/service/col/athlete/delete/" + colIDString,
		dataType : 'json',
		success : function(data) {		
			if (callback) callback();
		},
		error: function(err){
			
		}
	});
}

var openActivities = function(activities){
	activities = activities.split(";");
	
	activities.forEach(function(a){
		window.open('https://www.strava.com/activities/' + a,'_blank');
	});
}

