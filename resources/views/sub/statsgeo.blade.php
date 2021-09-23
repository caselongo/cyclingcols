<?php
	if ($stattype->StatTypeID > 0 && count($stats) > 0){
		$pre = "";
		if ($stattype->Type == 1) $pre = "Highest";
		else if ($stattype->Type == 2) $pre = "Most";
?>
				<div class="card mb-3">
					<div class="card-header p-2 d-flex justify-content-between align-items-baseline">
						<div>
							<i class="fas fas-grey fa-{{$stattype_current->Icon}} no-pointer"></i>
							<span>{{$pre}} {{$stattype_current->StatType}} Per {{$geotype}}</span>
						</div>
					</div>
					<div class="card-body p-2 font-weight-light text-small-90">
<?php
	
		foreach ($stats as $stat){
			$countryid_ = $stat->Country1ID;
			$country_ = $stat->Country1;
			$geo = "";
			
			if ($stat->GeoID == $stat->Country2ID){
				$countryid_ = $stat->Country2ID;
				$country_ = $stat->Country2;
			} else if ($stat->GeoID == $stat->CountryID){
				$countryid_ = $stat->CountryID;
				$country_ = $stat->Country;
			}	

			switch($stat->GeoID) {
				case  $stat->Country1ID:
					$geo = $stat->Country1;
					break;
				case  $stat->Country2ID:
					$geo = $stat->Country2;
					break;
				case  $stat->Region1ID:
					$geo = $stat->Region1;
					break;
				case  $stat->Region2ID:
					$geo = $stat->Region2;
					break;
				case  $stat->SubRegion1ID:
					$geo = $stat->SubRegion1;
					break;
				case  $stat->SubRegion2ID:
					$geo = $stat->SubRegion2;
					break;
				}

			$value_ = formatStat($stat->StatTypeID, $stat->Value);			
?>
						<div class="align-items-end d-flex">
							<img src="/images/flags/{{$country_}}.gif" title="{{$country_}}" class="flag mr-1">		
		@if ($geo != $country_ && $geo)
							<div class="text-truncate" style="width: 150px" title="{{$geo}}">
								{{$geo}}
							</div>
		@endif	
							<div class="text-truncate">

		@if ($stat->Col)
								<a href="/col/{{$stat->ColIDString}}{{ $stat->FileName ? '#' . $stat->FileName : '' }}">{{$stat->Col}}</a>
		@elseif ($stat->SubRegion)
								{{$stat->SubRegion}}
		@elseif ($stat->Region)
								{{$stat->Region}}
		@endif

							</div>
		@if ($stat->Side)
							<div class="ml-1 text-small-75" style="flex: 0 0 40px;" title="{{$stat->Side}}">
								<img class="direction" src="/images/{{$stat->Side}}.png">
								<!--<span class="pl-1 text-small-75">{{$stat->Side}}</span>-->
							</div>
		@endif
							<div class="ml-auto text-small-75 text-right" style="flex: 0 0 45px;">{{$value_}}</div>
						</div>					
<?php		
			}
?>
					</div><!--card-body-->
				</div><!--card-->
<?php
		}
?>