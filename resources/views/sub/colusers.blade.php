@if (count($users) == 0)
	<span class="text-small-75">No registree climbed this col.</span>
@else
	@foreach ($users as $user)
		<div class="text-small-90 align-items-end d-flex">
			<div class="text-truncate">
				<a href="user/{{$user->id}}">{{$user->name}}</a>
			</div>
			<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
		@if ($user->pivot->ClimbedAt != null)
			{{Carbon\Carbon::parse($user->pivot->ClimbedAt)->format('d M Y')}}
		@else 
			date unknown
		@endif
			</div>
		</div>
	@endforeach
	<div class="text-small-75 pt-1">Climbed by {{$count}} registree{{ $count > 1 ? "s" : "" }}.</div>
@endif