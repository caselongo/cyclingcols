<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class StatType extends Model
{
	protected $table = 'stattype';
	
    protected $fillable = array(
		'StatTypeID','StatType',
		'URL','Suffix',
		'NumberOfDecimals','Icon',
		'Description'
	);
}