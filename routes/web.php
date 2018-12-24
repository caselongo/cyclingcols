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
	
	return View::make('pages.col')
		->with('col',$col)
		->with('profiles',$profiles)
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
	
	return View::make('pages.col')
		->with('col',$col)
		->with('profiles',$profiles)
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