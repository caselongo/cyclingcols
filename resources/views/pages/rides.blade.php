@extends('layouts.master')

@section('title')
CyclingCols - Rides
@stop

@section('content')
<script type="text/javascript">	
$(document).ready(function() {
	getRides();
	
	$("#year").on("change",function(){
		showRides();
	});
	
	$("#month").on("change",function(){
		showRides();
	});
	
	$("#day").on("change",function(){
		showRides();
	});
	
	$("#orderby").on("change",function(){
		showRides();
	});
});

var rides = [];
var rides_show = [];
var years = [];

getRides = function() {
	$.ajax({
		type: "GET",
		url : "/service/rides",
		dataType : 'json',
		success : function(data) {
			rides = data;
			
			var max_year = -1;
			for(var i = 0; i < rides.length; i++){
				rides[i].Year = parseInt(rides[i].DateSort.toString().substring(0,4));
				rides[i].Month = parseInt(rides[i].DateSort.toString().substring(4,6));
				rides[i].Day = parseInt(rides[i].DateSort.toString().substring(6,8));
				if (rides[i].TempMax && rides[i].TempMin){
					rides[i].TempMaxMin = parseInt(rides[i].TempMax*100)+parseInt(rides[i].TempMin);
					rides[i].TempMinMax = parseInt(rides[i].TempMin*100)+parseInt(rides[i].TempMax);
				}
				if ($.inArray(rides[i].Year,years) == -1){
					years.push(rides[i].Year);
					$("#year").append('<option value="' + rides[i].Year + '">' + rides[i].Year + '</option>');
				}
				
				if(rides[i].Year > max_year){
					max_year = rides[i].Year;
				}
			}
			
			$("#year").val(max_year);
			
			showRides();
		}
	})
}

showRides = function(){
	rides_show = [];
	
	var year = $("#year").val();
	var month = $("#month").val();
	var day = $("#day").val();
	var orderby = $("#orderby").val();
	var orderby_field = "";
	var orderby_factor = 1;
	
	if(orderby == "last"){
		orderby_field = "DateSort";
		orderby_factor = -1;
	} else if(orderby == "first"){
		orderby_field = "DateSort";
		orderby_factor = 1;
	} else if(orderby == "longest"){
		orderby_field = "Distance";
		orderby_factor = -1;
	} else if(orderby == "shortest"){
		orderby_field = "Distance";
		orderby_factor = 1;
	} else if(orderby == "highest"){
		orderby_field = "HeightDiff";
		orderby_factor = -1;
	} else if(orderby == "lowest"){
		orderby_field = "HeightDiff";
		orderby_factor = 1;
	} else if(orderby == "toughest"){
		orderby_field = "RideIndex";
		orderby_factor = -1;
	} else if(orderby == "easiest"){
		orderby_field = "RideIndex";
		orderby_factor = 1;
	} else if(orderby == "hottest"){
		orderby_field = "TempMaxMin";
		orderby_factor = -1;
	} else if(orderby == "coldest"){
		orderby_field = "TempMinMax";
		orderby_factor = 1;
	}
	
	for(var i = 0; i < rides.length; i++){
		if ((rides[i].Year == year || year == -1) && (rides[i].Month == month || month == -1) && (rides[i].Day == day || day == -1)){
			rides_show.push(rides[i]);
		}
	}
	
	rides_show = rides_show.filter(function(r){
		return r[orderby_field];
	});

	rides_show.sort(function(a,b){
		return a[orderby_field]*orderby_factor - b[orderby_field]*orderby_factor;
	});
	
	var max = rides_show.length;
	if (year == -1 && max > 50){
		max = 50;
	}
	
	$("tbody").empty();
	
	for (var i = 0; i < max; i++){
		var r = rides_show[i];
		
		var distance = r.Distance;
		var heightdiff = r.HeightDiff;
		
		var stage = r.Stage.split(" vv")[0];
		
		var temp = "";
		if (r.TempMin && r.TempMax){
			temp = r.TempMin + "/" + r.TempMax + "&deg;C"; 
		}
		
		var c = r.Countries.split(",");
		var countries = "";
		for (var j = 0; j < c.length; j++){
			countries += "<img class=\"flag ml-1\" src=\"/images/flags/" + c[j] + ".gif\"></img>";
		}
		
		var cols = "";
		if (r.Cols){
			cols = r.Cols;
		}
		
		var weather = "";
		if (r.WeatherCode > 0){
			weather = "<img class=\"ride-weather\" src=\"/images/weather/weather" + r.WeatherCode + ".gif\"></img>";
		}
		
		var profile = "";
		if (r.FileName){
			
			profile = "<a tabindex=\"0\" role=\"button\" data-toggle=\"modal\" data-target=\"#modalRide\" data-ride=\"" + r.FileName + "\" data-date=\"" + r.Date + "\"><img class=\"ride-profile\" src=\"{{env('TOURS_PATH','/tours')}}/" + r.FileName + ".gif\"></img></a>";
		}
		
		var class_ = "ride-index-4";
		if (r.RideIndex <= 26) { class_ = "ride-index-3"; }
		if (r.RideIndex <= 22) { class_ = "ride-index-2"; }
		if (r.RideIndex <= 19) { class_ = "ride-index-1"; }
		if (r.RideIndex <= 16) { class_ = "ride-index-0"; }

		var html = "<tr class=\"text-small-75 border-bottom ride\">";
		html += "<td class=\"ride-date\">" + r.Date + "</td>";
		html += "<td>" + stage + "</td>";
		html += "<td class=\"ride-countries\">" + countries + "</td>";
		html += "<td>" + distance + "</td>";
		html += "<td>" + heightdiff + "</td>";
		html += "<td><div class=\"border rounded text-center ride-index " + class_ + "\">" + r.RideIndex + "</div></td>";
		html += "<td>" + profile + "</td>";
		html += "<td>" + cols + "</td>";
		html += "<td>" + weather + "</td>";
		html += "<td>" + temp + "</td>";
		html += "</tr>";
	
		$("tbody").append(html);
	}		
}

</script>

<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light">CyclingCols Rides</h4>
	</div>	
	<div class="container-fluid px-4 pb-4">
		<div class="font-weight-light">
			<label for="year">Year</label>
			<select id="year">
				<option value="-1">All</option>
			</select>
			<label for="month">Month</label>
			<select id="month">
				<option value="-1">All</option>
				<option value="1">January</option>
				<option value="2">February</option>
				<option value="3">March</option>
				<option value="4">April</option>
				<option value="5">May</option>
				<option value="6">June</option>
				<option value="7">July</option>
				<option value="8">August</option>
				<option value="9">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>
			</select>
			<label for="day">Day</label>
			<select id="day">
				<option value="-1">All</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
			</select>
			<label for="orderby">Order By</label>
			<select id="orderby">
				<option value="last">Last</option>
				<option value="first">First</option>
				<option value="longest">Longest</option>
				<option value="shortest">Shortest</option>
				<option value="highest">Highest</option>
				<option value="lowest">Lowest</option>
				<option value="toughest">Toughest</option>
				<option value="easiest">Easiest</option>
				<option value="hottest">Hottest</option>
				<option value="coldest">Coldest</option>
			</select>
		</div>
		<div class="font-weight-light">
			<table id="#rides" class="w-100">
				<thead class="border-top border-bottom">
					<tr>
						<td>Date</td>
						<td>From( - To)</td>
						<td>Countries</td>
						<td>KM</td>
						<td>HM</td>
						<td>Index</td>
						<td>Profile</td>
						<td>Cols</td>
						<td>Weather</td>
						<td>Temp</td>
					</tr>
				</thead>
				<tbody>	

				</tbody>
			</table>
		</div>
    </div>
</div>
@stop

@include('includes.ridemodal')

