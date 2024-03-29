<footer class="footer mt-auto bg-dark text-white text-small-75">
	<div class="d-flex flex-row flex-wrap justify-content-between">
		<div class="px-3 py-2">
			Developed&nbsp;by&nbsp;Michiel&nbsp;van&nbsp;Lonkhuyzen&nbsp;| Contact:&nbsp;<a href="mailto:info@cyclingcols.com">info@cyclingcols.com</a>&nbsp;|
				©&nbsp;2001&nbsp;-&nbsp;{{date("Y")}},&nbsp;<a href="http://www.cyclingcols.com" target="_blank">cyclingcols.com</a>,&nbsp;all&nbsp;rights&nbsp;reserved
		</div>
		<div class="px-3">
			<img src="/images/strava/api_logo_pwrdBy_strava_horiz_light.png" style="height: 30px;"/>
		</div>
		<div class="px-3 py-2">
			<span class="badge badge-primary font-weight-normal">{{App\Col::count()}}</span>&nbsp;cols&nbsp;
			<span class="badge badge-primary font-weight-normal">{{App\Profile::count()}}</span>&nbsp;climbs
		</div>
	</div>
</footer>

