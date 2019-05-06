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
	
	public function eventShort(){
		if ($this->EventID == 1) return "Tour";
		if ($this->EventID == 2) return "Giro";
		if ($this->EventID == 3) return "Vuelta";
		return 0;
	}
}