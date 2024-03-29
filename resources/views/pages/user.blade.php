@extends('layouts.master')

@section('title')
CyclingCols - My CyclingCols
@stop

@section('content')

<script type="text/javascript">	
	var listMode = "";
	var listSlug = "";

	var getLists = function() {
		var url = "/service/list/lists/{{$user->id}}";
		
		$.ajax({
			type: "GET",
			url: url,
			dataType : 'json',
			success : function(result) {	
				if (result.success){
					$("#lists").html(result.html);		
				}
			}
		})
	}

<?php
	$isOwner = false;
	
	$user_ = Auth::user();
	
	if ($user_){
		$isOwner = ($user->id == $user_->id);	
	}
	
	if ($isOwner){
?>

	var processed = false;
		
	$(document).ready(function() {
		getLists();

		$('#modalNewList').on('show.bs.modal', function (e) {
			$('#txtNewListName').val("");

			var button = $(event.target);
			listMode = button.data('mode');
  			
			if (listMode == "edit"){
				$('#lblNewList').html("Edit List");

				listSlug = button.data('slug');
				var name = button.data('name');

				$('#txtNewListName').val(name);
				$('#btnNewListCreate').addClass("d-none");
				$('#btnNewListSave').removeClass("d-none");
				$('#btnNewListDelete_').removeClass("d-none");
			} else if (listMode == "create"){
				$('#lblNewList').html("Create New List");
				$('#btnNewListCreate').removeClass("d-none");
				$('#btnNewListSave').addClass("d-none");
				$('#btnNewListDelete_').addClass("d-none");
			}
		});

		$('#modalNewList').on('shown.bs.modal', function (e) {
			$('#txtNewListName').focus();
		});

		$('#btnNewListCreate').on('click', function (e) {
			var listName = $('#txtNewListName').val();

			if (!listName){
				$('#divNewListInvalid').removeClass("d-none");
			} else {
				$('#divNewListInvalid').addClass("d-none");

				//create new list
				var url = "/service/list/create";
		
				$.ajax({
					type: "POST",
					url : url,
					dataType : 'json',
					data: {
						name: listName
					},
					success : function(result) {	
						if (result.success){
							getLists();
							$('#modalNewList').modal('hide');
						}
					}
				});	
			}
		});

		$('#btnNewListSave').on('click', function (e) {
			var listName = $('#txtNewListName').val();

			if (!listName){
				$('#divNewListInvalid').removeClass("d-none");
			} else {
				$('#divNewListInvalid').addClass("d-none");

				//save new list
				var url = "/service/list/update";

				$.ajax({
					type: "PUT",
					url : url,
					dataType : 'json',
					data: {
						slug: listSlug,
						name: listName
					},
					success : function(result) {	
						if (result.success){
							getLists();
							$('#modalNewList').modal('hide');
						}
					}
				});	
			}
		});

		$('#btnNewListDelete').on('click', function (e) {
			//delete new list
			var url = "/service/list/delete";

			$.ajax({
				type: "DELETE",
				url : url,
				dataType : 'json',
				data: {
					slug: listSlug
				},
				success : function(result) {	
					if (result.success){
						getLists();
						$('#modalNewList').modal('hide');
						$('#collapseDeleteList').collapse('hide');
					}
				}
			});
		});

		refreshStravaStatus();
@if (!$isOwner)
		getFollowing();
@endif
		
		$(".follow").hover(function(){
			if ($(this).hasClass("text-primary")){
				$(this).addClass("text-secondary").removeClass("text-primary");
				$(this).find("span").html("stop following");
			} else {
				$(this).addClass("text-primary").removeClass("text-secondary");	
				$(this).find("span").html("start following");
			}
		},function(){
			if ($(this).hasClass("text-primary")){
				$(this).addClass("text-secondary").removeClass("text-primary");
				$(this).find("span").html("not following");
			} else {
				$(this).addClass("text-primary").removeClass("text-secondary");	
				$(this).find("span").html("following");
			}			
		});
	});

	var refreshStravaStatus = function(){
		$.ajax({
			type: "GET",
			url : "/service/strava/status/" + processed,
			dataType : 'json',
			success : function(result) {	
				if (result.success){
					if (result.strava_processing){
						processed = true;
					}
					
					$("#stravastatus").html(result.html);	
		
					setTimeout(function(){
						refreshStravaStatus();
					}, 10000);
				}
			}
		});
	}

@if (!$isOwner)	
	var getFollowing = function(){
		var url = "/service/user/following/{{$user->slug}}";
		
		$.ajax({
			type: "GET",
			url : url,
			dataType : 'json',
			success : function(result) {	
				if (result.success){
					$("#following").html(result.html);		
				}
			}
		});		
	}
@endif

<?php			
	} else {
?>		
	$(document).ready(function() {
		getFollowing();
		getLists();
	});
	
	var followMouseout = function(el){
		if ($(el).hasClass("text-primary")){
			$(el).addClass("text-secondary").removeClass("text-primary");
			$(el).find("span").html("not following");
		} else {
			$(el).addClass("text-primary").removeClass("text-secondary");	
			$(el).find("span").html("following");
		}			
	}
	
	var initFollowing = function(){
		/*$(".follow").on("mouseenter", function(){
			if ($(this).hasClass("text-primary")){
				$(this).addClass("text-secondary").removeClass("text-primary");
				$(this).find("span").html("stop following");
			} else {
				$(this).addClass("text-primary").removeClass("text-secondary");	
				$(this).find("span").html("start following");
			}
		});
		
		$(".follow").on("mouseout", function(){
			followMouseout(this);		
		});		*/
		initToolTip($(".follow"));
		
		$(".follow").on("click", function(){
			$(this).addClass("cursor-wait");
			$(this).tooltip("hide");
			
			var url = "/service/user/";
			
			if ($(this).hasClass("follow-yes")){
				url += "unfollow";
			} else {
				url += "follow";
			}
			
			url += "/{{$user->slug}}";
			
			var this_ = this;
			
			$.ajax({
				type: "POST",
				url : url,
				dataType : 'json',
				success : function(result) {	
					if (result.success){
						getFollowing(function(){	
							$(this).removeClass("cursor-wait");
						});
						//followMouseout(this_);
					}
				},
				error: function(result) {		
					$(this).removeClass("cursor-wait");
				}
			});
		});		
	}

	var getFollowing = function(callback){
		var url = "/service/user/following/{{$user->slug}}";
		
		$.ajax({
			type: "GET",
			url : url,
			dataType : 'json',
			success : function(result) {	
				if (result.success){
					$("#following").html(result.html);	
					initFollowing();
					if (callback) callback();
				}
			}
		})		
	}

<?php			
	}
?>

</script>
<main role="main" class="bd-content">
    <div class="header px-4 py-3 row m-0">
		<div class="col-xs-12 col-md-4 px-0 pt-1 d-flex align-items-baseline">
			<h4 class="font-weight-light d-inline">Dashboard</h4>
			<span class="border rounded bg-light ml-2 px-2 py-1 font-weight-light">{{$user->name}}</span>
			<div id="following"></div>
			<!--<div class="text-small-75 text-primary ml-2 follow">
				<i class="fas fa-check"></i> 
				<span>following</span>
			</div>
			<div class="text-small-75 text-secondary ml-2 follow">
				<i class="fas fa-check"></i>
				<span>not following</span>
			</div>-->
		</div>
		<div class="col-xs-12 col-md-4 px-0 pt-1"></div>
		<div class="col-xs-12 col-md-4 px-0 pr-sm-3 pt-1">
			<input id="search-athlete" class="search-input form-control mr-sm-2 px-2 py-1 font-weight-light" type="search" placeholder="Search an athlete...">
			<div id="search-athlete-wrapper" class="search-input-wrapper ui-front"></div>
		</div>	
	</div>
	<div class="container-fluid">
		<div class="card-columns-3">
			<div class="card mb-3">
				<div class="card-header p-2">
					Overview
				</div>
				<div class="card-body p-2 font-weight-light text-small-90 text-center">
					<div class="kpi kpi-1">
						<span class="">{{$climbed_count}}</span>
					</div>
					<div class="mb-2">Cols Climbed</div>
					<div class="p-1 d-inline-block">
						<div class="bar bar-big bar-year bar-rounded-left" style="width: {{$width_climbed * 100}}px;"></div>
						<div class="bar bar-big bar-total bar-rounded-right" style="width: {{$width_total * 100}}px;"></div>
					</div>
					<div class="mb-3">{{$perc_climbed}}% Of All Cols</div>
					<div class="kpi kpi-2">
						<span class="">{{$climbed_year_count}}</span>
					</div>
					<div class="mb-3">In {{date("Y")}}</div>
					<div class="kpi kpi-3">
						<span class="">{{$climbed_lastyear_count}}</span>
					</div>
					<div class="mb-3">In {{date("Y") - 1}}</div>
<?php
	if (count($highest) > 0){
		$h = $highest[0];
		
?>
					<h3 class="mb-1">
						<i class="fas fas-grey fa-mountain no-pointer"></i>
					</h3>
					<div class="mb-1 d-flex justify-content-center align-items-baseline">
						<img src="/images/flags/{{$h->Country1IDString}}.gif" title="{{$h->Country1}}" data-toggle="tooltip" class="flag mr-1">
		@if ($h->Country2)
						<img src="/images/flags/{{$h->Country2IDString}}.gif" title="{{$h->Country2}}" data-toggle="tooltip" class="flag mr-1">
		@endif
						<div class="text-truncate">
							<a href="/col/{{$h->ColIDString}}">{{$h->Col}}</a>
						</div>
						<div class="ml-1 text-small-75 text-centre badge badge-elevation font-weight-light">
							{{$h->Height}}m
						</div>
					</div>
					<div class="mb-3 text-small-75">(highest col climbed)</div>
<?php
	}
	$c = $countries->first();
	if ($c->col_count_user > 0){
?>
					<div class="mb-1">
						<img src="/images/flags/{{$c->CountryIDString}}.gif" class="flag flag-big mr-1">
					</div>
					<div class="d-flex justify-content-center align-items-baseline">
						<div class="text-truncate">
							{{$c->col_count_user}} Cols Climbed In
							<a href="/athlete/{{$user_->slug}}/cols/{{$c->URL}}/climbed">{{$c->Country}}</a>
						</div>
					</div>
					<div class="mb-1 text-small-75">(most popular country)</div>
<?php
	}
?>
				</div>
			</div>
			<!-- -->
@if ($isOwner)
			<div class="card mb-3 text-center">
				<div class="card-body p-0 font-weight-light text-small-90">
					<div class="p-2 border-bottom">
						<h6 class="font-weight-light m-0">Strava</h6>
					</div>
					<div class="p-2 text-center" id="stravastatus">
						<div>Initialize or update your cols list with Strava</div>
						<div class="p-1">
							<a class="btn p-0 disabled">
								<img src="/images/strava/btn_strava_connectwith_orange.png"></img>
                            </a>
						</div>
						<div id="stravastatus">
							&nbsp;
						</div>
					</div>
					<div class="p-2 border-bottom border-top">
						<h6 class="font-weight-light m-0">Map</h6>
					</div>
					<div class="p-2">
						<div>Explore your cols in a map <a href="/map">here</a>.</div>
						<!--<div>Make sure this checkbox is turned on and zoom in.</div>
						<div class="d-flex justify-content-around mt-1">
							<div class="command leaflet-control"><div class="leaflet-bar climbed-control d-flex align-items-center justify-content-around"><a style="outline: none;"><i class="fas fa-check climbed-control-checked"></i></a></div></div>
						</div>-->
					</div>
				</div>
			</div>
			<!-- -->
@endif
		<!--	<div class="card mb-3">
				<div class="card-header p-2 d-flex align-items-baseline">
					<div class="">Cols Climbed Per List</div>			
@if ($isOwner)
					<div class="text-small text-right p-1 ml-auto">
						<button type="button" class="btn btn-sm btn-primary font-weight-light text-small-90 px-2 py-1" data-toggle="modal" data-target="#modalNewList" data-mode="create">New List</button>
					</div>
@endif
				</div>
				<div id="lists" class="card-body p-2 font-weight-light text-small-90">
				</div>
			</div>-->
			<div class="card mb-3">
				<div class="card-header p-2 d-flex align-items-end">
					<div class="">Cols Climbed Per Country</div>
					<div class="text-small-90 text-right p-1 ml-auto" style="flex: 0 0 100px;">
						<div class="bar bar-year bar-rounded-left" style="width: 40px;">
							<span class="text-small-60 text-center float-left pl-1">{{date("Y")}}</span>
						</div>
						<div class="bar bar-total bar-rounded-right" style="width: 50px;">
							<span class="text-small-60 text-center float-left pl-1">total</span>
						</div>
					</div>
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
@foreach($countries as $country)
					<div class="align-items-end d-flex">
						<img src="/images/flags/{{$country->CountryIDString}}.gif" title="{{$country->Country}}" data-toggle="tooltip" class="flag mr-1">
						<div class="text-truncate">
							<a href="/athlete/{{$user_->slug}}/cols/{{$country->URL}}/climbed">{{$country->Country}}</a>
						</div>
						<div class="ml-auto text-small-90 text-right" style="flex: 0 0 30px;">
							{{$country->col_count_user}}
						</div>
						<div class="p-1" style="flex: 0 0 80px;">
	@if ($country->width_year > 0)
							<div class="bar bar-year
		@if ($country->width > 0)
			bar-rounded-left
		@else
			bar-rounded
		@endif		
							" style="width: {{$country->width_year * 70}}px;"></div>
	@endif
	@if ($country->width > 0)
							<div class="bar bar-total
		@if ($country->width_year > 0)
			bar-rounded-right
		@else
			bar-rounded
		@endif					
						" style="width: {{$country->width * 70}}px;"></div>
	@endif
						</div>
						
	@if ($country->perc_climbed == 100)
						<div class="text-small-75 text-right font-weight-normal" style="flex: 0 0 60px;">complete</div>
	@else
						<div class="text-small-75 text-right" style="flex: 0 0 60px;">{{$country->perc_climbed}} %</div>
	@endif
						<!--<div class="text-small-75 text-right" style="flex: 0 0 60px;">
							of {{$country->col_count}}
						</div>-->
					</div>
@endforeach
				</div>
			</div>	
			<!-- -->
			<div class="card mb-3">
				<div class="card-header p-2">
					<span>Cols</span>
				</div>
				<div class="card-body p-0 font-weight-light text-small-90">
					<div class="p-2 border-bottom d-flex align-items-center">
						<h6 class="font-weight-light m-0">Last Climbed</h6>
						<div class="ml-auto" tabindex="0" role="button">
							<a href="/athlete/{{$user->slug}}/cols/eur/climbed"><i class="fas fas-grey fa-search-plus" title="Show all" data-toggle="tooltip"></i></a>
						</div>
					</div>
					<div class="p-2">
@if (count($climbed) == 0)
						<span class="text-small-75">No cols climbed yet.</span>
@else
	@foreach($climbed as $climbed_)
						<div class="align-items-baseline d-flex">
							<img src="/images/flags/{{$climbed_->Country1IDString}}.gif" title="{{$climbed_->Country1}}" data-toggle="tooltip" class="flag mr-1">
		@if ($climbed_->Country2)
							<img src="/images/flags/{{$climbed_->Country2IDString}}.gif" title="{{$climbed_->Country2}}" data-toggle="tooltip" class="flag mr-1">
		@endif
							<div class="text-truncate">
								<a href="/col/{{$climbed_->ColIDString}}">{{$climbed_->Col}}</a>
							</div>
		@if ($isOwner)
							<div class="col-climbed-date ml-auto text-small-75 text-right" style="flex: 0 0 75px;" data-colidstring="{{$climbed_->ColIDString}}" data-date="{{getDate_dMY($climbed_->pivot->ClimbedAt)}}">
		@else
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
		@endif
		@if ($climbed_->pivot->ClimbedAt)
							{{getHumanDate($climbed_->pivot->ClimbedAt)}}
		@elseif ($isOwner)
							add date
		@else
							unknown
		@endif
							</div>
							<div class="ml-1 text-small-75 text-centre font-weight-light" style="flex: 0 0 15px;">
		@if ($climbed_->pivot->StravaActivityIDs != null)
								<div class="pointer viewonstrava" onclick="openActivities('{{$climbed_->pivot->StravaActivityIDs}}'); return false;" title="View on Strava" data-toggle="tooltip">
									<img src="/images/strava.png"/ class="w-100 strava-icon">
								</div>
		@endif
							</div>
						</div>
	@endforeach
@endif
					</div>			
					<div class="p-2 border-bottom border-top d-flex align-items-center">						
						<h6 class="font-weight-light m-0">Last Claimed</h6>
						<div class="ml-auto" tabindex="0" role="button">
							<a href="/athlete/{{$user->slug}}/cols/eur/claimed"><i class="fas fas-grey fa-search-plus" title="Show all" data-toggle="tooltip"></i></a>
						</div>
					</div>				
					<div class="p-2">
@if (count($claimed) == 0)
						<span class="text-small-75">No cols claimed yet.</span>
@else
	@foreach($claimed as $claimed_)
						<div class="align-items-baseline d-flex">
							<img src="/images/flags/{{$claimed_->Country1IDString}}.gif" title="{{$claimed_->Country1}}" data-toggle="tooltip" class="flag mr-1">
		@if ($claimed_->Country2)
							<img src="/images/flags/{{$claimed_->Country2IDString}}.gif" title="{{$claimed_->Country2}}" data-toggle="tooltip" class="flag mr-1">
		@endif
							<div class="text-truncate">
								<a href="/col/{{$claimed_->ColIDString}}">{{$claimed_->Col}}</a>
							</div>
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 70px;">
							{{getHumanDate($claimed_->pivot->CreatedAt)}}
							</div>
							<div class="ml-1 text-small-75 text-centre font-weight-light" style="flex: 0 0 15px;">
		@if ($claimed_->pivot->StravaActivityIDs != null)
								<div class="pointer" onclick="openActivities('{{$claimed_->pivot->StravaActivityIDs}}'); return false;">
									<img src="/images/strava.png"/ class="w-100 strava-icon">
								</div>
		@endif
							</div>
						</div>
	@endforeach
@endif
					</div>	
					<div class="p-2 border-bottom border-top d-flex align-items-center">
						<h6 class="font-weight-light m-0">Highest</h6>
						<div class="ml-auto" tabindex="0" role="button">
							<a href="/athlete/{{$user->slug}}/cols/eur/elevation"><i class="fas fas-grey fa-search-plus" title="Show all" data-toggle="tooltip"></i></a>
						</div>
					</div>
					<div class="p-2">
@if (count($highest) == 0)
						<span class="text-small-75">No cols claimed yet.</span>
@else
	@foreach($highest as $highest_)
						<div class="align-items-baseline d-flex">
							<img src="/images/flags/{{$highest_->Country1IDString}}.gif" title="{{$highest_->Country1}}" data-toggle="tooltip" class="flag mr-1">
		@if ($highest_->Country2)
							<img src="/images/flags/{{$highest_->Country2IDString}}.gif" title="{{$highest_->Country2}}" data-toggle="tooltip" class="flag mr-1">
		@endif
							<div class="text-truncate">
								<a href="/col/{{$highest_->ColIDString}}">{{$highest_->Col}}</a>
							</div>
							<div class="ml-auto text-small-75 text-centre badge badge-elevation font-weight-light" style="flex: 0 0 45px;">
							{{$highest_->Height}}m
							</div>
							<div class="ml-1 text-small-75 text-centre font-weight-light" style="flex: 0 0 15px;">
		@if ($highest_->pivot->StravaActivityIDs != null)
								<div class="pointer" onclick="openActivities('{{$highest_->pivot->StravaActivityIDs}}'); return false;">
									<img src="/images/strava.png"/ class="w-100 strava-icon">
								</div>
		@endif
							</div>
						</div>
	@endforeach
@endif
					</div>
				</div>
			</div>
			<div class="card mb-3">
				<div class="card-header p-2 d-flex align-items-end">
					<span>Most Popular Regions</span>
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
@foreach($regions as $region)
	@break($loop->index == 10)
					<div class="align-items-end d-flex">
						<img src="/images/flags/{{$region->country->CountryIDString}}.gif" title="{{$region->country->Country}}" data-toggle="tooltip" class="flag mr-1">
						<div class="text-truncate">
							<a href="/athlete/{{$user_->slug}}/cols/{{$region->URL}}/climbed">{{$region->Region}}</a>
						</div>
	@if ($region->perc_climbed == 100)
						<div class="ml-auto text-small-75 text-right font-weight-normal">complete</div>
	@else
						<div class="ml-auto text-small-75 text-right">{{$region->perc_climbed}} %</div>
	@endif
					</div>
@endforeach
				</div>
			</div>
		</div>
	</div>

	<!-- Modal List -->
	<div class="modal fade" id="modalNewList" tabindex="-1" role="dialog" aria-labelledby="lblNewList" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="modal-title" id="lblNewList">Create New List</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-small-90">
					<input type="text" class="form-control" id="txtNewListName" placeholder="Enter name of list">
					<div class="text-danger text-small-90 px-3 py-1 d-none" id="divNewListInvalid">Invalid name for list</div>
				</div>
				<div class="modal-footer text-small-90">
					<button type="button" class="btn btn-sm btn-secondary font-weight-light" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-sm btn-primary font-weight-light" id="btnNewListCreate">Create</button>
					<button type="button" class="btn btn-sm btn-primary font-weight-light" id="btnNewListSave">Save</button>
					<button type="button" class="btn btn-sm btn-primary font-weight-light" id="btnNewListDelete_" data-toggle="collapse" data-target="#collapseDeleteList">Delete</button>
				</div>
				<div class="collapse text-small-90 p-2 text-right" id="collapseDeleteList">
					<span class="text-danger">Are you sure you want to delete this list?</span>
					<button type="button" class="btn btn-sm btn-primary font-weight-light" id="btnNewListDelete">Yes</button>
					<button type="button" class="btn btn-sm btn-secondary font-weight-light" data-toggle="collapse" data-target="#collapseDeleteList">No</button>
				</div>
			</div>
		</div>
	</div>
</main>
@stop