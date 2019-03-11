<?php

namespace App\Http\Controllers\Stats;

use App\Col;
use App\Country;
use App\Stat;
use App\StatType;
use App\UserCol;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Stats2Controller extends Controller
{
	protected $stattypeurl_default = "all";
	protected $countryurl_default = "eur";
	
	/* views */
	
	public function index_default(Request $request)
	{
		return \Redirect::to('stats2/' . $this->stattypeurl_default . '/' . $this->countryurl_default);	
	}
	
	public function index(Request $request, $stattypeurl, $countryurl)
	{
		/* stattype */	
		$stattypes = \App\StatType::get();
		
		$stattype_all = new \stdClass();
		$stattype_all->StatTypeID = 0;
		$stattype_all->StatType = "All"; 
		$stattype_all->URL = "all"; 
		$stattype_all->Icon = ""; 
		$stattype_all->Description = ""; 				
		$stattypes->prepend($stattype_all);	
		
		$stattype_current = null;
		foreach($stattypes as $stattype){
			if ($stattype->URL == $stattypeurl){
				$stattype_current = $stattype;
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
		
		if (is_null($stattype_current) && is_null($country_current)){
			return \Redirect::to('stats2/' . $this->stattypeurl_default . '/' . $countryurl_default);
		} else if (is_null($stattype_current)){
			return \Redirect::to('stats2/' . $this->stattypeurl_default . '/' . $countryurl);
		} else if (is_null($country_current)){
			return \Redirect::to('stats2/' . $stattypeurl . '/' . $countryurl_default);		
		}

		if ($stattype_current->StatTypeID > 0) {
			$stats = \App\Stat::where('StatTypeID', $stattype_current->StatTypeID)->where('GeoID', $country_current->CountryID)->orderBy('Rank','ASC')->get();
		} else {
			$stats = \App\Stat::where('GeoID', $country_current->CountryID)->where('Rank','<=', 10)->orderBy('StatTypeID','ASC')->orderBy('Rank','ASC')->get();
		}
		
		$user = Auth::user();
		
		foreach($stats as $stat){
			$col = \App\Col::where('ColID',$stat->ColID)->first();
				
			if ($col != null){
				$stat->Height = $col->Height;
				$stat->CoverPhotoPosition = $col->CoverPhotoPosition;
				$stat->Latitude = $col->Latitude;
				$stat->Longitude = $col->Longitude;
				$stat->Climbed = false;
			}
			
			if($user != null){
				$usercol = \App\UserCol::where('ColID',$col->ColID)->first();
				
				if ($usercol){
					$stat->Climbed = true;
				}
			}
		}	
		
		/* other countries */
		$stats_other = null;
		
		if ($stattype_current->StatTypeID > 0){
			$stats_other = \App\Stat::where('StatTypeID', $stattype_current->StatTypeID)->where('GeoID', '>', 0)->where('Rank', 1)->orderBy('Value','DESC')->get();
		}
		
        return view('pages.stats2')
			->with('stattypes',$stattypes)
			->with('stattype',$stattype_current)
			->with('country',$country_current)
			->with('stats',$stats)
			->with('stats_other',$stats_other)
			->with('countries',$countries);
	}
	
	/* service */
	
    public function _top(Request $request, $countryurl)
    {
		$countryid = 0;
		if ($countryurl != "all"){
			$country = Country::where('CountryAbbr',$countryurl)->first();
			if ($country != null){
				$countryid = $country->CountryID;
			}
		}
		
		$top = Stat::where('GeoID',$countryid)
				->where('Rank', 1)
				->get();
				
		foreach($top as $t){
			$t->StatType = $t->stattype;
		}
		
		$returnHTML = view('sub.statstop')
			->with('top', $top)
			->render();
		
		return response()->json(array('success' => true, 'html' => $returnHTML));
    }
}