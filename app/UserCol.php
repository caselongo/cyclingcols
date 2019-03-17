<?php
namespace App;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class UserCol extends Model
{
	public $timestamps = false;
	
	protected $table = 'usercol';
	
    protected $fillable = array(
		'ClimbedAt', 'CreatedAt', 'UpdatedAt'
	);
}