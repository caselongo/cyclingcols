<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ListCol extends Model
{
	public $timestamps = false;
	
	protected $table = 'listcol';
	
    public function col()
	{
        return $this->hasOne(Col::class,'ColID','ColID');
    }
}