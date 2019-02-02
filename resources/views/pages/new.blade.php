@extends('layouts.master')

@section('title')
CyclingCols - New
@stop

@section('content')
<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light m-0">New and updated cols and profiles</h4>
	</div>	
	<div class="container-fluid px-4 pb-3">
		<div class="p-0">
		
<?php
	use Carbon\Carbon;

	$datesort = -1;
	$is_new = -1;
	$count = 0;
	
	foreach ($newitems as $newitem){
		
		if ($newitem->DateSort != $datesort){
			$datesort = $newitem->DateSort;
			$is_new = -1;
			
			$diff_for_humans = Carbon::createFromFormat('Ymd',$newitem->DateSort)->diffForHumans();
			$date = Carbon::createFromFormat('Ymd',$newitem->DateSort)->format('j M Y');
			
?>
			<div class="d-flex w-100 align-items-center px-2 py-1 border-bottom border-top p-0">
				<h6 class="m-0 font-weight-light" >{{$date}}</h6>&nbsp;
				<small>({{$diff_for_humans}})</small>
			</div>
<?php		
		}
		
		if ($newitem->IsNew != $is_new){
			$is_new = $newitem->IsNew;
?>
@if ($count > 0)
		</lu>
@endif

@if ($newitem->IsNew)
			<div class="rounded-top bg-primary cc-new-label mt-2">new</div>
@else	
			<div class="rounded-top bg-secondary cc-new-label mt-2">updated</div>
@endif
			<lu class="list-group">
<?php
				

		}
?>	
				<li class="list-group-item list-group-item-action no-pointer rounded-0">
					<div class="d-flex align-items-center">
						<div class="p-1">
							<img src="/images/flags/{{$newitem->Country1}}.gif" title="{{$newitem->Country1}}" class="flag">
@if ($newitem->Country2)
							<img src="/images/flags/{{$newitem->Country2}}.gif" title="{{$newitem->Country2}}" class="flag flag2">
@endif
							<a href="/col/{{$newitem->ColIDString}}"> {{$newitem->Col}}</a>
							<small>{{$newitem->Side}}</small>
							<span class="category category-{{$newitem->Category}}">{{$newitem->Category}}</span>
						</div>
						<div class="p-1 ml-auto" tabindex="0" role="button" data-toggle="modal" data-target="#modalProfile" data-filename="{{$newitem->FileName}}" data-col="{{$newitem->Col}}" data-side="{{$newitem->Side}}"><i class="fas fas-grey  fa-search-plus"></i></div>
					</div>		
				</li>
<?php

		$count++;
	}
?>	
			</lu>
		</div>
	</div><!--container-->
</main>
@stop

@include('includes.profilemodal')
