<?php
	$countTotal = count($lists);
	$count = 0;
?>
<div class="dropdown d-inline">
  <button class="btn btn-sm btn-light font-weight-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Lists
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
	@foreach ($lists_all as $list)
    	<button type="button" class="dropdown-item font-weight-light pointer
		@if ($list->hasCol)
			listRemove" title="Remove this col from '{{$list->Name}}' list" data-list="{{$list->Slug}}">
			<i class="fas fa-check fas-yes" style="pointer-events: none"></i>
		@else
			listAdd" title="Add this col to '{{$list->Name}}' list" data-list="{{$list->Slug}}">
			<i class="fas fa-check fas-light" style="pointer-events: none"></i>
		@endif
			{{$list->Name}}
		</button>
	@endforeach
  </div>
</div>
@if ($countTotal > 0)
	@foreach ($lists as $list)
		@if ($count == 2 && $countTotal > 3)
			&nbsp;and {{$countTotal - $count}} other lists
		@elseif ($count <= 2)
			<a role="button" class="btn btn-sm btn-secondary font-weight-light" href="/list/{{$list->Slug}}" 
				title="This col belongs to your '{{$list->Name}}' list"
			>{{$list->Name}}</a>
		@endif
<?php
	$count++;
?>
	@endforeach
@endif
