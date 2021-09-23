<?php

use Carbon\Carbon;

function formatStat($stattypeid, $value) {
	switch($stattypeid) {
		case 1://distance
			return number_format($value/10,1) . 'km';
			break;
		case 2://altitude gain
			return $value . 'm';
			break;			
		case 3://avg slope
			return number_format($value/10,1) . '%';
			break;			
		case 4://max slope
			return number_format($value/10,1) . '%';
			break;			
		case 5://profile index
			return $value;
			break;			
		case 13://distance 5%
			return number_format($value/10,1) . 'km';
			break;			
		case 14://distance 10%
			return number_format($value/10,1) . 'km';
			break;			
		case 15://max slope 1km
			return number_format($value/10,1) . '%';
			break;			
		case 16://max slope 5km
			return number_format($value/10,1) . '%';
			break;
		default:
			return $value;
	}
}

function getStatCat($stattypeid, $value) {

	$category = 0;
	
	/* distance */
	if ($stattypeid == 1){
		if($value < 50) {$category = 5;} 
		elseif($value < 100) {$category = 4;} 
		elseif($value < 150) {$category = 3;} 
		elseif($value < 200) {$category = 2;} 
		else {$category = 1;}
	}	
	/* altitude gain */
	else if ($stattypeid == 2){
		if($value < 400) {$category = 5;} 
		elseif($value < 800) {$category = 4;} 
		elseif($value < 1300) {$category = 3;} 
		elseif($value < 1800) {$category = 2;} 
		else {$category = 1;}
	}	
	/* average slope */
	else if ($stattypeid == 3){
		if($value < 40) {$category = 5;} 
		elseif($value < 60) {$category = 4;} 
		elseif($value < 80) {$category = 3;} 
		elseif($value < 100) {$category = 2;} 
		else {$category = 1;}
	}	
	/* maximum slope */
	else if ($stattypeid == 4){
		if($value < 60) {$category = 5;} 
		elseif($value < 80) {$category = 4;} 
		elseif($value < 100) {$category = 3;} 
		elseif($value < 120) {$category = 2;} 
		else {$category = 1;}
	}	
	/* profile index */
	else if ($stattypeid == 5){
		if($value < 300) {$category = 5;} 
		elseif($value < 500) {$category = 4;} 
		elseif($value < 700) {$category = 3;} 
		elseif($value < 900) {$category = 2;} 
		else {$category = 1;}
	}		
	/* distance 5 */
	else if ($stattypeid == 13){
		if($value < 30) {$category = 5;} 
		elseif($value < 55) {$category = 4;} 
		elseif($value < 80) {$category = 3;} 
		elseif($value < 120) {$category = 2;} 
		else {$category = 1;}
	}			
	/* distance 10 */
	else if ($stattypeid == 14){
		if($value < 1) {$category = 5;} 
		elseif($value < 5) {$category = 4;} 
		elseif($value < 15) {$category = 3;} 
		elseif($value < 30) {$category = 2;} 
		else {$category = 1;}
	}					
	/* max perc 1 */
	else if ($stattypeid == 15){
		if($value < 70) {$category = 5;} 
		elseif($value < 80) {$category = 4;} 
		elseif($value < 95) {$category = 3;} 
		elseif($value < 115) {$category = 2;} 
		else {$category = 1;}
	}					
	/* max perc 5 */
	else if ($stattypeid == 16){
		if($value < 50) {$category = 5;} 
		elseif($value < 60) {$category = 4;} 
		elseif($value < 70) {$category = 3;} 
		elseif($value < 80) {$category = 2;} 
		else {$category = 1;}
	}

	return $category;
}

/*function statName($statid) {
	switch($statid) {
		case 1://distance
			return 'Distance';
			break;
		case 2://altitude gain
			return 'Altitude Gain';
			break;			
		case 3://avg slope
			return 'Average Slope';
			break;			
		case 4://max slope
			return 'Maximum Slope';
			break;			
		case 5://profile index
			return 'Profile Index';
			break;
		default:
			return '???';
	}
}*/

/*function statNameShort($statid) {
	switch($statid) {
		case 1://distance
			return 'distance';
			break;
		case 2://altitude gain
			return 'altgain';
			break;			
		case 3://avg slope
			return 'avgslope';
			break;			
		case 4://max slope
			return 'maxslope';
			break;			
		case 5://profile index
			return 'profileidx';
			break;
		default:
			return '???';
	}
}*/
function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
      return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
  } else {
      return $miles;
  }
}

function latitudeToDistance($delta_lat){
	return $delta_lat * 110.54;
}

function longitudeToDistance($delta_lng, $lat){
	return $delta_lng * 111.320 * cos((($lat/360)*2*pi()));
}

function distanceToLatitude($distance){
	return $distance / 110.54;
}

function distanceToLongitude($distance, $lat){
	return $distance / ( 111.320 * cos((($lat/360)*2*pi())) );
}

function getHumanDate($date){
	if (!$date) return null;
	
	if (is_string($date)){
		$date = Carbon::parse($date);
	}	
	
	if ($date->isToday()) return "today";
	elseif ($date->isYesterday()) return "yesterday";
	else return $date->format('d M Y');
}

function getDate_dMY($date){
	if (!$date) return null;
	
	if (is_string($date)){
		$date = Carbon::parse($date);
	}	
	
	return $date->format('d M Y');
}

?>