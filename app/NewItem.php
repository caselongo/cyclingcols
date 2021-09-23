<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class NewItem extends Model
{
	public $table = 'newitems';
	
    protected $fillable = array(
		'DateSort','Date',
		'ColID','ColIDString','Col',
		'Country1ID','Country1IDString','Country1',
		'Country2ID','Country2IDString','Country2',
		'Region1ID','Region1IDString','Region1',
		'Region2ID','Region2IDString','Region2',
		'SubRegion1ID','SubRegion1IDString','SubRegion1',
		'SubRegion2ID','SubRegion2IDString','SubRegion2',
		'Height',
		'ProfileID',
		'SideID','Side',
		'FileName','Category',
		'HTML',
		'IsNew','IsNewCol'
	);
}