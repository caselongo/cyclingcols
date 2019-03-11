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

// Col
Route::get('col/{colIDString}','Col\ColController@col');

// Map
Route::get('map','Map\MapController@map');
Route::get('map/country/{countryIDString}','Map\MapController@country');
Route::get('map/region/{regionIDString}','Map\MapController@region');
Route::get('map/subregion/{subregionIDString}','Map\MapController@subregion');
Route::get('map/col/{colIDString}','Map\MapController@col');

// Stats
Route::get('stats/{stattypeurl}/{countryurl}','Stats\StatsController@index');
Route::get('stats','Stats\StatsController@index_default');

// Stats2
Route::get('stats2/{stattypeurl}/{countryurl}','Stats\Stats2Controller@index');
Route::get('stats2','Stats\Stats2Controller@index_default');

// Home
Route::get('/','General\GeneralController@home');

// New
Route::get('new','General\GeneralController@new');

// Help
Route::get('help','General\GeneralController@help');

// About
Route::get('about','General\GeneralController@about');

// Rides
Route::get('rides','General\GeneralController@rides');

// User
Route::get('user/welcome', 'User\UserController@welcome');
Route::get('user','User\UserController@index');

	Route::get('service/col/user/save/{colIDString}','Col\ColController@_user_save');
Route::middleware(['ajax'])->group(function () {
	/* col */
	Route::get('service/col/nearby/{colIDString}','Col\ColController@_nearby');
	Route::get('service/col/first/{colIDString}','Col\ColController@_first_all');
	Route::get('service/col/first/{colIDString}/{limit}','Col\ColController@_first');
	Route::get('service/col/top/{colIDString}','Col\ColController@_col_top');
	Route::get('service/col/profile/top/{profileFileName}','Col\ColController@_profile_top');
	Route::get('service/col/profile/{fileName}','Col\ColController@_profile');
	Route::get('service/col/user/{colIDString}','Col\ColController@_user');
	Route::get('service/col/users/{colIDString}','Col\ColController@_users');
	
	/* cols */
	Route::get('service/cols','Cols\ColsController@_cols');
	Route::get('service/cols/search','Cols\ColsController@_search');
	Route::get('service/cols/photos','Cols\ColsController@_photos');

	/* stat */
	Route::get('service/stats/top/{country_url}','Stats\StatsController@_top');	
	Route::get('service/stats2/top/{country_url}','Stats\Stats2Controller@_top');	
	
	/* general */
	Route::get('service/countries','General\GeneralController@_countries');
	Route::get('service/regions','General\GeneralController@_regions');		
	Route::get('service/subregions','General\GeneralController@_subregions');
	Route::get('service/rides','General\GeneralController@_rides');
	Route::get('service/banners','General\GeneralController@_banners_all');	
	Route::get('service/banners/{colIDString}','General\GeneralController@_banners');	
});

	