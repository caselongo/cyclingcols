<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/* Homepage */
Route::get('/', function()
{
	return View::make('pages.mainsearch')
	->with('pagetype','home');
});

/* New page*/
Route::get('new', function()
{
	$newitems = \App\NewItem::orderBy('DateSort','DESC')->get();
	
	return View::make('pages.new')
		->with('newitems',$newitems)
		->with('pagetype','newtemplate');
});

/* About page*/
Route::get('about', function()
{
	return View::make('pages.about', array('pagetype'=>'abouttemplate'));
});

/* Help page */
Route::get('help', function()
{
    return View::make('pages.help', array('pagetype'=>'helptemplate'));
});

/*Col page*/
Route::get('col/{colIDString}', function($colIDString)
{
	$col = \App\Col::where('ColIDString',$colIDString)->first();
	
	if (is_null($col))
	{
		return Redirect::to('/');
	}

	$profiles = \App\Profile::where('ColID',$col->ColID)->get();
	
	$user = Auth::user();
	$usercol = null;
	if($user != null)
	{
		$usercol = $user->cols()->where('cols.ColID','=',$col->ColID)->first();
	}
	
	return View::make('pages.col')
		->with('col',$col)
		->with('profiles',$profiles)
		->with('user',$user)
		->with('usercol',$usercol)
		->with('pagetype','coltemplate');
});

/*Col page - select upmost profile*/
Route::get('col/{colIDString}/{profileID}', function($colIDString,$profileID)
{
	$col = \App\Col::where('ColIDString',$colIDString)->first();
	
	if (is_null($col))
	{
		return Redirect::to('/');
	}
	
	$orderBy ='CASE WHEN ProfileID = ' . $profileID . ' THEN 0 ELSE 1 END';
	
	$profiles = \App\Profile::where('ColID',$col->ColID)->orderBy(DB::raw($orderBy),'ASC')->get();
	
	$user = Auth::user();
	$usercol = null;
	if($user != null)
	{
		$usercol = $user->cols()->where('cols.ColID','=',$col->ColID)->first();
	}
	
	return View::make('pages.col')
		->with('col',$col)
		->with('profiles',$profiles)
		->with('user',$user)
		->with('usercol',$usercol)
		->with('pagetype','coltemplate');
});

/* googlemaps pages*/
Route::get('map', function()
{   
	return View::make('pages.map')
		->with('pagetype','mappage');
});

Route::get('map/country/{country}', function($country)
{   
	$country = \App\Country::where('CountryIDString',$country)->first();
	
	if (is_null($country))
	{
		return Redirect::to('/map');
	}
	
    return View::make('pages.map')
		->with('country',$country)
		->with('pagetype','mappage');
});

/*col page*/

Route::get('map/col/{col}', function($col)
{   
	$col = \App\Col::where('ColIDString',$col)->first();
	
	if (is_null($col))
	{
		return Redirect::to('/map');
	}
	
    return View::make('pages.map')
		->with('col',$col)
		->with('pagetype','mappage');
});


/*rides page*/

Route::get('rides', function()
{   
	return View::make('pages.rides')
		->with('pagetype','ridestemplate');
});

/*stats page*/

Route::get('stats', function()
{   
	return Redirect::to('stats/0/0');
});

Route::get('stats/{statid}/{geoid}', function($statid,$geoid)
{   
	if ($statid > 0) {
		$stats = \App\Stat::whereRaw('StatID = ' . $statid . ' AND GeoID = ' . $geoid)->get();
	} else {
		$stats = \App\Stat::whereRaw('GeoID = ' . $geoid . ' AND Rank <= 5')->get();
	}
	
	if (is_null($stats))
	{
		return Redirect::to('stats/0/0');
	}
	
    return View::make('pages.stats')
		->with('stats',$stats)
		->with('statid',$statid)
		->with('geoid',$geoid)
		->with('pagetype','statstemplate');
});

// Login Routes...
Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', ['as' => 'login.post', 'uses' => 'Auth\LoginController@login']);
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

// Registration Routes...
Route::get('register', ['as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm']);
Route::post('register', ['as' => 'register.post', 'uses' => 'Auth\RegisterController@register']);

// Password Reset Routes...
Route::get('password/reset', ['as' => 'password.reset', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
Route::post('password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
Route::get('password/reset/{token}', ['as' => 'password.reset.token', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
Route::post('password/reset', ['as' => 'password.reset.post', 'uses' => 'Auth\ResetPasswordController@reset']);

Route::get('/home', 'HomeController@index')->name('home');
