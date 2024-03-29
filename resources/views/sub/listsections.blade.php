@if (count($sections) == 0)
		<span class="text-small-90 font-weight-light">This list is still empty. Fill this list by adding sections and cols.</span>
@endif

@foreach($sections as $sections_)
	@if (!is_null($sections_->Name))
		<div class="card mb-3">
			<div class="card-header p-2 align-items-end d-flex justify-content-between"
		@if ($edit)
					ondrop="dropColInSection(event)" ondragover="allowDrop(event)" ondragenter="dragEnterCol(event)" ondragleave="dragLeaveCol(event)"
					id="s{{$sections_->ID}}"
		@endif
			>
				{{$sections_->Name}}
		@if ($edit == 1)
				<button type="button" class="btn btn-sm btn-primary font-weight-light text-small-90 px-2 py-1 ml-auto mr-1" data-toggle="modal" data-target="#modalCols" data-id="{{$sections_->ID}}">Add Col</button>
				<i class="fas fas-grey fa-edit" title="edit" data-toggle="modal" data-target="#modalSection" data-mode="edit" data-id="{{$sections_->ID}}" data-name="{{$sections_->Name}}"></i>
		@endif				
			</div>
	@else
		<div class="card mb-3 card-dummy">
	@endif
			<div class="card-body px-2 py-1 font-weight">
	@foreach($sections_->cols()->orderBy('Sort')->get() as $col)
<?php
		$climbed = null;
		$col_ = $col->col;
		if (is_null($col_)){
			$col->ColID = 0;
		}
?>
				<div class="font-weight-light text-small-90 d-flex justify-content-between mt-1 align-items-baseline"
		@if ($edit)
					style="cursor: move"
					ondrop="dropCol(event)" ondragover="allowDrop(event)" ondragenter="dragEnterCol(event)" ondragleave="dragLeaveCol(event)"
					draggable="true" ondragstart="dragCol(event)" id="c{{$col->ID}}"	
		@endif
				>
		@if ($col->Category)
					<div class="list-category mr-1" title="Category" data-toggle="tooltip">{{$col->Category}}</div>
		@endif
		@if ($col->ColID == 0 || $col->ShowColName)
					<span class="mr-1">{{$col->Col}}</span>
		@endif
		@if ($col->ColID > 0)
<?php
		if (Auth::user()){
			$climbed = $col_->climbedByMe();
		}
		
		$profile = null;
		if ($col->ProfileID > 0){
			$profile = \App\Profile::where('ProfileID','=',$col->ProfileID)->first();
			
		}
?>
			@if (!is_null($profile) && (is_null($col->Category) || $col->Category == ""))
					<span class="category category-{{$profile->Category}} mr-1">{{$profile->Category}}</span>
			@endif
			@if ($col->PPartial)
					(~ <a href="/col/{{$col_->ColIDString}}" title="Only partially climbed" data-toggle="tooltip">{{$col_->Col}}</a>)
			@else
				@if ($col->ShowColName)
					<span class="mr-1">=</span>
				@endif
					<a href="/col/{{$col_->ColIDString}}" draggable="false">{{$col_->Col}}</a>
			@endif
			
			@if (!is_null($profile))
				@if (!is_null($profile->Side))
					<div>
						<span class="text-small-75 ml-1">{{$profile->Side}}</span>
						<img class="direction" src="/images/{{$profile->Side}}.png">
					</div>
				@endif
					<div class="ml-2">
						<a tabindex="0" role="button" data-toggle="modal" data-target="#modalProfile" data-profile="{{$profile->FileName}}" data-col="{{$col_->Col}}" data-remarks="{{$col->Remarks}}">
							<i class="fas fas-grey fa-search-plus"></i>
						</a>
					</div>
			@endif

		@endif
		

<?php
	if ($edit == 1){
?>
					<i class="fas fas-grey fa-trash-alt px-1 py-1 text-small-90 ml-auto removeCol" title="remove from list" data-toggle="tooltip"></i>
<?php

	} elseif (is_null($climbed)){
?>
					<span class="ml-auto"></span>
<?php
	} else {
		if ($climbed){
			$col_climbed_class = "col-climbed-yes";
			$col_climbed_title = "You climbed this col";
		} else {
			$col_climbed_class = "col-climbed-no-light";
			$col_climbed_title = "You did not climb this col";
		}
?>
					<i class="col-done fas fa-check {{$col_climbed_class}} px-1 py-1 text-small-90 no-pointer ml-auto" title="{{$col_climbed_title}}" data-toggle="tooltip"></i>
<?php
	}
?>					
				</div>
		@if ($list->EventID > 0)
<?php
$last = $col->lastPassage($list->EventID);
?>
			@if (!is_null($last))
					<div class="d-flex font-weight-light ml-4 align-items-end">
						<span class="text-small-75 mr-1">Last time in {{$last->eventShort()}}: {{$last->Edition}}</span>
						<div class="text-small-75 mr-1">{{$last->Person}}</div>
						<img class="flag flag-small" src='/images/flags/small/{{strtolower($last->NatioAbbr)}}.gif' title='{{$last->Natio}}'/>
					</div>
			@endif
		@endif
	@endforeach
			</div>
		</div>
@endforeach