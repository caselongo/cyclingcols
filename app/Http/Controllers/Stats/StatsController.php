<?php

namespace App\Http\Controllers\Stats;

use App\Col;
use App\Country;
use App\Stat;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function top(Request $request, $countryurl)
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

		return response()->json($top);
    }
}