<?php

namespace App\Http\Controllers\User;

use App\Col;
use App\Country;
use App\Region;
use App\SubRegion;
use App\User;

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
	
    public function index_default(Request $request)
    {
        $user = Auth::user();
		
		return \Redirect::to('athlete/' . $user->slug);	
	}
	
    public function index(Request $request, $slug)
    {
        $user = User::where('slug', $slug)->first();
		
		if ($user == null){
            return response(['success' => false], 404);
		}

        $_climbed = $user->cols();
		
		$_climbed_year = clone $_climbed;
		$_climbed_year->wherePivot('ClimbedAt','>=',Carbon::now()->startOfYear());
		
		$_climbed_lastyear = clone $_climbed;
		$_climbed_lastyear->wherePivot('ClimbedAt','<',Carbon::now()->startOfYear())->wherePivot('ClimbedAt','>=',Carbon::now()->addYear(-1)->startOfYear());

		/* climbed recently */	
        $climbed_count = $_climbed->count();
		
		$climbed = clone $_climbed;
		$climbed = $climbed->orderBy('pivot_ClimbedAt', 'desc')->limit(5)->get();

		/* claimed recently */	
        $claimed = clone $_climbed;
		$claimed = $claimed->orderBy('pivot_CreatedAt', 'desc')->limit(5)->get();

		/* highest */	
        $highest = clone $_climbed;
		$highest = $highest->orderBy('Height', 'desc')->limit(5)->get();

		/* climbed this year */	
		$climbed_year_count = $_climbed_year->count();

		/* climbed last year */	
		$climbed_lastyear_count = $_climbed_lastyear->count();
		
		/* countries */
		$countries = Country::where('NrCols', '>', '0')->get();
		
		$col_count_user_max = 1;
			
		foreach($countries as $country){
			$c = clone $_climbed;
			$cy = clone $_climbed_year;
			$cy = $cy->wherePivot('ClimbedAt','>=',Carbon::now()->startOfYear());
			
			$country->URL = strtolower($country->CountryIDString);
			$country->col_count = $country->col_count();
			$country->col_count_user = $c->where('Country1ID', $country->CountryID)->count() + $c->where('Country2ID', $country->CountryID)->count();
			$country->col_count_user_year = $cy->where('Country1ID', $country->CountryID)->count() + $cy->where('Country2ID', $country->CountryID)->count();
			
			if ($country->col_count_user > $col_count_user_max){
				$col_count_user_max = $country->col_count_user;
			}

			if ($country->col_count == 0) {
				$country->perc_climbed = 0;
			} else {
				$country->perc_climbed = round(($country->col_count_user/$country->col_count) * 100,0);
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
		$countries = $countries->sortBy(function($country){
			return substr("000000" . strval(10000 - $country->col_count_user), -6) . $country->Country;
		});
		
		/* regions */
		$regions = Region::where('NrCols', '>', '0')->get();
		
		foreach($regions as $region){
			$c = clone $_climbed;
			
			$region->URL = strtolower($region->RegionIDString);
			$region->col_count = $region->col_count();
			$col_count_user = $c->whereRaw($region->RegionID . ' IN (Region1ID, Region2ID)')->count();
			
			if ($region->col_count == 0) {
				$region->perc_climbed = 0;
			} else {
				$region->perc_climbed = round(($col_count_user/$region->col_count) * 100,0);
			}
		}
		$regions = $regions->sortBy(function($region){
			return -1 * ($region->perc_climbed * 1000 + $region->col_count);
		});
		
		$total_count = Col::count();
		
		if ($climbed_count == $total_count){
			$width_climbed = 1;
			$perc_climbed = "100";
		} else if ($climbed_count > 0){
			$width_climbed = 0.1 + round($climbed_count/$total_count,1) * 0.9;
			$perc_climbed = round(($climbed_count/$total_count) * 100,1);
			if (floor($perc_climbed) > 0 && ceil($perc_climbed) < 100){
				$perc_climbed = round(($climbed_count/$total_count) * 100,0);
			}
			$perc_climbed = strval($perc_climbed);
		} else {
			$width_climbed = 0;
			$perc_climbed = "0";
		}
		$width_total = 1 - $width_climbed;
		
		//$countries = $countries->sortByDesc('col_count_user');

        return view('pages.user', compact('user', 'climbed', 'climbed_count', 'climbed_year_count', 'climbed_lastyear_count', 'claimed', 'countries', 'regions', 'highest', 'width_total', 'width_climbed', 'perc_climbed'));
    }
	
    public function cols_default(Request $request, $slug)
	{
		return \Redirect::to('athlete/' . $slug . '/cols/' . $this->countryurl_default . '/' . $this->sorttypeurl_default);
	}
	
    public function cols(Request $request, $slug, $geourl, $sorttypeurl)
    {		
        /* sorttype */	
			
		$sorttype_climbed = new \stdClass();
		$sorttype_climbed->SortType = "Most Recently Climbed"; 
		$sorttype_climbed->URL = "climbed"; 
		//$sorttype_climbed->DateField = "ClimbedAt";	
		$sorttype_climbed->NameField = "Col";	
		$sorttype_climbed->SortField = "pivot_ClimbedAt";	
		$sorttype_climbed->SortDirection = "DESC";	
		
		$sorttype_claimed = new \stdClass();
		$sorttype_claimed->SortType = "Most Recently Claimed"; 
		$sorttype_claimed->URL = "claimed";
		//$sorttype_claimed->DateField = "CreatedAt";
		$sorttype_claimed->NameField = "Col";	
		$sorttype_claimed->SortField = "pivot_CreatedAt";
		$sorttype_claimed->SortDirection = "DESC";	
		
		$sorttype_alphabetical = new \stdClass();
		$sorttype_alphabetical->SortType = "Alphabetically"; 
		$sorttype_alphabetical->URL = "alphabetical";
		//$sorttype_alphabetical->DateField = "ClimbedAt";
		$sorttype_alphabetical->NameField = "ColSort";	
		$sorttype_alphabetical->SortField = "ColSort";
		$sorttype_alphabetical->SortDirection = "ASC";	
		
		$sorttype_elevation = new \stdClass();
		$sorttype_elevation->SortType = "Elevation"; 
		$sorttype_elevation->URL = "elevation";
		//$sorttype_alphabetical->DateField = "ClimbedAt";
		$sorttype_elevation->NameField = "Col";	
		$sorttype_elevation->SortField = "Height";
		$sorttype_elevation->SortDirection = "DESC";	
		
		$sorttypes = array($sorttype_climbed, $sorttype_claimed, $sorttype_alphabetical, $sorttype_elevation);
				
		$sorttype_current = null;
		foreach($sorttypes as $sorttype){
			if ($sorttype->URL == $sorttypeurl){
				$sorttype_current = $sorttype;
				break;
			}
		}

		$countryID = 0;
		$regionID = 0;
		$subregionID = 0;
		$geoID = 0;

		$sr = \App\SubRegion::whereRaw('LOWER(SubRegionIDString) = "' . $geourl . '"')->where('NrCols','>',0)->first();
		if (!is_null($sr)){
			$countryID = $sr->CountryID;
			$regionID = $sr->RegionID;
			$subregionID = $sr->SubRegionID;
			$geoID = $subregionID;
		} else {
			$r = \App\Region::whereRaw('LOWER(RegionIDString) = "' . $geourl . '"')->where('NrCols','>',0)->first();
			if (!is_null($r)){
				$countryID = $r->CountryID;
				$regionID = $r->RegionID;
				$geoID = $regionID;
			} else {
				$c = \App\Country::whereRaw('LOWER(CountryIDString) = "' . $geourl . '"')->where('NrCols','>',0)->first();
				if (!is_null($c)){
					$countryID = $c->CountryID;
					$geoID = $countryID;
				}
			}
		}
		
		/* country */	
		$countries = \App\Country::where('NrCols', '>', 0)->get();
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
				$country->URL = strtolower($country->CountryIDString);
				$country->Flag = $country->CountryIDString;
			}
			
			if ($country->CountryID == $countryID){
				$country_current = $country;
			}
		}
		
		/* region */
		$regions = \App\Region::where('CountryID','=',$countryID)->where('NrCols','>',0)->get();
		$regions = $regions->sortBy("Region");
		
		$region_all = new \stdClass();
		$region_all->RegionID = 0;
		$region_all->Region = "(all regions)"; 
		$region_all->URL = $country_current->URL; 		
		$regions->prepend($region_all);	
		
		$region_current = $region_all;
		foreach($regions as $region){
			if ($region->RegionID > 0){
				$region->URL = strtolower($region->RegionIDString);
			}
			
			if ($region->RegionID == $regionID){
				$region_current = $region;
			}
		}
		
		/* subregion */	
		$subregions = \App\SubRegion::where('CountryID','=',$countryID)->where('NrCols','>',0)->get();
		$subregions = $subregions->sortBy("SubRegion");
		
		$subregion_all = new \stdClass();
		$subregion_all->SubRegionID = 0;
		$subregion_all->SubRegion = "(all provinces)"; 
		$subregion_all->URL = $country_current->URL; 		
		$subregions->prepend($subregion_all);	
		
		$subregion_current = $subregion_all;
		foreach($subregions as $subregion){
			if ($subregion->SubRegionID > 0){
				$subregion->URL = strtolower($subregion->SubRegionIDString);
			}
			
			if ($subregion->SubRegionID == $subregionID){
				$subregion_current = $subregion;
			}
		}
		
		if (is_null($sorttype_current) && is_null($country_current)){
			return \Redirect::to('athlete/cols/' . $slug . '/' . $this->countryurl_default . '/' . $this->sorttypeurl_default);
		} else if (is_null($sorttype_current)){
			return \Redirect::to('athlete/cols/' . $slug . '/' . $geourl . '/' . $this->sorttypeurl_default);
		} else if (is_null($country_current)){
			return \Redirect::to('athlete/cols/' . $slug . '/' . $this->countryurl_default . '/' . $sorttypeurl);		
		}

        $user = User::where('slug', $slug)->first();
				
		$cols = $user->cols()->select("cols.ColIDString", "cols.Col", "cols.ColSort", "cols.Country1ID", "cols.Country1", "cols.Country2ID", "cols.Country2", "cols.Height");
		
		if ($subregion_current->SubRegionID > 0) {
			$this->SubRegionID = $subregion_current->SubRegionID;
			
			$cols = $cols->where(function ($q) {
				$q->where('SubRegion1ID', '=', $this->SubRegionID)->orWhere('SubRegion2ID', '=', $this->SubRegionID);
			});
		} else if ($region_current->RegionID > 0) {
			$this->RegionID = $region_current->RegionID;
			
			$cols = $cols->where(function ($q) {
				$q->where('Region1ID', '=', $this->RegionID)->orWhere('Region2ID', '=', $this->RegionID);
			});
		} else if ($country_current->CountryID > 0) {
			$this->CountryID = $country_current->CountryID;
			
			$cols = $cols->where(function ($q) {
				$q->where('Country1ID', '=', $this->CountryID)->orWhere('Country2ID', '=', $this->CountryID);
			});
		} 

		$cols = $cols->orderBy($sorttype_current->SortField, $sorttype_current->SortDirection)->paginate(30);
		
		$diff_years = $user->cols()
			->selectRaw("*, YEAR(CURRENT_DATE()) - YEAR(usercol.ClimbedAt) AS years")
			->whereRaw("MONTH(usercol.ClimbedAt) = MONTH(CURRENT_DATE())")
			->whereRaw("DAY(usercol.ClimbedAt) = DAY(CURRENT_DATE())")
			/*->orderBy("pivot_ClimbedAt","DESC")*/
			->orderByRaw("RAND()")
			->get();
		
		$diff_days = $user->cols()
			->selectRaw("*, DATEDIFF(CURRENT_DATE(),usercol.ClimbedAt) AS days")
			->whereRaw("DATEDIFF(CURRENT_DATE(),usercol.ClimbedAt) IN (100,1000)")
			/*->orderBy("pivot_ClimbedAt","DESC")*/
			->orderByRaw("RAND()")
			->get();
		
		$diff_months = $user->cols()
			->selectRaw("*, MONTH(CURRENT_DATE()) - MONTH(usercol.ClimbedAt) AS months")
			->whereRaw("DAY(usercol.ClimbedAt) = DAY(CURRENT_DATE())")
			->whereRaw("DATEDIFF(CURRENT_DATE(),usercol.ClimbedAt) < 365")
			/*->orderBy("pivot_ClimbedAt","DESC")*/
			->orderByRaw("RAND()")
			->get();
		
        return view('pages.usercols')
			->with('user',$user)
			->with('geourl',$geourl)
			->with('sorttypes',$sorttypes)
			->with('sorttype',$sorttype_current)
			->with('countries',$countries)
			->with('country',$country_current)
			->with('regions',$regions)
			->with('region',$region_current)
			->with('subregions',$subregions)
			->with('subregion',$subregion_current)
			->with('cols',$cols)
			->with('diff_years',$diff_years)
			->with('diff_days',$diff_days)
			->with('diff_months',$diff_months);
	}
	
	/* service */
	
	public function _following(Request $request, $slug)
    {
		$userid = User::where('slug', $slug)->first()->id;
		$user = Auth::user()->following()->wherePivot('UserIDFollowing', $userid)->first();
		
		$returnHTML = view('sub.following')
			->with('user', $user)
			->render();
		
		return response()->json(array('success' => true, 'html' => $returnHTML));
    }
	
	public function _follow(Request $request, $slug)
    {
		$userid = User::where('slug', $slug)->first()->id;
		$user = Auth::user()->following()->attach($userid);
		
        return response(['success' => true], 200);
    }
	
	public function _unfollow(Request $request, $slug)
    {
		$userid = User::where('slug', $slug)->first()->id;
		$user = Auth::user()->following()->detach($userid);
		
        return response(['success' => true], 200);
    }

}