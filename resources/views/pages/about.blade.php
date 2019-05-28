@extends('layouts.master')

@section('title')
CyclingCols - About
@stop

@section('content')
<main role="main" class="bd-content">
	<div class="header px-4 py-3">
		<h4 class="font-weight-light">About CyclingCols</h4>
	</div>
	<div class="container-fluid font-weight-light">
		<div class="row px-2">
			<div class="col-xs-12 col-md-6 col-lg-8">
				<p>This website is a collection of unique and accurate information about cols in Europe, maintained by myself, Michiel van Lonkhuyzen (1977). </p>
				<p>
					Since the late 90s I have been cycling through the mountaineous regions of Europe, measuring and recording the altitudes of roads. 
					While cycling uphill and downhill I make pictures to capture the atmosphere of every col. 
				</p>
				<p>
					CyclingCols was first released at February 26th 2001, just built in Notepad and Paint. 
					Because the number of cols on the site was growing fast, I decided to rebuild the site in 2004 using ASP pages and SQL Server.
					This version existed for more than 10 years until the current MySQL/PHP driven version was unveiled in the beginning of 2015.
				</p>
				<p>
					Four years later, in 2019, CyclingCols got another re-design and technical update, introducing user interactivity.
				</p>
				<p>
					If you have a question about this website or myself, or if you want to advertise or cooperate, please send en email to <a href="mailto:cyclingcols@gmail.com">cyclingcols@gmail.com</a>.
				</p>
				<p>
					By the way, you can see all my mountain rides <a href="/rides">here</a>.
				</p>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-4 about-image">
				<img src="/images/Michiel.jpg"/>	
			</div>
		</div>
		<div class="row border-top my-3">
<?php

	$banners = \App\Banner::where('ColID',0)->where('Active',1)->orderBy(DB::raw("RAND()"),'ASC')->get();

	foreach($banners as $banner) {
?>	
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 p-3 text-center">
				<a href="http://{{$banner->RedirectURL}}" target="_blank">
					<img class="ad rounded" src="/images/banners/{{$banner->BannerFileName}}">
				</a>
			</div>
<?php
	}

?>
		</div>
	</div>
</main>

@stop
