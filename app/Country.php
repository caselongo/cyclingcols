<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = array(
		'CountryID','CountryIDString',
		'Country','CountrySort','CountryAbbr',
		'NrRegions','NrCols','NrProfiles',
		'Latitude','Longitude'	
	);
}