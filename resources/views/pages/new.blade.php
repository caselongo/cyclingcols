@extends('layouts.master')

@section('title')
CyclingCols - New
@stop

@section('content')
<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light">New and updated cols and profiles</h4>
	</div>	
	<div class="container-fluid mx-2">
		<div class="card-deck">

<?php	
	$datesort = 0;
	$colidstring = "";
	$count = 0;

	foreach($newitems as $newitem) {
		if ($newitem->DateSort != $datesort || $newitem->ColIDString != $colidstring) {
			$datesort = $newitem->DateSort;
			$colidstring = $newitem->ColIDString;
		}
?>	
			<div class="card mb-4">
				<div class="card-img-top card-img-background" onclick='goToCol("{{$newitem->ColIDString}}")' style='background-position: 50% 47%; background-image: url("images/covers/small/{{$newitem->ColIDString}}.jpg")'>
@if ($newitem->IsNew)
					<div class="card-img-new">New</div>
@endif
					<div class="card-go-to"><small><i class="fas fa-search"></i></small></div>
				</div><!--card-img-top-->
				<div class="card-body">
					<h6 class="card-title font-weight-light">
						<img src="/images/flags/{{$newitem->Country1}}.gif" title="{{$newitem->Country1}}" class="flag">
@if ($newitem->Country2)
						<img src="/images/flags/{{$newitem->Country2}}.gif" title="{{$newitem->Country2}}" class="flag flag2">
@endif
				{{$newitem->Col}}
						<span class="searchitemheight">{{$newitem->Height}}m</span>
					</h6>
@foreach ($newitem->Profiles as $profile)
					<div class="card-profile d-flex flex-row justify-content-between align-items-baseline">
						<div>
							<span class="category category-{{$profile->Category}}">{{$profile->Category}}</span>
							<span>{{$profile->Side}}</span>
							<small>{{$profile->Start}}</small>
@if (!$newitem->IsNew && $profile->IsNew)
							<span class="badge badge-new">New</span>
@endif
						</div>	
						<!--<a tabindex="0" role="button" data-toggle="modal" data-target="#modalProfile" data-filename="{{$profile->FileName}}" data-col="{{$newitem->Col}}" data-side="{{$profile->Side}}"><i class="fas fas-grey  fa-search-plus"></i></a>-->	
						<a tabindex="0" role="button" data-toggle="modal" data-target="#modalProfile" data-filename="{{$profile->FileName}}" data-col="{{$newitem->Col}}" data-side="{{$profile->Side}}"><i class="fas fas-grey  fa-search-plus"></i></a>		
					</div>
@endforeach
				</div><!--card-body-->
				<div class="card-footer">
					<small class="text-muted">
@if ($newitem->IsNew) 
			Added
@else
			Updated
@endif
			{{$newitem->DiffForHumans}}</small>
				</div><!--card-footer-->
			</div><!--card-->
<?php	
		$count++;

		if ($count > 0){
?>
			<span class="dummy"></span><!--needed responsive card-deck-->
<?php
		}
	}
?>
			<!--add some invisible cards to be sure last cards are of equal size-->
			<div class="card card-invisible"></div><span class="dummy"></span>
			<div class="card card-invisible"></div><span class="dummy"></span>
			<div class="card card-invisible"></div><span class="dummy"></span>
			<div class="card card-invisible"></div><span class="dummy"></span>
		</div><!--row-->
	</div><!--container-->
</main>
<div class="modal fade" id="modalProfile" tabindex="-1" role="dialog" aria-labelledby="modalProfileLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
		<div class="modal-header-sub">
			<span class="category category-4">4</span>
			<h6 class="modal-title font-weight-light" id="modalProfileLabel"></h6>
			<span class="modal-title-secondary"></span>
		</div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img class="modal-body-profile" src=""></img>
      </div>
      <!--<div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Send message</button>
      </div>-->
    </div>
  </div>
</div>
@stop
