<?php

namespace App\Http\Controllers\Map;

use App\Col;
use App\Country;
use App\Region;
use App\SubRegion;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
	/* views */
	public function map(Request $request)
	{
		return view('pages.map');
	}	
	
	public function country(Request $request, $country)
	{
		$country = \App\Country::where('CountryIDString',$country)->first();
	
		if (is_null($country))
		{
			return Redirect::to('/map');
		}
	
		return view('pages.map')
			->with('country',$country);
	}		
	
	public function region(Request $request, $region)
	{
		$region = \App\Region::where('RegionIDString',$region)->first();
	
		if (is_null($region))
		{
			return Redirect::to('/map');
		}
	
		return view('pages.map')
			->with('region',$region);
	}			
	
	public function subregion(Request $request, $subregion)
	{
		$subregion = \App\SubRegion::where('SubRegionIDString',$subregion)->first();
	
		if (is_null($subregion))
		{
			return Redirect::to('/map');
		}
	
		return view('pages.map')
			->with('subregion',$subregion);
	}			
	
	public function col(Request $request, $colidstring)
	{
		$col = \App\Col::where('ColIDString',$colidstring)->first();
	
		if (is_null($col))
		{
			return Redirect::to('/map');
		}
	
		return view('pages.map')
			->with('col',$col);
	}		
}