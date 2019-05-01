<?php
namespace App;

use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class ListSection extends Model
{
	public $timestamps = false;
	
	protected $table = 'listsection';

    public function cols()
	{
        return $this->hasMany(ListCol::class,'SectionID','ID');
    }
}