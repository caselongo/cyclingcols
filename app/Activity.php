<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
	public $timestamps = false;	
	
    protected $fillable = array(
		'UserID','AthleteID','ActivityID',
		'LatitudeMin','LatitudeMax',
		'LongitudeMin','LongitudeMax'
	);
}