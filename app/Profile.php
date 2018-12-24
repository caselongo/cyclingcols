<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model 
{
	protected $fillable = array(
		'ProfileID',
		'ColID',
		'SideID','SideNumber','Side','SideAbbr',
		'Start',
		'FileName',
		'Distance',
		'StartHeight','EndHeight','HeightDiff',
		'AvgPerc','MinPerc','MaxPerc',
		'MaxPerc1','MaxPerc5',
		'Distance5','Distance10',
		'ProfileIdx',
		'Category',
		'Unpaved'
	);
	public function Col(){
		return $this->belongsTo('Col');
	}
}
?>