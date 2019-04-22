<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements CanResetPassword, MustVerifyEmail
{
	use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
		
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->slug = Str::slug($model->name, '-');
        });
    }

    public function cols()
	{
        return $this->belongsToMany(Col::class,'usercol','UserID','ColID',null,'ColID')
			->wherePivot('StravaNew', false)
            ->withPivot(['ClimbedAt','CreatedAt','UpdatedAt','StravaNew','StravaActivityIDs']);
    }

    public function colsNew()
	{
        return $this->belongsToMany(Col::class,'usercol','UserID','ColID',null,'ColID')
			->wherePivot('StravaNew', true)
            ->withPivot(['ClimbedAt','CreatedAt','UpdatedAt','StravaNew','StravaActivityIDs']);
    }

    public function colsAll()
	{
        return $this->belongsToMany(Col::class,'usercol','UserID','ColID',null,'ColID')
			->withPivot(['ClimbedAt','CreatedAt','UpdatedAt','StravaNew','StravaActivityIDs']);
    }

    public function activities()
	{
        return $this->hasMany(Activity::class,'UserID','id');
    }

    public function following()
	{
        return $this->belongsToMany(User::class,'useruser','UserID','UserIDFollowing',null,null)
            ->withPivot(['CreatedAt','UpdatedAt']);
    }

    public function followed()
	{
        return $this->belongsToMany(User::class,'useruser','UserIDFollowing','UserID',null,null)
            ->withPivot(['CreatedAt','UpdatedAt']);
    }

    public function followedByMe()
	{
		$user = Auth::user();
		
		if ($user == null){
			return false;
		} else {
			return \App\UserUser::where('UserID', '=', $user->id)->where('UserIDFollowing', '=', $this->id)->exists();
		}
    }
}
