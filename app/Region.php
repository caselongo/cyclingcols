<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = array(
		'RegionID','RegionIDString','CountryID',
		'Region','RegionSort','RegionAbbr',
		'NrSubRegions','NrCols','NrProfiles',
		'Latitude','Longitude'	
	);
}