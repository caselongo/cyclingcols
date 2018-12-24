<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $fillable = array(
		'StatID','GeoID',
		'ColID','ColIDString',
		'Country1ID','Country1','Country2ID','Country2',
		'ProfileID','SideID','Side','FileName','Value','Rank'
	);
}