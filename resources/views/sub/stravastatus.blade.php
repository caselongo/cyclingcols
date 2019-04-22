<div>Initialize or update your cols list with Strava</div>
<div class="p-1">
@if ($strava_processing)
	<a class="btn btn-primary disabled">
@else
	<a class="btn btn-primary" href="/strava/connect">
@endif
		Connect with Strava
	</a>
</div>
<div id="stravastatus">
	<div class="text-small-75">
@if (!$strava_last_updated_at)
	(not done yet)
@else
	(last time connected: {{Carbon\Carbon::parse($strava_last_updated_at)->format('d M Y H:i:s')}})
@endif
	</div>
@if ($strava_processing)
	<div class="progress w-50 m-auto">
		<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
	</div>
	<div>
		processing... 
		<br/>
		<span class="text-small-75">(this can take a few minutes)</span>
		<br/>
		<span class="text-small-75">({{$count}} new col{{ $count != 1 ? "s" : ""}} found so far)</span>
@elseif ($count > 0)
		<a href="/strava/cols">
			{{$count}} new col{{ $count != 1 ? "s" : ""}} found
		</a>
@elseif ($count == 0 && $processed)
		0 new cols found
@endif
	</div>
</div>