<?php
namespace App;

use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class LList extends Model
{
    public $timestamps = false;
	
	protected $table = 'lists';

    public function sections()
	{
        return $this->hasMany(ListSection::class,'ListID','ID');
    }

    public function colCount()
	{
        return \App\ListCol::where('ListID', '=', $this->ID)->where('ColID', '>', 0)->distinct('ColID')->count('ColID');
    }

    public function cols()
	{
        return $this->hasMany(ListCol::class,'ListID','ID');
    }
}