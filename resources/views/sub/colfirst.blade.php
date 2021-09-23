@if ($count == 0)
	<span class="text-small-75">Never climbed in Tour, Giro or Vuelta.</span>
@else
	@foreach ($first as $first_)
<?php
		if ($first_->EventID == 1) $first_->RaceShort = "Tour";
		else if ($first_->EventID == 2) $first_->RaceShort = "Giro";
		else if ($first_->EventID == 3) $first_->RaceShort = "Vuelta";
		
		$first_->Flag = true;
		if ($first_->Neutralized == 1) {$first_->Person = "-neutralized-"; $first_->Flag = false;}
		else if ($first_->Cancelled == 1) {$first_->Person = "-cancelled-"; $first_->Flag = false;}
		else if ($first_->NatioAbbr == "") {$first_->Person = "-cancelled-"; $first_->Flag = false;}
		
?>
		<div class="text-small-90 align-items-baseline d-flex">
			<div class="d-flex text-small-75" style="flex: 0 0 80px;">
				<div>{{$first_->RaceShort}}</div>
				<div class="pl-1">{{$first_->Edition}}</div>
			</div>
			<div class="d-flex w-100 align-items-center">
				<div class="px-1 text-truncate">{{$first_->Person}}</div>
		@if ($first_->Flag)
				<img class="flag ml-auto" src='/images/flags/small/{{strtolower($first_->NatioAbbr)}}.gif' title='{{$first_->Natio}}'/>
		@endif
			</div>
		</div>
	@endforeach
@endif