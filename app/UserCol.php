<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCol extends Model
{
    protected $fillable = array(
		'Done', 'Rating', 'Favorite', 'ToDo'
	);
}