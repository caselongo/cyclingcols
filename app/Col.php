<?php
namespace App;

use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class Col extends Model
{
    public $timestamps = false;
	
	protected $fillable = array(
		'ColID','ColIDString',
		'Country1ID','Country1IDString','Country1','Country2ID','Country2IDString','Country2',
		'Region1ID','Region1IDString','Region1','Region2ID','Region2IDString','Region2',
		'SubRegion1ID','SubRegion1IDString','SubRegion1','SubRegion2ID','SubRegion2IDString','SubRegion2',
		'Col','ColSort','ColAbbr',
		'ColTypeID','ColType',
		'Height',
		'Latitude','Longitude',
		'CoverPhoto','CoverPhotoPosition','CoverPhotoPosition2',
		'Panel','PanelSource','PanelSourceURL',
		'URL','Number','Aliases',
		'HasImages'
	);

    public function users()
	{
        return $this->belongsToMany(User::class,'usercol','ColID','UserID','ColID',null)
			->wherePivot('StravaNew', false)
            ->withPivot(['ClimbedAt','CreatedAt','UpdatedAt','StravaActivityIDs']);
    }

    public function climbedByMe()
	{
		$user = Auth::user();
		
		if ($user == null){
			return false;
		} else {
			return \App\UserCol::where('UserID', '=', $user->id)->where('ColID', '=', $this->ColID)->exists();
		}
    }
}