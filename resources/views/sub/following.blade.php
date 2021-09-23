@if ($user)
	<div class="text-small-75 text-primary ml-2 follow follow-yes" title="click to stop following" data-toggle="tooltip">
		<i class="fas fa-check"></i> 
		<span>following</span>
	</div>
@else
	<div class="text-small-75 text-secondary ml-2 follow follow-no" title="click to start following" data-toggle="tooltip">
		<i class="fas fa-check"></i>
		<span>not following</span>
	</div>
@endif