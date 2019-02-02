@extends('layouts.master')

@section('title')
CyclingCols - Stats
@stop

@include('includes.functions')

@section('content')

<main role="main" class="bd-content">
    <div class="header px-4 py-3">
        <h4 class="font-weight-light m-0">Stats</h4>
	</div>		
	<div class="container-fluid px-4 pb-3">
		<nav class="navbar navbar-expand-sm navbar-light border-bottom border-top p-0" id="nav-stats">
			<ul class="navbar-nav">
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$stattype->name}}</a>
				<div class="dropdown-menu">
@foreach ($stattypes as $stattype_)
					<a class="dropdown-item font-weight-light" href="/stats/{{$stattype_->url}}/{{$country->url}}">{{$stattype_->name}}</a>
	@if ($stattype_->id == 0)
						<div class="dropdown-divider"></div>
	@endif
@endforeach
				</div>
			  </li>			  
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{$country->name}}</a>
				<div class="dropdown-menu">
@foreach ($countries as $country_)
					<a class="dropdown-item font-weight-light" href="/stats/{{$stattype->url}}/{{$country_->url}}">
						<img src="/images/flags/{{$country_->flag}}.gif" class="flag">{{$country_->name}}
					</a>
	@if ($country_->id == 0)
						<div class="dropdown-divider"></div>
	@endif
@endforeach
				</div>
			  </li>
			</ul>
		</nav>
		<div class="p-0">
		
<?php
	$statid = -1;
	$stattype_current = null;
	$value = null;
	
	foreach ($stats as $stat){
		
		if ($stat->StatID != $statid){
			$statid = $stat->StatID;
			
			foreach($stattypes as $stattype_){
				if ($stattype_->id == $statid){
					$stattype_current = $stattype_;
					break;
				}
			}
			
?>
			<div class="d-flex w-100 justify-content-between align-items-center px-2 pb-2 pt-3">
				<h6 class="m-0 font-weight-light" >{{$stattype_current->name}}</h6>
@if ($stattype->id == 0)
				<a href="/stats/{{$stattype_current->url}}/{{$country->url}}">
					<small>more...</small>
				</a>
@endif
			</div>
			<lu class="list-group">
<?php		
		}
						
		$value = (string)$stat->Value;
		
		if ($stattype_current->number_of_decimals > 0){
			$value_ = "." . substr($value, -1 * $stattype_current->number_of_decimals);
			
			if (strlen($value) > $stattype_current->number_of_decimals){
				$value_ = substr($value, 0, strlen($value) - $stattype_current->number_of_decimals) . $value_;
			} else {
				$value_ = "0" . $value_;
			}

			$value = $value_;
		}
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
							<small>{{$stat->Side}}</small>
							<span class="category category-{{$stat->Category}}">{{$stat->Category}}</span>
						</div>
						new!!
						<div class="p-1 ml-auto" tabindex="0" role="button" data-toggle="modal" data-target="#modalProfile" data-filename="{{$stat->FileName}}" data-col="{{$stat->Col}}" data-side="{{$stat->Side}}"><i class="fas fas-grey  fa-search-plus"></i></div>
						<div class="p-1">
							{{$value}}&nbsp;{{$stattype_current->suffix}}
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