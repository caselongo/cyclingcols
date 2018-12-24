<?php
function formatStat($statid, $value) {
	switch($statid) {
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

function statName($statid) {
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
}

function statNameShort($statid) {
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
}
?>