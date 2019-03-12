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
	protected $sorttypeurl_default = "climbed";
	protected $countryurl_default = "eur";
	
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

        return view('pages.user', compact('user', 'climbed', 'climbed_count', 'climbed_year_count', 'climbed_lastyear_count', 'claimed', 'countries'));
    }
	
    public function cols(Request $request, $countryurl, $sorttypeurl)
    {		
        /* sorttype */	
		
		$sorttype_claimed = new \stdClass();
		$sorttype_claimed->SortType = "Claimed"; 
		$sorttype_claimed->URL = "claimed";
		$sorttype_claimed->Field = "CreatedAt";
		$sorttype_claimed->PivotField = "pivot_CreatedAt";
		
		$sorttype_climbed = new \stdClass();
		$sorttype_climbed->SortType = "Climbed"; 
		$sorttype_climbed->URL = "climbed"; 
		$sorttype_climbed->Field = "ClimbedAt";	
		$sorttype_climbed->PivotField = "pivot_ClimbedAt";	
		
		$sorttypes = array($sorttype_claimed, $sorttype_climbed);
				
		$sorttype_current = null;
		foreach($sorttypes as $sorttype){
			if ($sorttype->URL == $sorttypeurl){
				$sorttype_current = $sorttype;
				break;
			}
		}
		
		/* country */	
		$countries = \App\Country::get();
		
		$country_all = new \stdClass();
		$country_all->CountryID = 0;
		$country_all->Country = "Europe"; 
		$country_all->URL = "eur"; 
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
		
		if (is_null($sorttype_current) && is_null($country_current)){
			return \Redirect::to('user/cols/' . $this->sorttypeurl_default . '/' . $countryurl_default);
		} else if (is_null($sorttype_current)){
			return \Redirect::to('user/cols/' . $this->sorttypeurl_default . '/' . $countryurl);
		} else if (is_null($country_current)){
			return \Redirect::to('user/cols/' . $sorttypeurl . '/' . $countryurl_default);		
		}

        $user = Auth::user();
				
		$cols = $user->cols()->select("cols.ColIDString", "cols.Col", "cols.Country1ID", "cols.Country1", "cols.Country2ID", "cols.Country2");
		
		if ($country_current->CountryID > 0) {
			$this->CountryID = $country_current->CountryID;
			
			$cols = $cols->where(function ($q) {
				$q->where('Country1ID', '=', $this->CountryID)->orWhere('Country2ID', '=', $this->CountryID);
			});
		} 

		$cols = $cols->orderBy($sorttype_current->PivotField, 'desc')->get();
		
        return view('pages.usercols')
			->with('sorttypes',$sorttypes)
			->with('sorttype',$sorttype_current)
			->with('countries',$countries)
			->with('country',$country_current)
			->with('cols',$cols);
	}

}