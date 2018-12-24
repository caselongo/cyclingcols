<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Passage extends Model
{
    protected $fillable = array(
		'ColID','ProfileID','SideID','Side','SideAbbr',
		'EventID','EditionID','Edition',
		'PersonID','Person',
		'NatioID','Natio','NatioAbbr',
		'Cancelled','Neutralized'
	);
}