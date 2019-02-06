@extends('layouts.master')

@section('title')
CyclingCols - Stats
@stop

@section('content')

<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light m-0">Stats</h4>
	</div>		
	<div class="container-fluid px-4 pb-3">
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
		<div class="p-0">
		
<?php
	$stattypeid = -1;
	$stattype_current = null;
	$value = null;
	
	foreach ($stats as $stat){
		
		if ($stat->StatTypeID != $stattypeid){
			$stattypeid = $stat->StatTypeID;
			
			foreach($stattypes as $stattype_){
				if ($stattype_->StatTypeID == $stattypeid){
					$stattype_current = $stattype_;
					break;
				}
			}
			
?>
			<div class="d-flex w-100 justify-content-between align-items-center px-2 pb-2 pt-3">
				<i class="fas fas-grey fa-{{$stattype_current->icon}} no-pointer"></i>
				<h6 class="m-0 pl-1 font-weight-light" >{{$stattype_current->StatType}}</h6>
@if ($stattype->StatTypeID == 0)
				<a class="ml-auto" href="/stats/{{$stattype_current->URL}}/{{$country->URL}}">
					<small>more...</small>
				</a>
@endif
			</div>
			<lu class="list-group">
<?php		
		}
						
		$value = formatStat($stattype_current->StatTypeID, $stat->Value);
?>	
				<li class="list-group-item list-group-item-action no-pointer rounded-0">
					<div class="d-flex align-items-center">
						<div class="p-1" style="flex-basis: 30px;">{{$stat->Rank}}</div>
						<div class="p-1">
							<img src="/images/flags/{{$stat->Country1}}.gif" title="{{$stat->Country1}}" class="flag">
@if ($stat->Country2)
							<img src="/images/flags/{{$stat->Country2}}.gif" title="{{$stat->Country2}}" class="flag flag2">
@endif
							<a href="/col/{{$stat->ColIDString}}"> {{$stat->Col}}</a>
@if ($stat->Side)
							<span class="text-small-75"><img class="direction mr-1" src="/images/{{$stat->Side}}.png"/>{{$stat->Side}}</span>
@endif
							<span class="category category-{{$stat->Category}}">{{$stat->Category}}</span>
						</div>
						new!!
						<div class="p-1 ml-auto" tabindex="0" role="button" data-toggle="modal" data-target="#modalProfile" data-profile="{{$stat}}"><i class="fas fas-grey  fa-search-plus"></i></div>
						<div class="p-1">
							{{$value}}
						</div>
					</div>		
				</li>
<?php
	}
?>	
			</lu>
		</div>
	</div>
</main>

@stop

@include('includes.profilemodal')