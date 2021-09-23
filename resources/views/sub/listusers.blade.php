		<div class="card mb-3">
			<div class="card-header p-2">
				Most Cols In This List Climbed
			</div>
			<div class="card-body p-2 font-weight-light text-small-90">
@foreach($users as $user)
				<div class="align-items-baseline d-flex">
	@auth
					<div class="text-truncate">
						<a href="/athlete/{{$user->slug}}">{{$user->name}}</a>
					</div>
					<div class="text-primary text-small-75 text-right" style="flex: 0 0 15px;">
		@if ($user->id == Auth::user()->id)
						<i class="fas fa-user" title="That's you!" data-toggle="tooltip"></i> 
		@elseif ($user->followedByMe())
						<i class="fas fa-check" title="Following" data-toggle="tooltip"></i> 
		@endif
					</div>
	@else
					<div class="text-truncate">{{$user->name}}</div>		
	@endauth
					<div class="ml-auto text-small-75 text-right" style="flex: 0 0 75px;">
							{{$user->count}}
					</div>
				</div>									
@endforeach
@if (count($users) == 0)
				<span class="text-small-75">Nothing to show here.</span>
@endif
			</div>				
			<div class="card-footer text-muted">
				<span class="text-small-75">{{$list->colCount()}} cols in this list</span>
			</div><!--card-footer-->
		</div>
@if ($list->colCount() > 0)
		<div class="col-box w-100 mb-1 h-100">
			<div id="map" class="col-map">
			</div>			
		</div>	
@endif