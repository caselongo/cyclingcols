@if (count($users) == 0)
	<span class="text-small-75 px-2">No registered user climbed this col.</span>
@else
	@foreach ($users as $user)
		<div class="align-items-end d-flex">
			<div class="text-truncate">
				<a href="user/{{$user->id}}">{{$user->name}}</a>
			</div>
			<div class="ml-auto text-small-75 text-right" style="flex: 0 0 65px;">{{Carbon\Carbon::parse($user->pivot->CreatedAt)->format('d M Y')}}</div>
		</div>
	@endforeach
@endif