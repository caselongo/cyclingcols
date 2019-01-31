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
	$newitems = \App\NewItem::orderBy('DateSort','DESC')->orderBy('ColIDString','ASC')->get();
	
	$datesort = 0;
	$colidstring = "";
	
	$cols = array();
	$col;
	
	foreach($newitems as $newitem){
		if ($newitem->DateSort != $datesort || $newitem->ColIDString != $colidstring){
			
			$col = new stdClass;
			$col->DateSort = $newitem->DateSort;
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
	return Redirect::to('stats/all/0');
});

Route::get('stats/{stattypeurl}/{countryurl}', function($stattypeurl,$countryurl)
{   
	/* stattype */
	function createStatType($id,$name,$url){	
		$stattype = new stdClass;
		$stattype->id = $id;
		$stattype->name = $name; 
		$stattype->url = $url; 
		
		return $stattype;
	}
	
	$stattypes = array();
	array_push($stattypes,createStatType(0,"All Stats","all"));
	array_push($stattypes,createStatType(1,"Distance","distance"));
	array_push($stattypes,createStatType(2,"Altitude Gain","altitudegain"));
	array_push($stattypes,createStatType(3,"Average Slope","averageslope"));
	array_push($stattypes,createStatType(4,"Maximum Slope","maximumslope"));
	array_push($stattypes,createStatType(5,"Profile Index","profileindex"));
	
	$stattype_current = null;
	foreach($stattypes as $stattype){
		if ($stattype->url == $stattypeurl){
			$stattype_current = $stattype;
			break;
		}
	}
	
	/* country */
	function createCountry($id,$name,$url,$flag){	
		$country = new stdClass;
		$country->id = $id;
		$country->name = $name; 
		$country->url = $url; 
		$country->flag = $flag; 
		
		return $country;
	}
	
	$countries = array();
	array_push($countries,createCountry(0,"All Countries","all","Europe"));
	array_push($countries,createCountry(2,"Andorra","and","Andorra"));
	array_push($countries,createCountry(3,"Austria","aut","Austria"));
	array_push($countries,createCountry(4,"France","fra","France"));
	array_push($countries,createCountry(5833,"Great-Britain","gbr","Great-Britain"));
	array_push($countries,createCountry(5,"Italy","ita","Italy"));
	array_push($countries,createCountry(6383,"Norway","nor","Norway"));
	array_push($countries,createCountry(6,"Slovenia","slo","Slovenia"));
	array_push($countries,createCountry(7,"Spain","spa","Spain"));
	array_push($countries,createCountry(8,"Switzerland","swi","Switzerland"));
	
	$country_current = null;
	foreach($countries as $country){
		if ($country->url == $countryurl){
			$country_current = $country;
			break;
		}
	}
	
	if (is_null($stattype_current) && is_null($country_current)){
		return Redirect::to('stats/all/all');
	} else if (is_null($stattype_current)){
		return Redirect::to('stats/all/' . $countryurl);
	} else if (is_null($country_current)){
		return Redirect::to('stats/' . $stattypeurl . "/all");		
	}


	if ($stattype_current->id > 0) {
		$stats = \App\Stat::whereRaw('StatID = ' . $stattype_current->id . ' AND GeoID = ' . $country_current->id)->orderBy('Rank','ASC')->get();
	} else {
		$stats = \App\Stat::whereRaw('GeoID = ' . $country_current->id . ' AND Rank <= 5')->orderBy('StatID','ASC')->orderBy('Rank','ASC')->get();
	}
	
	if (is_null($stats))
	{
		return Redirect::to('stats/all/all');
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

Route::get('/welcome', 'WelcomeController@index')->name('welcome');
