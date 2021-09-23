<?php
function formatStat2($stattypeid, $value) {
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
		default:
			return $value;
	}
}

function getStatCat2($stattypeid, $value) {

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
?>