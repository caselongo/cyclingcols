@extends('layouts.master')

@section('title')
	@if (is_null($list))
		CyclingCols - Lists
	@else
		CyclingCols - Lists - {{$list->Name}}
	@endif
@stop

@section('content')

<link rel="stylesheet" href="/css/leaflet.fullscreen.css" type="text/css">
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>
<script src="/js/leaflet.fullscreen.min.js" type="text/javascript"></script>
<?php
	$isOwner = false;
	
	$rankpos_ = 0;
	$rank_ = 0;
	$stattypeid_ = 0;
	$value_ = 0;

	if (!is_null($list)){
		$user_ = Auth::user();
		
		if ($user_){
			$isOwner = ($user_->id == $list->UserID);	
		}
	}

	if (!is_null($slug)){	
?>
<script type="text/javascript">	


	/* init colsearch */
	var callback = function(ui){
		var div = document.createElement('li')
		$(div).html(ui.item.value);
		$(div).attr("draggable", true);
		$(div).addClass("list-group-item font-weight-light p-1 d-flex align-items-end justify-content-between");
		$("#selected-cols").append(div);
		
		var i = document.createElement('i')
		$(i).addClass("fas fas-grey fa-times");
		$(i).click(function(){
			selectedCols = selectedCols.filter(c => c != ui.item.ColIDString);
			$(this).parent().remove();
		});
		$(div).append(i);

		selectedCols.push(ui.item.ColIDString);

		$("#list-col-search").val("").focus();
	}

	var sectionMode = "";
	var sectionID = 0;
	var selectedCols = [];

	var getList = function() {
		var url = "/service/list/{{$slug}}";
		
		$.ajax({
			type: "GET",
			url: url,
			dataType : 'json',
			data: {
@if ($edit)
				edit: 1
@else
				edit: 0
@endif
			},
			success : function(result) {	
				if (result.success){

					$("#sections").html(result.htmlSections);	
					$("#users").html(result.htmlUsers);	
		
					$('.removeCol').on('click', function (e) {
						$(e.target).tooltip('hide');

						var id = e.target.parentElement.getAttribute("id");

						if (!id) return;

						//save new list
						var url = "/service/listcol/delete";

						$.ajax({
							type: "POST",
							url : url,
							dataType : 'json',
							data: {
								id: id
							},
							success : function(result) {	
								if (result.success){
									getList();
								}
							}
						});	
					});
					
					markers = [];
					colidstring = "";
					for (var i = 0; i < result.cols.length; i++){
						var col = result.cols[i];
						if (colidstring != col.ColIDString){
							markers.push({
								lat: col.Latitude/1000000,
								lng: col.Longitude/1000000,
								colIDString: col.ColIDString,
								title: col.Col
							});

							colid = col.ColIDString;
						}
					}

					if (markers.length > 0){
						var mapOptions = {
							attributionControl: false,
							zoomControl: true,
							dragging: true,
							fullscreenControl: true   
						};
						var map = L.map('map', mapOptions);//.setView([lat, lng], 4
						map.fitBounds(markers.map(function(m){
							return [m.lat, m.lng];
						}),{padding: [20,20]});
						map.scrollWheelZoom.disable();
						
						L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
							attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
						}).addTo(map);
						
						var icon = L.icon({
							iconUrl: '/images/ColRed.png',
							iconAnchor: [16,35]
						});
						
						markers.forEach(function(m){
							var html = m.rank;
						
							var markerOptions = {
								icon: icon,
								bubblingMouseEvents: true
							};
							
							var marker = L.marker([m.lat, m.lng], markerOptions).addTo(map);
							m.marker = marker;
							
							$(marker._icon).attr("title", m.title);
							initToolTip($(marker._icon),$("#map"));
							
							marker.on("click", function() {
								parent.document.location.href = "/col/" + m.colIDString;
							});
						});
					}
				}
			}
		})
	}

	var sortCols = function(idfrom,idto){
		var url = "/service/listcol/sort";

		$.ajax({
			type: "POST",
			url: url,
			dataType : 'json',
			data: {
				idfrom: idfrom,
				idto: idto
			},
			success : function(result) {	
				if (result.success){
				}
			}
		});
	}
	
	var dragCol = function(ev) {
		ev.dataTransfer.setData("id", ev.currentTarget.id);
		//	alert(ev.target.id)
	}

	var dropCol = function(ev) {
		ev.preventDefault();
		var idFrom = ev.dataTransfer.getData("id").replace("c","");
		var idTo = ev.currentTarget.id.replace("c","");

		if (idFrom == idTo) {
			ev.currentTarget.style.backgroundColor = bgColor;
			return;
		}

		//alert(idFrom + "-" + idTo);
		var elFrom = $("#c" + idFrom);
		var elTo = $("#c" + idTo);

		var dummySection = $(elFrom).parents(".card-dummy");
		var siblings = $(elFrom).siblings();

		var upwards = true;

		elTo.prevAll().each(function(index, value){
			if ($(value).attr("id") == idFrom){
				upwards = false;
			}
		})

		//alert(upwards);

		if (upwards){
			elFrom.insertBefore(elTo);
		} else {
			elFrom.insertAfter(elTo);
		}

		if (siblings.length == 0){
			$(dummySection).remove();
		}
		//ev.target.appendChild(document.getElementById(data));
		ev.currentTarget.style.backgroundColor = bgColor;

		sortCols(idFrom, idTo);
	}

	var dropColInSection = function(ev) {
		ev.preventDefault();
		var idFrom = ev.dataTransfer.getData("id").replace("c","");
		var sectionIdTo = ev.currentTarget.id.replace("s","");

		var elFrom = $("#c" + idFrom);
		var elSectionTo = $("#s" + sectionIdTo);

		var dummySection = $(elFrom).parents(".card-dummy");
		var siblings = $(elFrom).siblings();

		$(elSectionTo).parent().find(".card-body").append(elFrom);

		if (siblings.length == 0){
			$(dummySection).remove();
		}

		ev.currentTarget.style.backgroundColor = bgColor;

		sortCols(idFrom, -sectionIdTo);
	}

	var allowDrop = function(ev) {
		ev.preventDefault();
	}

	var bgColor = "";

	var dragEnterCol = function(ev) {
		bgColor = ev.currentTarget.style.backgroundColor;
		ev.currentTarget.style.backgroundColor = "#eee";
	}

	var dragLeaveCol = function(ev) {
		ev.currentTarget.style.backgroundColor = bgColor;
	}
	
	$(document).ready(function() {
		getList();		
		initColSearch("#list-col-search","#list-col-search-wrapper",callback);

		// lists
	
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
							$('#modalNewList').modal('hide');
							document.location.href = "/list/" + result.slug;
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
							$('#modalNewList').modal('hide');
							if (result.slug){
								document.location.href = "/list/" + result.slug;
							} else {
								document.location.href = "/list";
							}
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
						$('#modalNewList').modal('hide');
						$('#collapseDeleteList').collapse('hide');
						document.location.href = "/list";
					}
				}
			});
		});

		// sections

		$('#modalSection').on('show.bs.modal', function (e) {
			$('#txtSectionName').val("");

			var button = $(event.target);
			sectionMode = button.data('mode');
  			
			if (sectionMode == "edit"){
				$('#lblSection').html("Edit Section");

				sectionID = button.data('id');
				var name = button.data('name');

				$('#txtSectionName').val(name);
				$('#btnSectionCreate').addClass("d-none");
				$('#btnSectionSave').removeClass("d-none");
				$('#btnSectionDelete_').removeClass("d-none");
			} else if (sectionMode == "create"){
				$('#lblSection').html("Add Section");
				$('#btnSectionCreate').removeClass("d-none");
				$('#btnSectionSave').addClass("d-none");
				$('#btnSectionDelete_').addClass("d-none");
			}
		});

		$('#modalSection').on('shown.bs.modal', function (e) {
			$('#txtSectionName').focus();
		});

		$('#btnSectionCreate').on('click', function (e) {
			var sectionName = $('#txtSectionName').val();

			if (!sectionName){
				$('#divSectionInvalid').removeClass("d-none");
			} else {
				$('#divSectionInvalid').addClass("d-none");

				//create new list
				var url = "/service/listsection/create";
		
				$.ajax({
					type: "POST",
					url : url,
					dataType : 'json',
					data: {
						list: '{{$list->Slug}}',
						name: sectionName
					},
					success : function(result) {	
						if (result.success){
							getList();
							$('#modalSection').modal('hide');
						}
					}
				});	
			}
		});

		$('#btnSectionSave').on('click', function (e) {
			var sectionName = $('#txtSectionName').val();

			if (!sectionName){
				$('#divSectionInvalid').removeClass("d-none");
			} else {
				$('#divSectionInvalid').addClass("d-none");

				//save new list
				var url = "/service/listsection/update";

				$.ajax({
					type: "PUT",
					url : url,
					dataType : 'json',
					data: {
						id: sectionID,
						name: sectionName
					},
					success : function(result) {	
						if (result.success){
							getList();
							$('#modalSection').modal('hide');
						}
					}
				});	
			}
		});

		$('#btnSectionDelete').on('click', function (e) {
			//delete new list
			var url = "/service/listsection/delete";

			$.ajax({
				type: "DELETE",
				url : url,
				dataType : 'json',
				data: {
					id: sectionID
				},
				success : function(result) {	
					if (result.success){
						getList();
						$('#modalSection').modal('hide');
						$('#collapseSectionDelete').collapse('hide');
					}
				}
			});
		});
		

		$('#modalCols').on('show.bs.modal', function (e) {
			$("#selected-cols").empty();
			
			var button = $(event.target);
			sectionID = button.data('id');
		});

		$('#modalCols').on('shown.bs.modal', function (e) {
			$('#list-col-search').focus();
			selectedCols = [];
		});

		$('#btnColsSave').on('click', function (e) {
			//save new list
			var url = "/service/listcol/create";

			$.ajax({
				type: "POST",
				url : url,
				dataType : 'json',
				data: {
					list: '{{$list->Slug}}',
					sectionid: sectionID,
					cols: selectedCols
				},
				success : function(result) {	
					if (result.success){
						getList();
						$('#modalCols').modal('hide');
						$("#selected-cols").empty();
					}
				}
			});	
		});

	});

</script>

<?php
}
?>

<main role="main" class="bd-content">
    <div class="header px-4 py-3 d-flex align-items-baseline">
        <h4 class="font-weight-light">Lists</h4>
@if (!is_null($list))
		<span class="border rounded bg-light ml-2 px-2 py-1 font-weight-light">
			{{$list->Name}}
			@if (!is_null($list->User))
				- <a href="/athlete/{{$list->User->slug}}">{{$list->User->name}}</a>
			@endif
		</span>
	@if ($isOwner && $edit)
		<i class="fas fas-grey fa-edit ml-2" title="edit" data-toggle="modal" data-target="#modalNewList" data-mode="edit" data-slug="{{$list->Slug}}" data-name="{{$list->Name}}"></i>
	@endif

	@if ($isOwner)
		<div class="text-small text-right p-1 ml-auto">
			@if($edit)
				<button type="button" class="btn btn-sm btn-primary font-weight-light text-small-90 px-2 py-1" data-toggle="modal" data-target="#modalCols" data-id="0">Add Col</button>
				<button type="button" class="btn btn-sm btn-primary font-weight-light text-small-90 px-2 py-1" data-toggle="modal" data-target="#modalSection" data-mode="create">Add Section</button>
				<a role="button" class="btn btn-sm btn-secondary font-weight-light text-small-90 mx-2 px-2 py-1" href="/list/{{$slug}}">Stop Editting</a>
			@else
				<a role="button" class="btn btn-sm btn-secondary font-weight-light text-small-90 px-2 py-1" href="/list/{{$slug}}/edit">Edit List</a>
				<button type="button" class="btn btn-sm btn-primary font-weight-light text-small-90 px-2 py-1" data-toggle="modal" data-target="#modalNewList" data-mode="create">New List</button>
			@endif
			<!--<span class="text-small-75">For example: 
				<span class="font-italic">Day 1, Stage 10, Nice To Haves, etc.</span>
			</span>-->
		</div>
	@endif
@endif
	</div>	
	<div class="w-100 d-flex align-items-start flex-wrap">
		<div class="w-100 w-lg-25 p-3"><!--sidebar-->
<?php
	$userid = -1;
	foreach($lists as $lists_){
		if ($lists_->UserID != $userid){
			if ($lists_->UserID == 0){
?>
			<div class="mb-1">Public Lists</div>
<?php
			} else {
?>
			<div class="mt-2 mb-1">My Lists</div>
<?php
			}

			$userid = $lists_->UserID;
		}
?>
			<a class="d-block font-weight-light" href="/list/{{$lists_->Slug}}">{{$lists_->Name}}</a>
<?php
	}
?>
		</div>		
		<div id="sections" class="w-100 w-md-50 w-lg-50 p-3">
		</div>
		
		<div id="users" class="w-100 w-md-50 w-lg-25 p-3"><!--sidebar-->
		</div>
	</div><!--container-->

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

@if($edit)
			
	<!-- Modal Section -->
	<div class="modal fade" id="modalSection" tabindex="-1" role="dialog" aria-labelledby="lblSection" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="modal-title" id="lblSection">Create New Section</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-small-90">
					<input type="text" class="form-control" id="txtSectionName" placeholder="Day 1, Stage 10, Nice To Haves, etc.">
					<div class="text-danger text-small-90 px-3 py-1 d-none" id="divSectionInvalid">Invalid name for section</div>
				</div>
				<div class="modal-footer text-small-90">
					<button type="button" class="btn btn-sm btn-secondary font-weight-light" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-sm btn-primary font-weight-light" id="btnSectionCreate">Create</button>
					<button type="button" class="btn btn-sm btn-primary font-weight-light" id="btnSectionSave">Save</button>
					<button type="button" class="btn btn-sm btn-primary font-weight-light" id="btnSectionDelete_" data-toggle="collapse" data-target="#collapseSectionDelete">Delete</button>
				</div>
				<div class="collapse text-small-90 p-2 text-right" id="collapseSectionDelete">
					<span class="text-danger">Are you sure you want to delete this section?</span>
					<button type="button" class="btn btn-sm btn-primary font-weight-light" id="btnSectionDelete">Yes</button>
					<button type="button" class="btn btn-sm btn-secondary font-weight-light" data-toggle="collapse" data-target="#collapseSectionDelete">No</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Cols -->
	<div class="modal fade" id="modalCols" tabindex="-1" role="dialog" aria-labelledby="lblCols" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="modal-title" id="lblCols">Add Cols</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-small-90">
					<input id="list-col-search" class="search-input form-control mr-sm-2 px-2 py-1 font-weight-light" type="search" placeholder="Search a col in Europe..." aria-label="Search">
					<div id="list-col-search-wrapper" class="search-input-wrapper ui-front"></div>
					<ul id="selected-cols" class="list-group my-1">
					</ul>
				</div>
				<div class="modal-footer text-small-90">
					<button type="button" class="btn btn-sm btn-secondary font-weight-light" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-sm btn-primary font-weight-light" id="btnColsSave">Save</button>
				</div>
			</div>
		</div>
	</div>
@endif
</main>
@stop

@include('includes.profilemodal')
