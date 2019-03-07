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

    public function col_count()
	{
        return $this->hasMany('App\Col','Country1ID','CountryID')->count() + $this->hasMany('App\Col','Country2ID','CountryID')->count();
    }
}