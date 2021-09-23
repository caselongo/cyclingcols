<?php
namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class LList extends Model
{
    public $timestamps = false;
	
    protected $table = 'lists';
    
    public static function boot()
    {
        parent::boot();
		
        static::saving(function ($model) {
			if (is_null($model->Slug)){
				$model->Slug = $model->get_slug($model->Name);
			}
        });
    }
	
	public function get_slug($name){
		$slug = Str::slug($name, '-');
		$slug_ = $slug;
		$suffix = 0;
		
		while($this->slug_exists($slug_)){
			$suffix++;
			$slug_ = $slug . '_' . $suffix;
		}

		return $slug_;
	}
	
	private function slug_exists($slug)
	{
		$list = LList::where('Slug','=',$slug)->first();
		
		return !is_null($list);
	}

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