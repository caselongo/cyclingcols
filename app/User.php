<?php

namespace App;

use Illuminate\Support\Facades\Auth;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    // NotificationInformation also included Notifiable
    use Authenticatable, Authorizable, CanResetPassword;


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

    public function activities()
	{
        return $this->hasMany(Activity::class,'UserID','id');
    }

    public function following()
	{
        return $this->belongsToMany(User::class,'useruser','UserID','UserIDFollowing',null,null)
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
