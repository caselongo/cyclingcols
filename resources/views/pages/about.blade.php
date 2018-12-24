@extends('layouts.master')

@section('title')
CyclingCols - About
@stop

@section('content')
<div id="about-canvas" class="canvas col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="header">
        <h1>
            About CyclingCols
        </h1>
	</div>
	<div class="content">
		<div class="about col-xs-12 col-sm-6">
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
				If you have a question about this website or myself, or if you want to advertise or cooperate, please send en email to <a href="mailto:cyclingcols@gmail.com">cyclingcols@gmail.com</a>.
			</p>
			<p>
				By the way, all my mountain rides you can see <a href="/rides">here</a>.
			</p>
		</div>
        <div class="about col-xs-12 col-sm-6">
			<img id="imgMichiel" src="{{ URL::asset('images/Michiel.jpg') }}"/>	
		</div>
		<div class="about_banners">
<?php

	$banners = Banner::where('ColID',0)->orderBy(DB::raw("RAND()"),'ASC')->get();

	foreach($banners as $banner) {
?>	
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<a href="http://{{$banner->RedirectURL}}" target="_blank">
				<img src="/images/banners/{{$banner->BannerFileName}}">
			</a>
		</div>
<?php
	}

?>
		</div>
    </div>
</div>

@stop
