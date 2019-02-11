<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class SubRegion extends Model
{
    protected $table = 'subregions';
	
    protected $fillable = array(
		'SubRegionID','SubRegionIDString','CountryID','RegionID',
		'SubRegion','SubRegionSort','SubRegionAbbr',
		'NrCols','NrProfiles',
		'Latitude','Longitude'	
	);
}