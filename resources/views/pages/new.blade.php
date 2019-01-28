@extends('layouts.master')

@section('title')
CyclingCols - New
@stop

@section('content')
<main role="main" class="bd-content p-3">
    <div class="header">
        <h4>New cols and profiles</h4>
	</div>
	

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
@if ($count % 4 == 0)
	<div class="card-deck py-3">
@endif
	  <div class="card">
		<div class="card-img-top card-img-background" style='background-position: 50% 47%; background-image: url("images/covers/small/{{$newitem->ColIDString}}.jpg")'>
@if ($newitem->IsNew)
			<div class="card-img-new">New</div>
@endif
			<div class="card-go-to"><small><a href="col/{{$newitem->ColIDString}}" title="go to col page"><i class="fas fa-search"></i></a></small></div>
		</div>
		<div class="card-body">
			<h6 class="card-title">
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
					<span class="category category-{{$profile->Category}}">{{$profile->Category}}</span>{{$profile->Side}}
					<small>{{$profile->Start}}</small>
@if (!$newitem->IsNew && $profile->IsNew)
					<span class="badge badge-new">New</span>
@endif
				</div>
				<i class="fas fas-grey  fa-search-plus"></i>
			</div>
@endforeach
		</div>
		<div class="card-footer">
		  <small class="text-muted">
@if ($newitem->IsNew) 
			Added
@else
			Updated
@endif
			{{$newitem->DiffForHumans}}</small>
		</div>
	  </div>
<?php
		
		
		$count++;

		if ($count % 4 == 0){
?>
	</div>
<?php
		}
	}
?>
</main>
@stop
