<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserUser extends Model
{
	const CREATED_AT = 'CreatedAt';
	const UPDATED_AT = 'UpdatedAt';

	protected $primaryKey = "ID";
	
	protected $table = 'useruser';
	
    protected $fillable = array(
		'CreatedAt', 'UpdatedAt'
	);
}