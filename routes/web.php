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
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register')->name('register.post');

// Verification Routes...
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

// Password Reset Routes...
Route::get('password/request', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset.post');

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

// Home
Route::get('/','General\GeneralController@home');

// New
Route::get('new','General\GeneralController@new');

// Help
Route::get('help','General\GeneralController@help');

// About
Route::get('about','General\GeneralController@about');

//Lists
Route::get('list','LList\ListController@index_default');
Route::get('list/{slug}','LList\ListController@index');

// Rides
Route::get('rides','General\GeneralController@rides');

// User

Route::middleware(['verified'])->group(function () {
	Route::get('athlete/welcome', 'User\UserController@welcome');
	Route::get('athlete','User\UserController@index_default');
	Route::get('athlete/{slug}','User\UserController@index');
	Route::get('athlete/{slug}/cols','User\UserController@cols_default');
	Route::get('athlete/{slug}/cols/{countryurl}/{sorttypeurl}','User\UserController@cols');

	// Users
	Route::get('athletes','Users\UsersController@index');
	Route::get('athletes/cols/{countryurl}/{yearurl}/{athleteurl}','Users\UsersController@cols');
	Route::get('athletes/athletes/{countryurl}/{yearurl}/{athleteurl}','Users\UsersController@athletes');
});

// Strava
Route::get('strava/connect','Strava\StravaController@connect');
Route::get('strava/process','Strava\StravaController@process');
Route::get('strava/cols','Strava\StravaController@cols');
Route::get('strava/error','Strava\StravaController@error');
Route::get('strava/claim','Strava\StravaController@claim');

Route::middleware(['ajax'])->group(function () {
	/* col */
	Route::get('service/col/nearby/{colIDString}','Col\ColController@_nearby');
	Route::get('service/col/first/{colIDString}','Col\ColController@_first_all');
	Route::get('service/col/first/{colIDString}/{limit}','Col\ColController@_first');
	Route::get('service/col/top/{colIDString}','Col\ColController@_col_top');
	Route::get('service/col/profile/top/{profileFileName}','Col\ColController@_profile_top');
	Route::get('service/col/profile/{fileName}','Col\ColController@_profile');
	Route::get('service/col/athlete/{colIDString}','Col\ColController@_user');
	Route::post('service/col/athlete/save/{colIDString}','Col\ColController@_user_save');
	Route::post('service/col/athlete/delete/{colIDString}','Col\ColController@_user_delete');
	Route::get('service/col/athletes/{colIDString}','Col\ColController@_users');
	
	/* cols */
	Route::get('service/cols','Cols\ColsController@_cols');
	Route::get('service/cols/search','Cols\ColsController@_search');
	Route::get('service/cols/photos','Cols\ColsController@_photos');

	/* stat */
	Route::get('service/stats/top/{country_url}','Stats\StatsController@_top');		
	
	/* general */
	Route::get('service/countries','General\GeneralController@_countries');
	Route::get('service/regions','General\GeneralController@_regions');		
	Route::get('service/subregions','General\GeneralController@_subregions');
	Route::get('service/rides','General\GeneralController@_rides');
	Route::get('service/banners','General\GeneralController@_banners');

	/* strava */
	Route::get('service/strava/status/{processed}','Strava\StravaController@_status');
	
	/* user */
	Route::get('service/user/following/{slug}','User\UserController@_following');
	Route::post('service/user/follow/{slug}','User\UserController@_follow');
	Route::post('service/user/unfollow/{slug}','User\UserController@_unfollow');
	
	/* users */
	Route::get('service/athletes/search','Users\UsersController@_search');
});

	