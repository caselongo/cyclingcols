@extends('layouts.master')

@section('title')
CyclingCols - New
@stop

@section('content')
<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light">New & Updated Cols & Climbs</h4>
	</div>	
	<div class="container-fluid">
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
		
		$coverPhoto = "_Dummy";
		$coverPhotoPosition = 50;
		if ($newitem->CoverPhotoPosition){
			$coverPhoto = $newitem->ColIDString;
			$coverPhotoPosition = $newitem->CoverPhotoPosition;
		}
		
		//$diff_for_humans = Carbon::createFromFormat('Ymd',$newitem->DateSort)->diffForHumans();
		//$date = Carbon::createFromFormat('Ymd',$newitem->DateSort)->format('j M Y');
?>	
			<div class="card mb-4">
				<div class="card-img-top card-img-background" onclick='goToCol("{{$newitem->ColIDString}}")' style='background-position: 50% {{$coverPhotoPosition}}%; background-image: url("/images/covers/small/{{$coverPhoto}}.jpg")'>
@if ($newitem->IsNew)
					<div class="card-img-new">New</div>
@endif
					<!--<div class="card-go-to"><small><i class="fas fa-search"></i></small></div>-->
				</div><!--card-img-top-->
				<div class="card-body p-0">
					<h6 class="card-title p-2 m-0 font-weight-light">
						<img src="/images/flags/{{$newitem->Country1}}.gif" title="{{$newitem->Country1}}" data-toggle="tooltip" class="flag">
@if ($newitem->Country2)
						<img src="/images/flags/{{$newitem->Country2}}.gif" title="{{$newitem->Country2}}" data-toggle="tooltip" class="flag flag2">
@endif
						<a href="/col/{{$newitem->ColIDString}}">{{$newitem->Col}}</a>
						<span class="badge badge-altitude font-weight-light text-small-70">{{$newitem->Height}}m</span>
					</h6>
@foreach ($newitem->Profiles as $profile)
					<div class="card-profile px-2 py-1 border-top text-small-75 d-flex flex-row justify-content-between align-items-baseline">
						<div>
							<span class="category category-{{$profile->Category}}">{{$profile->Category}}</span>
@if ($profile->Side != null)
							<span>{{$profile->Side}}</span>
							<img class="direction" src="/images/{{$profile->Side}}.png">
@endif
							<small>{{$profile->Start}}</small>
@if (!$newitem->IsNew && $profile->IsNew)
							<span class="badge badge-new">New</span>
@endif
						</div>	
						<a tabindex="0" role="button" data-toggle="modal" data-target="#modalProfile" data-profile="{{$profile->FileName}}" data-col="{{$newitem->Col}}"><i class="fas fas-grey  fa-search-plus"></i></a>		
					</div>
@endforeach
				</div><!--card-body-->
				<div class="card-footer text-muted">
					<span class="text-small-75">
@if ($newitem->IsNew) 
			Added
@else
			Updated
@endif
			{{$newitem->DateString}}</span>
				</div><!--card-footer-->
			</div><!--card-->
<?php	
		$count++;
?>
		<!-- card wrapping, see https://www.codeply.com/go/nIB6oSbv6q -->
		@if ($count > 0 && $count % 2 == 0)
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 2 on sm--></div>
		@endif
		@if ($count > 0 && $count % 3 == 0)
			<div class="w-100 d-none d-md-block d-lg-none"><!-- wrap every 3 on md--></div>
		@endif
		@if ($count > 0 && $count % 4 == 0)
			<div class="w-100 d-none d-lg-block"><!-- wrap every 4 on lg or larger--></div>
		@endif
<?php
	}
	
	for ($i = 0; $i < 4; $i++){
?>
		<!--add some invisible cards to be sure last cards are of equal size-->
		<div class="card card-invisible"></div>
			
<?php
		$count++;
?>
		@if ($count > 0 && $count % 2 == 0)
			<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 2 on sm--></div>
		@endif
		@if ($count > 0 && $count % 3 == 0)
			<div class="w-100 d-none d-md-block d-lg-none"><!-- wrap every 3 on md--></div>
		@endif
		@if ($count > 0 && $count % 4 == 0)
			<div class="w-100 d-none d-lg-block"><!-- wrap every 4 on lg or larger--></div>
		@endif
<?php
	}
?>
		</div><!--row-->
	</div><!--container-->
</main>
@stop

@include('includes.profilemodal')
