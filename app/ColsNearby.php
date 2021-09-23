<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ColsNearby extends Model
{
    protected $table = 'colsnearby';
	
    protected $fillable = array(
		'MainColID','ColIDString',
		'Col','Latitude','Longitude',
		'Distance','Direction'	
	);
}