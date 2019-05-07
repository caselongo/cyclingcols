<?php

namespace App\Http\Controllers\Users;

use App\Col;
use App\Country;
use App\User;
use App\UserCol;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
	protected $yearurl_default = "all";
	protected $countryurl_default = "eur";
	protected $athleteurl_default = "all";
	
    public function __construct()
    {
        $this->middleware('auth');
    }

	/* views */	
	public function index(Request $request)
    {
		$user = Auth::user();
		
		/* overview */
		$total = UserCol::count();
		
		$total_year = UserCol::where('ClimbedAt','>=',Carbon::now()->startOfYear())->count();
		
		$total_lastyear = UserCol::where('ClimbedAt','<',Carbon::now()->startOfYear())->where('ClimbedAt','>=',Carbon::now()->addYear(-1)->startOfYear())->count();
		
		$users = User::count();
		$users_following = $user->following()->count();
		$users_followed = $user->followed()->count();
		
		$cols = UserCol::distinct()->count('ColID');
	
		/* users */
		$users_most = User::join('usercol','usercol.UserID', '=', 'users.id')
			->where('StravaNew','=',0)
			->groupBy('users.id')
			->orderBy(DB::raw('count(usercol.id)'), 'DESC')
			->limit(5)
			->get(['users.id', 'users.name', 'users.slug', DB::raw('count(usercol.id) as cols')]);
			
		$users_most_me = $user->cols()->count();
			
		$users_most_year = User::join('usercol','usercol.UserID', '=', 'users.id')
			->where('StravaNew','=',0)
			->where('ClimbedAt','>=',Carbon::now()->startOfYear())
			->groupBy('users.id')
			->orderBy(DB::raw('count(usercol.id)'), 'DESC')
			->limit(5)
			->get(['users.id', 'users.name', 'users.slug', DB::raw('count(usercol.id) as cols')]);
			
		$users_most_year_me = $user->cols()->wherePivot('ClimbedAt','>=',Carbon::now()->startOfYear())->count();
			
		$users_most_following = User::join('usercol','usercol.UserID', '=', 'users.id')
			->where('StravaNew','=',0)
			->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('useruser')
                      ->whereRaw('useruser.UserIDFollowing = users.id AND useruser.UserID = ' . Auth::user()->id);
            })
			->orWhere('users.id', '=', $user->id)
			->groupBy('users.id')
			->orderBy(DB::raw('count(usercol.id)'), 'DESC')
			->limit(5)
			->get(['users.id', 'users.name', 'users.slug', DB::raw('count(usercol.id) as cols')]);
			
		/* cols */
		$cols_most = Col::join('usercol','usercol.ColID', '=', 'cols.ColID')
			->where('StravaNew','=',0)
			->groupBy('cols.ColID')
			->orderBy(DB::raw('count(usercol.id)'), 'DESC')
			->limit(5)
			->get(['cols.ColIDString', 'cols.Col', 'cols.Country1', 'cols.Country2', DB::raw('count(usercol.id) as users')]);
			
		$cols_most_year = Col::join('usercol','usercol.ColID', '=', 'cols.ColID')
			->where('StravaNew','=',0)
			->where('ClimbedAt', '>=', Carbon::now()->startOfYear())
			->groupBy('cols.ColID')
			->orderBy(DB::raw('count(usercol.id)'), 'DESC')
			->limit(5)
			->get(['cols.ColIDString', 'cols.Col', 'cols.Country1', 'cols.Country2', DB::raw('count(usercol.id) as users')]);
		
		$cols_most_following = Col::join('usercol','usercol.ColID', '=', 'cols.ColID')
			->where('StravaNew','=',0)
			->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('useruser')
                      ->whereRaw('useruser.UserIDFollowing = usercol.UserID AND useruser.UserID = ' . Auth::user()->id);
            })
			->orWhere('usercol.UserID', '=', Auth::user()->id)
			->groupBy('cols.ColID')
			->orderBy(DB::raw('count(usercol.id)'), 'DESC')
			->limit(5)
			->get(['cols.ColIDString', 'cols.Col', 'cols.Country1', 'cols.Country2', DB::raw('count(usercol.id) as users')]);
			
		/* following */
		$following = Auth::user()->following()
			->join('usercol', 'useruser.UserIDFollowing', 'usercol.UserID')
			->where('StravaNew','=',0)
			->join('cols', 'usercol.ColID', 'cols.ColID')
			->select('users.id', 'users.name', 'users.slug', 'usercol.ColID', 'usercol.ClimbedAt', 'cols.ColIDString', 'cols.Col', 'cols.Country1', 'cols.Country2')
			->orderBy('usercol.climbedAt', 'DESC')
			->limit(50)
			->get();
			
		/* countries */
		$countries = Country::get();
		
		$col_count_user_max = 1;
			
		foreach($countries as $country){
			$col = Col::join('usercol','usercol.ColID', '=', 'cols.ColID')
				->where('StravaNew','=',0)
				->whereRaw('cols.Country1ID = ' . $country->CountryID)
				->orWhereRaw('cols.Country2ID = ' . $country->CountryID)
				->groupBy('cols.ColID')
				->orderBy(DB::raw('count(usercol.id)'), 'DESC')
				->first(['cols.ColIDString', 'cols.Col', 'cols.Country1', 'cols.Country2', DB::raw('count(usercol.id) as users')]);
			
			if ($col){
				$country->ColIDString = $col->ColIDString;
				$country->Col = $col->Col;
				$country->Height = $col->Height;
				$country->Users = $col->users;
			}
		}
		
		$countries = $countries->sortBy('Users')->reverse();

        return view('pages.users', compact('users_most', 'users_most_me', 'users_most_year', 'users_most_year_me', 'users_most_following', 'cols_most', 'cols_most_year', 'cols_most_following', 'countries', 'following', 'total', 'total_year', 'total_lastyear', 'users', 'users_following', 'users_followed', 'cols'));
    }
	
    public function cols(Request $request, $countryurl, $yearurl, $athleteurl)
    {		
		return $this->_most( $request, $countryurl, $yearurl, $athleteurl, "pages.userscols");
	}
	
    public function athletes(Request $request, $countryurl, $yearurl, $athleteurl)
    {		
		return $this->_most( $request, $countryurl, $yearurl, $athleteurl, "pages.usersathletes");
	}
	
    private function _most(Request $request, $countryurl, $yearurl, $athleteurl, $page)
    {		
		$redirect = false;
		$currentyear = date('Y');
	
        /* year */	
			
		$year_alltime = new \stdClass();
		$year_alltime->Year = "All Years"; 
		$year_alltime->URL = "all";
		$year_alltime->Title = "";
		
		$years = array($year_alltime);
		
		$minyear = Carbon::parse(UserCol::min('ClimbedAt'))->year;
		$maxyear = Carbon::now()->year;
		
		while ($maxyear >= $minyear){
			$year__ = new \stdClass();
			$year__->Year = $maxyear; 
			$year__->URL = $maxyear;
			$year__->Title = "in " . $maxyear;
			
			$years[] = $year__;
			
			$maxyear--;
		}		
				
		$year_current = null;
		foreach($years as $year){
			if ($year->URL == $yearurl){
				$year_current = $year;
				break;
			}
		}
		if ($year_current == null) {
			$year_current = $this->yearurl_default;
			$redirect = true;
		}
		
		/* country */	
		$countries = \App\Country::get();
		$countries = $countries->sortBy("Country");
		
		$country_all = new \stdClass();
		$country_all->CountryID = 0;
		$country_all->Country = "Europe"; 
		$country_all->URL = "eur"; 
		$country_all->Flag = "Europe"; 		
		$countries->prepend($country_all);	
		
		$country_current = null;
		foreach($countries as $country){
			if ($country->CountryID > 0){
				$country->URL = strtolower($country->CountryAbbr);
				$country->Flag = $country->Country;
			}
			
			if ($country->URL == $countryurl){
				$country_current = $country;
			}
		}
		if ($country_current == null) {
			$country_current = $this->countryurl_default;
			$redirect = true;
		}
		
		/* athletes */
		
		$athletes_all = new \stdClass();
		$athletes_all->Type = "All Athletes"; 
		$athletes_all->URL = "all";
		$athletes_all->Title = "";
		
		$athletes_following = new \stdClass();
		$athletes_following->Type = "Following"; 
		$athletes_following->URL = "following";
		$athletes_following->Title = "by athletes you are following";
		
		$athletes = array($athletes_all, $athletes_following);
				
		$athlete_current = null;
		foreach($athletes as $athlete){
			if ($athlete->URL == $athleteurl){
				$athlete_current = $athlete;
				break;
			}
		}
		if ($athlete_current == null) {
			$athlete_current = $this->athleteurl_default;
			$redirect = true;
		}
		
		if ($redirect){
			return \Redirect::to('athletes/cols/' . $this->countryurl_default . '/' . $this->yearurl_default . '/' . $this->athleteurl_default);
		}
		
		/* year filter */
		
		
		if ($page == "pages.userscols"){
			$set = Col::join('usercol', 'usercol.ColID', '=', 'cols.ColID')
				->where('StravaNew','=',0);
		
		} else if ($page == "pages.usersathletes"){
			$set = User::join('usercol', 'usercol.UserID', '=', 'users.id')
						->where('StravaNew','=',0)
						->join('cols', 'cols.ColID', '=', 'usercol.ColID');
		}
		
		if ($year_current->URL != "all"){
			$offset = $year_current->Year - $currentyear;
			$set = $set->where('ClimbedAt','<',Carbon::now()->addYear($offset + 1)->startOfYear())
						->where('ClimbedAt','>=',Carbon::now()->addYear($offset)->startOfYear());		
		}
		
		/* country filter */
		if ($country_current->CountryID > 0) {
			$this->CountryID = $country_current->CountryID;
			
			$set = $set->where(function ($query) {
				$query->where('Country1ID', '=', $this->CountryID)->orWhere('Country2ID', '=', $this->CountryID);
			});
		}  
		
		/* athletes filter */
		if ($athlete_current->URL == "following"){
				
			$set = $set->whereExists(function ($query) {
				$query->select(DB::raw(1))
					  ->from('useruser')
					  ->whereRaw('(useruser.UserIDFollowing = usercol.UserID AND useruser.UserID = ' . Auth::user()->id . ') OR users.id = ' . Auth::user()->id);
			});				
		}
				
		/* cols */
		
		if ($page == "pages.userscols"){
		
			$set = $set
					->groupBy('cols.ColID')
					->orderBy(DB::raw('count(usercol.id)'), 'DESC')
					->select('cols.ColID', 'cols.ColIDString', 'cols.Col', 'cols.Country1', 'cols.Country2', DB::raw('count(usercol.id) as count'));
		} else if ($page == "pages.usersathletes"){
		
			$set = $set			
					->groupBy('users.id')
					->orderBy(DB::raw('count(usercol.id)'), 'DESC')
					->select('users.id', 'users.name', 'users.slug', DB::raw('count(usercol.id) as count'));
			
		} else {
			return response(['success' => false], 404);
		}

		$set = $set->paginate(30);
		
        return view($page)
			->with('years',$years)
			->with('year',$year_current)
			->with('countries',$countries)
			->with('country',$country_current)
			->with('athletes',$athletes)
			->with('athlete',$athlete_current)
			->with('set',$set);
	}
	
	/* service */
	public function _search(Request $request)
    {
		$users = User::selectRaw("users.name, users.slug, useruser.id as following")
			->leftJoin('useruser', function ($join) {
				$join->on('useruser.UserIDFollowing', '=', 'users.id')
					->where('useruser.UserID', '=', Auth::user()->id);
			})
			->where("users.id", "<>", Auth::user()->id)
			->orderBy("users.name")->get();
		
		return response()->json($users);		
	}
}