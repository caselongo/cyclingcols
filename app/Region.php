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

    public function col_count()
	{
        return $this->hasMany('App\Col','Region1ID','RegionID')->count() + $this->hasMany('App\Col','Region2ID','RegionID')->count();
    }
	
    public function country()
    {
        return $this->belongsTo('App\Country','CountryID','CountryID');
    }
}