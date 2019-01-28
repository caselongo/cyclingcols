<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = array(
		'ImageID',
		'ColID','ProfileID',
		'Description','Number',
		'URL',
		'Source','SourceURL'
	);
}