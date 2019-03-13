@extends('layouts.master')

@section('title')
CyclingCols - Help
@stop

@section('content')

<main role="main" class="bd-content">
	<div class="header px-4 py-3">
		<h4 class="font-weight-light">Help</h4>
	</div>
	<div class="font-weight-light px-4 pb-3">
		Some details that can appear in a CyclingCols elevation profile explained:
	</div>
	<div class="container-fluid">
		<div class="card-deck w-100">
			<div class="card mb-3">
				<div class="card-header p-2">
					Scale
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
					<img class="cc-img-help mx-auto" src="/images/help/scale.jpg" style="height: 40px"/>
					<p class="mt-1">Until 2016 all profiles have strictly been generated with 1km sections. Later, I started generating more and more profiles in 500m section scale or sometimes even 200m scale.</p>
					<p>These higher resolution scales unveil a lot of interesting details, especially irregular slopes, and give a better picture of the character of a climb.</p>
				</div>
			</div>	
			<div class="card mb-3">
				<div class="card-header p-2">
					Category
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
					<img class="cc-img-help mx-auto" src="/images/help/category.jpg" style="height: 30px"/>
					<p class="mt-1">All profiles have been categorized on difficulty from category 5 (easy) to category 1 (very difficult).</p>
					<p>These categories are calculated from the profile's profile index and total distance.</p>
					<p>Each category covers approximately 20% of all climbs present on CyclingCols.com. So a category 1 climb is amongst the 20% most difficult climbs.</p>
				</div>
			</div>	
			<div class="w-100 d-none d-sm-block d-md-none"></div>
			<div class="card mb-3">
				<div class="card-header p-2">
					Statistics
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
					<img class="cc-img-help mx-auto" src="/images/help/stats.jpg" style="height: 50px"/>
					<p class="mt-1">Underneath each profile five global statistics are shown: distance, elevation gain, average slope, maximum slope and profile index (more statistics may be added later on).</p>
					<p>A country flag and a rank is shown below if the statistic is amongst the highest values of its country or even of Europe.</p>
				</div>
			</div>	
			<div class="w-100 d-none d-md-block d-lg-none"></div>
			<div class="card mb-3">
				<div class="card-header p-2">
					Steepest intervals
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
					<img class="cc-img-help mx-auto" src="/images/help/steepest.jpg" style="height: 50px"/>
					<p class="mt-1">Three banners underneath the profile baseline locate the steepest 5km, 1km and 200m sections within the entire climb.</p>
				</div>
			</div>
			<div class="w-100 d-none d-lg-block"></div>	
			<div class="w-100 d-none d-sm-block d-md-none"></div>
			<div class="card mb-3">
				<div class="card-header p-2">
					Short steep sections
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
					<img class="cc-img-help mx-auto" src="/images/help/steep.jpg" style="height: 65px"/>
					<p class="mt-1">Sections of minimum 200m length that are significantly steeper than the average slope of their covering section are indicated in red.</p>
				</div>
			</div>	
			<div class="card mb-3">
				<div class="card-header p-2">
					Tunnels
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
					<img class="cc-img-help mx-auto" src="/images/help/tunnel.jpg" style="height: 65px"/>
					<p class="mt-1">Tunnels along the route are indicated. Consecutive short tunnels can be denoted by a single tunnel icon.</p>
					<p class="text-small-75">Not all profiles have yet been generated with tunnel icons.</p>
				</div>
			</div>	
			<div class="w-100 d-none d-md-block d-lg-none"></div>
			<div class="w-100 d-none d-sm-block d-md-none"></div>
			<div class="card mb-3">
				<div class="card-header p-2">
					Hairpin curves
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
					<img class="cc-img-help mx-auto" src="/images/help/hairpins.jpg" style="height: 65px"/>
					<p class="mt-1">A black solid vertical line just underneath the profile stands for a hairpin curve to the right, a dashed one depicts a leftward hairpin curve.</p>
					<p class="text-small-75">Not all profiles have yet been generated with hairpin curve indicators.</p>
				</div>
			</div>	
			<div class="card mb-3">
				<div class="card-header p-2">
					Unpaved sections
				</div>
				<div class="card-body p-2 font-weight-light text-small-90">
					<img class="cc-img-help mx-auto" src="/images/help/unpaved.jpg" style="height: 65px"/>
					<p class="mt-1">Unpaved sections of the road are indicated by a gray line along the profile.</p>
				</div>
			</div>
			<div class="w-100 d-none d-lg-block"></div>	
			<div class="w-100 d-none d-sm-block d-md-none"></div>
			<div class="card card-invisible"></div>
			<div class="w-100 d-none d-md-block d-lg-none"></div>
			<div class="card card-invisible"></div>
			<div class="w-100 d-none d-sm-block d-md-none"></div>
			<div class="card card-invisible"></div>
		</div>
	</div>
</main>
@stop
