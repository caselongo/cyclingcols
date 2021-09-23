<?php

namespace App\Http\Controllers\Stats;

use App\Col;
use App\Country;
use App\Region;
use App\SubRegion;
use App\Stat;
use App\StatType;
use App\UserCol;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
	protected $stattypeurl_default = "all";
	protected $geourl_default = "eur";
	
	/* views */
	
	public function index_default(Request $request)
	{
		return \Redirect::to('stats/' . $this->stattypeurl_default . '/' . $this->geourl_default);	
	}
	
	public function index(Request $request, $stattypeurl, $geourl)
	{
		if (substr($stattypeurl, 0, 1) == '_'){
			$col = \App\Col::where('ColIDString',substr($stattypeurl, 1))->first();
			$geo_url = "";

			switch($geourl){
				case "c":
					$c = \App\Country::where('CountryID','=',$col->Country1ID)->first();
					if (!is_null($c)){
						$geo_url = $c->CountryIDString;
					}
					break;
				case "cc":
					$c = \App\Country::where('CountryID','=',$col->Country2ID)->first();
					if (!is_null($c)){
						$geo_url = $c->CountryIDString;
					}
					break;
				case "r":
					$r = \App\Region::where('RegionID','=',$col->Region1ID)->first();
					if (!is_null($r)){
						$geo_url = $r->RegionIDString;
					}
					break;
				case "rr":
					$r = \App\Region::where('RegionID','=',$col->Region2ID)->first();
					if (!is_null($r)){
						$geo_url = $r->RegionIDString;
					}
					break;
				case "s":
					$s = \App\SubRegion::where('SubRegionID','=',$col->SubRegion1ID)->first();
					if (!is_null($s)){
						$geo_url = $s->SubRegionIDString;
					}
					break;
				case "ss":
					$s = \App\SubRegion::where('SubRegionID','=',$col->SubRegion2ID)->first();
					if (!is_null($s)){
						$geo_url = $s->SubRegionIDString;
					}
					break;
			}

			if (is_null($geo_url)){
				return \Redirect::to('stats/' . $this->stattypeurl_default . '/' . $geourl_default);
			} else {
				return \Redirect::to('stats/' . $this->stattypeurl_default . '/' . $geo_url);
			}
		}

		$countryID = 0;
		$regionID = 0;
		$subregionID = 0;
		$geoID = 0;

		$sr = \App\SubRegion::where('SubRegionIDString','=',$geourl)->where('NrCols','>',0)->first();
		if (!is_null($sr)){
			$countryID = $sr->CountryID;
			$regionID = $sr->RegionID;
			$subregionID = $sr->SubRegionID;
			$geoID = $subregionID;
		} else {
			$r = \App\Region::where('RegionIDString','=',$geourl)->where('NrCols','>',0)->first();
			if (!is_null($r)){
				$countryID = $r->CountryID;
				$regionID = $r->RegionID;
				$geoID = $regionID;
			} else {
				$c = \App\Country::where('CountryIDString','=',$geourl)->where('NrCols','>',0)->first();
				if (!is_null($c)){
					$countryID = $c->CountryID;
					$geoID = $countryID;
				}
			}
		}

		/* stattype */	
		$stattypes = \App\StatType::get();
		
		$stattype_all = new \stdClass();
		$stattype_all->StatTypeID = 0;
		$stattype_all->StatType = "(all stats)"; 
		$stattype_all->URL = "all"; 
		$stattype_all->Icon = ""; 
		$stattype_all->Description = "";
		$stattype_all->Type = 0; 	 
		$stattype_all->IsPrimary = 0; 	 					
		$stattypes->prepend($stattype_all);	
		
		$stattype_current = null;
		foreach($stattypes as $stattype){
			if ($stattype->URL == $stattypeurl){
				$stattype_current = $stattype;
				break;
			}
		}
		
		/* country */	
		$countries = \App\Country::where('NrCols','>',0)->get();
		$countries = $countries->sortBy("Country");
		
		$country_all = new \stdClass();
		$country_all->CountryID = 0;
		$country_all->Country = "Europe"; 
		$country_all->URL = "europe"; 
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
		
		if (is_null($stattype_current) && is_null($country_current)){
			return \Redirect::to('stats/' . $this->stattypeurl_default . '/' . $geourl_default);
		} else if (is_null($stattype_current)){
			return \Redirect::to('stats/' . $this->stattypeurl_default . '/' . $geourl);
		} else if (is_null($country_current)){
			return \Redirect::to('stats/' . $stattypeurl . '/' . $geourl_default);		
		}

		/* stats */	
		if ($stattype_current->StatTypeID > 0) {
			$stats = \App\Stat::where('StatTypeID', $stattype_current->StatTypeID)->where('GeoID', $geoID)->orderBy('Rank','ASC')->get();
		} else {
			$stats = \App\Stat::where('GeoID', $geoID)->where('Rank','<=', 10)->orderBy('StatTypeID','ASC')->orderBy('Rank','ASC')->get();
		}
		
		$user = Auth::user();
		
		foreach($stats as $stat){
			if ($stat->ColID > 0){
				$col = \App\Col::where('ColID',$stat->ColID)->first();
					
				if ($col != null){
					$stat->Height = $col->Height;
					$stat->CoverPhotoPosition = $col->CoverPhotoPosition;
					$stat->Latitude = $col->Latitude;
					$stat->Longitude = $col->Longitude;
					$stat->Climbed = false;
				}
				
				if($user != null){
					$usercol = $user->cols()->where('cols.ColID',$col->ColID)->first();
					
					if ($usercol){
						$stat->Climbed = true;
					}
				}
			}
		}	
		
		/* countries */
		$stats_countries = null;
		
		if ($stattype_current->StatTypeID > 0){
			$stats_countries = DB::table('stats')
					->join('countries','stats.GeoID', '=', 'countries.CountryID')
					->select('stats.*')
					->where('stats.StatTypeID', '=', $stattype_current->StatTypeID)
					->where('stats.GeoID', '>', 0)
					->where('stats.Rank', 1)
					->orderBy('stats.Value', 'DESC')
					->get();
		}	
		
		/* regions */
		$stats_regions = null;
		
		if ($stattype_current->StatTypeID > 0){
			$stats_regions = DB::table('stats')
					->join('regions','stats.GeoID', '=', 'regions.RegionID')
					->select('stats.*')
					->where('regions.CountryID', '=', $countryID)
					->where('stats.StatTypeID', '=', $stattype_current->StatTypeID)
					->where('stats.GeoID', '>', 0)
					->where('stats.Rank', 1)
					->orderBy('stats.Value', 'DESC')
					->get();
		}
		
		/* subregions */
		$stats_subregions = null;
		
		if ($stattype_current->StatTypeID > 0){
			$stats_subregions = DB::table('stats')
					->join('subregions','stats.GeoID', '=', 'subregions.SubRegionID')
					->select('stats.*')
					->where('subregions.CountryID', '=', $countryID)
					->where('stats.StatTypeID', '=', $stattype_current->StatTypeID)
					->where('stats.GeoID', '>', 0)
					->where('stats.Rank', 1)
					->orderBy('stats.Value', 'DESC')
					->get();
		}

		$htmlCountries = view('sub.statsgeo')
		->with('stattype', $stattype)
		->with('stattype_current', $stattype_current)
		->with('stats', $stats_countries)
		->with('geotype', 'Country')
		->render();

		$htmlRegions = view('sub.statsgeo')
		->with('stattype', $stattype)
		->with('stattype_current', $stattype_current)
		->with('stats', $stats_regions)
		->with('geotype', 'Region')
		->render();

		$htmlSubRegions = view('sub.statsgeo')
		->with('stattype', $stattype)
		->with('stattype_current', $stattype_current)
		->with('stats', $stats_subregions)
		->with('geotype', 'Province')
		->render();
		
        return view('pages.stats')
			->with('geourl',$geourl)
			->with('stattypes',$stattypes)
			->with('stattype',$stattype_current)
			->with('countries',$countries)
			->with('country',$country_current)
			->with('regions',$regions)
			->with('region',$region_current)
			->with('subregions',$subregions)
			->with('subregion',$subregion_current)
			->with('stats',$stats)
			->with('stats_countries',$htmlCountries)
			->with('stats_regions',$htmlRegions)
			->with('stats_subregions',$htmlSubRegions);
	}
	
	/* service */
	
    public function _top(Request $request, $countryurl)
    {
		$countryid = 0;
		if ($countryurl != "all"){
			$country = Country::where('CountryIDString',$countryurl)->first();
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