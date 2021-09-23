<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
	const CREATED_AT = 'CreatedAt';
	const UPDATED_AT = 'UpdatedAt';

	protected $primaryKey = "ID";
	
    protected $fillable = array(
		'UserID','AthleteID','ActivityID',
		'LatitudeMin','LatitudeMax',
		'LongitudeMin','LongitudeMax'
	);
}