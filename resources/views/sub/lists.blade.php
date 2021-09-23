@if (count($lists) == 0)
	<span class="text-small-75">
		No lists created yet. <p/>
		Examples of lists you may like to create:<br/>
		<span class="font-italic">Cycling Holiday Alps 2020</span>, 
		<span class="font-italic">My Favorite Cols</span>,
		 or just your
		<span class="font-italic">Cols Bucketlist</span>.
	</span>
@else
	@foreach ($lists as $list)
	<div class="align-items-end d-flex justify-content-between align-items-baseline">
		<div class="text-truncate">
			<a href="/list/{{$list->Slug}}">{{$list->Name}}</a>
		</div>	

		<div class="ml-auto text-small-90 text-right" style="flex: 0 0 30px;">
			{{$list->ClimbedCount}}
		</div>
		<div class="m-1" style="flex: 0 0 80px;">
	@if ($list->ClimbedCount > 0)
		<div class="bar bar-year
		@if ($list->ClimbedCount > 0)
			bar-rounded-left
		@else
			bar-rounded
		@endif		
		" style="width: {{($list->ClimbedCount*1.0/$list->ColCount) * 70}}px;"></div>
	@endif
	@if ($list->ClimbedCount < $list->ColCount)
		<div class="bar bar-total
		@if ($list->ClimbedCount > 0)
			bar-rounded-right
		@else
			bar-rounded
		@endif					
		" style="width: {{(1 - ($list->ClimbedCount*1.0/$list->ColCount)) * 70}}px;"></div>
	@endif
		</div>
		<div class="text-small-75 text-right" style="flex: 0 0 30px;">
			of {{$list->ColCount}}
		</div>
@if ($isOwner)
		<i class="fas fas-grey fa-edit ml-2" title="edit" data-toggle="modal" data-target="#modalNewList" data-mode="edit" data-slug="{{$list->Slug}}" data-name="{{$list->Name}}"></i>
@endif
	</div>
	@endforeach
@endif