@foreach ($banners as $banner)		
	<a class="d-block mb-2" href="http://{{$banner->RedirectURL}}" target="_blank">
		<img class="ad rounded" src="/images/banners/{{$banner->BannerFileName}}">
	</a>
@endforeach
@if ($contact)
	<a class="text-small-90 font-weight-light d-block" href="mailto:cyclingcols@gmail.com">Your ad here?</a>
@endif