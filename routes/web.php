<?php

use Carbon\Carbon;
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
	$newitems = \App\NewItem::orderBy('DateSort','DESC')->orderBy('ColIDString','ASC')->orderBy('IsNew','DESC')->get();
	
	$datesort = 0;
	$colidstring = "";
	
	$cols = array();
	$col;
	
	foreach($newitems as $newitem){
		if ($newitem->DateSort != $datesort || $newitem->ColIDString != $colidstring){
			
			$col = new stdClass;
			$col->DateSort = $newitem->DateSort;
			$col->DateString = Carbon::createFromFormat('Ymd',$newitem->DateSort)->format('j M Y');
			$col->DiffForHumans = Carbon::createFromFormat('Ymd',$newitem->DateSort)->diffForHumans();
			$col->ColIDString = $newitem->ColIDString;
			$col->Col = $newitem->Col;
			$col->Country1 = $newitem->Country1;
			$col->Country2 = $newitem->Country2;
			$col->Height = $newitem->Height;
			$col->IsNew = false;
			$col->Profiles = array();
			
			array_push($cols,$col);
			
			$datesort = $newitem->DateSort;
			$colidstring = $newitem->ColIDString;
		}
		
		if ($newitem->IsNewCol){
			$col->IsNew = true;
		}
		
		$profile = new stdClass;
		$profile->ProfileID = $newitem->ProfileID;
		$profile->Side = $newitem->Side;
		$profile->Category = $newitem->Category;
		$profile->FileName = $newitem->FileName;
		$profile->IsNew = $newitem->IsNew;
		
		$start = \App\Profile::where("ProfileID",$newitem->ProfileID)->get();
		if ($start){
			$profile->Start = $start[0]->Start;
		}
		
		array_push($col->Profiles,$profile);
	}
	
	return View::make('pages.new')
		->with('newitems',$cols);
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
		->with('usercol',$usercol);
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
	return Redirect::to('stats/all/all');
});

Route::get('stats/{stattypeurl}/{countryurl}', function($stattypeurl,$countryurl)
{   
	/* stattype */	
	$stattypes = \App\StatType::get();
	
	$stattype_current = null;
	foreach($stattypes as $stattype){
		if ($stattype->URL == $stattypeurl){
			$stattype_current = $stattype;
			break;
		}
	}
	
	/* country */	
	$countries = \App\Country::get();
	
	$country_all = new stdClass;
	$country_all->CountryID = 0;
	$country_all->Country = "All Countries"; 
	$country_all->URL = "all"; 
	$country_all->Flag = "europe"; 		
	$countries->prepend($country_all);	
	
	$country_current = null;
	foreach($countries as $country){
		if ($country->CountryID > 0){
			$country->URL = strtolower($country->CountryAbbr);
			$country->Flag = strtolower($country->Country);
		}
		
		if ($country->URL == $countryurl){
			$country_current = $country;
		}
	}
	
	if (is_null($stattype_current) && is_null($country_current)){
		return Redirect::to('stats/all/all');
	} else if (is_null($stattype_current)){
		return Redirect::to('stats/all/' . $countryurl);
	} else if (is_null($country_current)){
		return Redirect::to('stats/' . $stattypeurl . "/all");		
	}

	if ($stattype_current->StatTypeID > 0) {
		$stats = \App\Stat::where('StatTypeID', $stattype_current->StatTypeID)->where('GeoID', $country_current->CountryID)->orderBy('Rank','ASC')->get();
	} else {
		$stats = \App\Stat::where('GeoID', $country_current->CountryID)->where('Rank','<=', 5)->orderBy('StatTypeID','ASC')->orderBy('Rank','ASC')->get();
	}
	
	if (is_null($stats))
	{
		return Redirect::to('stats/all/all');
	}
	
	foreach($stats as $stat){
		$col = \App\Col::where('ColID',$stat->ColID)->first();
		
		if ($col != null){
			$stat->Height = $col->Height;
			$stat->CoverPhotoPosition = $col->CoverPhotoPosition;
		}
	}
	
    return View::make('pages.stats')
		->with('stattypes',$stattypes)
		->with('stattype',$stattype_current)
		->with('country',$country_current)
		->with('stats',$stats)
		->with('countries',$countries);
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

Route::get('welcome', 'WelcomeController@index')->name('welcome');

Route::middleware(['ajax'])->group(function () {
	/* col */
	Route::get('col/rating/{colIDString}','Col\ColController@rating')->name('col.col.rating');
	Route::get('col/nearby/{colIDString}','Col\ColController@nearby')->name('col.col.nearby');
	Route::get('col/first/{colIDString}','Col\ColController@first')->name('col.col.first');
	Route::get('col/top/{colIDString}','Col\ColController@topcol')->name('col.col.topcol');
	Route::get('col/profile/top/{profileFileName}','Col\ColController@topprofile')->name('col.col.topprofile');
	Route::get('col/profile/{fileName}','Col\ColController@profile')->name('col.col.profile');

	/* cols */
	Route::get('cols/all','Cols\ColsController@all')->name('cols.cols.all');
	Route::get('cols/photos','Cols\ColsController@photos')->name('cols.cols.photos');
	
});
