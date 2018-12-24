<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = array(
		'ColID',
		'RedirectURL','BannerFileName',
		'Sort','StartDate','EndDate','Active'
	);
}