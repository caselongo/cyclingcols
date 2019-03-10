<?php

namespace App\Http\Controllers\User;

use App\Col;
use App\Country;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	/* views */	
    public function welcome(Request $request)
    {
        return view('pages.welcome');
    }
	
    public function index(Request $request)
    {
        $user = Auth::user();

        $_climbed = $user->cols();
		
		$_climbed_year = clone $_climbed;
		$_climbed_year->wherePivot('ClimbedAt','>=',Carbon::now()->startOfYear());
		
		$_climbed_lastyear = clone $_climbed;
		$_climbed_lastyear->wherePivot('ClimbedAt','<',Carbon::now()->startOfYear())->wherePivot('ClimbedAt','>=',Carbon::now()->addYear(-1)->startOfYear());

		/* climbed recently */	
        $climbed_count = $_climbed->count();
		
		$climbed = clone $_climbed;
		$climbed = $climbed->orderBy('pivot_ClimbedAt', 'desc')->limit(10)->get();

		/* claimed recently */	
        $claimed = clone $_climbed;
		$claimed = $claimed->orderBy('pivot_CreatedAt', 'desc')->limit(10)->get();

		/* climbed this year */	
		$climbed_year_count = $_climbed_year->count();

		/* climbed last year */	
		$climbed_lastyear_count = $_climbed_lastyear->count();
		
		/* countries */
		$countries = Country::get();
		
		$col_count_user_max = 1;
			
		foreach($countries as $country){
			$c = clone $_climbed;
			$cy = clone $_climbed_year;
			$cy = $cy->wherePivot('ClimbedAt','>=',Carbon::now()->startOfYear());
			
			$country->col_count = $country->col_count();
			$country->col_count_user = $c->where('Country1ID', $country->CountryID)->count() + $c->where('Country2ID', $country->CountryID)->count();
			$country->col_count_user_year = $cy->where('Country1ID', $country->CountryID)->count() + $cy->where('Country2ID', $country->CountryID)->count();
			
			if ($country->col_count_user > $col_count_user_max){
				$col_count_user_max = $country->col_count_user;
			}
		}
		
		foreach($countries as $country){
			if ($country->col_count_user > 0){
				if ($country->col_count_user_year > 0){
					$country->width_year = 0.1 + round($country->col_count_user_year/$col_count_user_max,1) * 0.9;
				} else {
					$country->width_year = 0;
				}
				$country->width = 0.1 + round($country->col_count_user/$col_count_user_max,1) * 0.9 - $country->width_year;
			} else {
				$country->width_year = 0;
				$country->width = 0;
			}
		}
		
		$countries = $countries->sortByDesc('col_count_user');

        return view('cols.overview', compact('user', 'climbed', 'climbed_count', 'climbed_year_count', 'climbed_lastyear_count', 'claimed', 'countries'));
    }

}