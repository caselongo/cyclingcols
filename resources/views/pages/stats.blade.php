@extends('layouts.master')

@section('title')
CyclingCols - New
@stop

@section('content')
<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light">Stats</h4>
	</div>	
	<div class="container-fluid">
		<nav class="navbar navbar-expand-sm navbar-light border-bottom border-top p-0" id="nav-stats">
			<ul class="navbar-nav">
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$stattype->StatType}}</a>
				<div class="dropdown-menu">
@foreach ($stattypes as $stattype_)
					<a class="dropdown-item font-weight-light" href="/stats/{{$stattype_->URL}}/{{$country->URL}}">
						<i class="fas fas-grey fa-{{$stattype_->Icon}} no-pointer"></i>
						<span>{{$stattype_->StatType}}</span>
					</a>
	@if ($stattype_->StatTypeID == 0)
						<div class="dropdown-divider"></div>
	@endif
@endforeach
				</div>
			  </li>			  
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$country->Country}}</a>
				<div class="dropdown-menu">
@foreach ($countries as $country_)
					<a class="dropdown-item font-weight-light" href="/stats/{{$stattype->URL}}/{{$country_->URL}}">
						<img src="/images/flags/{{$country_->Flag}}.gif" class="flag mr-1">{{$country_->Country}}
					</a>
	@if ($country_->CountryID == 0)
						<div class="dropdown-divider"></div>
	@endif
@endforeach
				</div>
			  </li>
			</ul>
		</nav>
		<div class="w-100 w-md-75 mb-3">
<?php
	$stattypeid = -1;
	$stattype_current = null;
	$count = 0;
	
	foreach ($stats as $stat){
		
		if ($stat->StatTypeID != $stattypeid){
			$stattypeid = $stat->StatTypeID;
			
			if ($count > 0){
?>
			</div><!--card-deck-->
<?php				
			}
			
			$count = 0;
			
			foreach($stattypes as $stattype_){
				if ($stattype_->StatTypeID == $stattypeid){
					$stattype_current = $stattype_;
					break;
				}
			}
			
?>
			<div class="d-flex w-100 align-items-center p-2">
				<i class="fas fas-grey fa-{{$stattype_current->Icon}} no-pointer"></i>
@if ($stattype->StatTypeID == 0)
				<a href="/stats/{{$stattype_current->URL}}/{{$country->URL}}">
					<h6 class="m-0 pl-1 font-weight-light" >{{$stattype_current->StatType}}</h6>
				</a>
@else 
					<h6 class="m-0 pl-1 font-weight-light" >{{$stattype_current->StatType}}</h6>	
@endif
			</div>
			<div class="card-deck w-100">
<?php
		}
		
		if ($count == 5){
?>
			</div><!--card-deck-->
			<div class="card-deck w-100 w-md-75 m-md-auto">
<?php				
		}
		
		$value = formatStat($stattype_current->StatTypeID, $stat->Value);
		
		$cardClass = "my-2";
		$cardBodyClass = "";
		$cardProfileClass = "border-top";
		
		if ($count >= 5) {		
			$cardClass = "my-0 text-small-90";
			$cardBodyClass = "d-flex align-items-center";
			$cardProfileClass = "flex-grow-1";
		}
?>	
			<div class="card {{$cardClass}}">
@if ($count < 5)
				<div class="card-header p-0 d-flex justify-content-around">
					<!--<span>{{$stat->Rank}}.</span>-->
					<span class="m-auto">{{$value}}</span>
				</div>
	@if ($stat->CoverPhotoPosition != null)
				<div class="card-img-top card-img-background" onclick='goToCol("{{$stat->ColIDString}}")' style='background-position: 50% {{$stat->CoverPhotoPosition}}%; background-image: url("/images/covers/small/{{$stat->ColIDString}}.jpg")'>
	@else
				<div class="card-img-top card-img-background" onclick='goToCol("{{$stat->ColIDString}}")' style='background-position: 50% 28%; background-image: url("/images/covers/small/_dummy.jpg")'>
	@endif
	@if ($stat->IsNew)
					<div class="card-img-new">New</div>
	@endif
					<div class="card-go-to"><small><i class="fas fa-search"></i></small></div>
				</div><!--card-img-top-->
@endif
				<div class="card-body p-0 {{$cardBodyClass}}">
					<h6 class="card-title p-2 m-0 font-weight-light">
						<img src="/images/flags/{{$stat->Country1}}.gif" title="{{$stat->Country1}}" class="flag">
@if ($stat->Country2)
						<img src="/images/flags/{{$stat->Country2}}.gif" title="{{$stat->Country2}}" class="flag flag2">
@endif
				{{$stat->Col}}
						<span class="badge badge-altitude font-weight-light">{{$stat->Height}}m</span>
					</h6>
					<div class="card-profile px-2 py-1 {{$cardProfileClass}} text-small-75 d-flex flex-row justify-content-between align-items-baseline">
						<div>
							<span class="category category-{{$stat->Category}}">{{$stat->Category}}</span>
@if ($stat->Side != null)
							<span>{{$stat->Side}}</span>
							<img class="direction" src="/images/{{$stat->Side}}.png">
@endif
							<small>{{$stat->Start}}</small>
						</div>	
						<a tabindex="0" role="button" data-toggle="modal" data-target="#modalProfile" data-profile="{{$stat->FileName}}" data-col="{{$stat->Col}}"><i class="fas fas-grey  fa-search-plus"></i></a>		
					</div>
@if ($count >= 5)
					<div class="p-1 font-weight-light">
						{{$value}}
					</div>
@endif
				</div><!--card-body-->
			</div><!--card-->
<?php	
		$count++;
?>
		<!-- card wrapping, see https://www.codeply.com/go/nIB6oSbv6q -->
		@if ($count <= 5)
			@if ($count == 1)
				<div class="w-100 d-none d-sm-block d-lg-none"><!-- wrap first 1 on sm--></div>
			@endif
			@if ($count > 0 && ($count - 1) % 2 == 0)
				<div class="w-100 d-none d-sm-block d-lg-none"><!-- wrap next 2 on sm--></div>
			@endif
			@if ($count == 2)
				<div class="w-100 d-none d-lg-block"><!-- wrap first 2 on lg or larger--></div>
			@endif
			@if ($count > 0 && ($count - 2) % 3 == 0)
				<div class="w-100 d-none d-lg-block"><!-- wrap next 3 on lg or larger--></div>
			@endif
		@else
				<div class="w-100 d-block"><!-- wrap first 1 on sm--></div>
		@endif
<?php
	}
?>
			</div><!--card-deck-->
		</div><!--row-->
	</div><!--container-->
</main>
@stop

@include('includes.profilemodal')
