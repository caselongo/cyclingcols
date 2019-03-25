@if (count($users) == 0)
	<span class="text-small-75">No athlete claimed this col.</span>
@else
	@foreach ($users as $user)
		<div class="text-small-90 align-items-baseline d-flex">
		@auth
		@endauth
			<div class="text-truncate">
				<a href="/athlete/{{$user->id}}">{{$user->name}}</a>
			</div>
			<div class="text-primary text-small-75 text-right" style="flex: 0 0 15px;">
			@if ($user->id == Auth::user()->id)
				<i class="fas fa-user" title="That's you!" data-toggle="tooltip"></i> 
			@elseif ($user->followedByMe())
				<i class="fas fa-check" title="Following" data-toggle="tooltip"></i> 
			@endif
			</div>
			<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
		@if ($user->pivot->ClimbedAt != null)
			{{getHumanDate($user->pivot->ClimbedAt)}}
		@else 
			date unknown
		@endif
			</div>
		</div>
	@endforeach
	<div class="text-small-75 pt-1">Claimed by {{$count}} athlete{{ $count > 1 ? "s" : "" }}.</div>
@endif