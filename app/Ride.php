<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    protected $fillable = array(
		'RideID','DateSort','Stage','Number','Date','FileName','Cols','Countries','WeatherCode','TempMin','TempMax','RideIndex','Distance','HeightDiff'
	);
}