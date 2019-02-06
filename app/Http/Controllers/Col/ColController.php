<?php

namespace App\Http\Controllers\Col;

use App\Col;
use App\Passage;
use App\Stat;
use App\StatType;
use App\Country;
use App\Profile;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ColController extends Controller
{

    public function rating(Request $request, $colIDString)
    {
		$user = Auth::user();
        $col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }
		
		$userID = 0;
		if ($user != null){
			$userID = $user->id;
		}
		
		$select = "SUM(CASE WHEN Done = 1 THEN 1 ELSE 0 END) as done_count,";
		$select .= "MAX(CASE WHEN UserID = " . $userID . " THEN Done ELSE 0 END) as done,";
		$select .= "SUM(CASE WHEN Rating > 0 THEN 1 ELSE 0 END) as rating_count,";
		$select .= "MAX(CASE WHEN UserID = " . $userID . " THEN Rating ELSE 0 END) as rating,";
		$select .= "AVG(CASE WHEN Rating > 0 THEN Rating ELSE NULL END) as rating_avg";
		
		$ratings = DB::table('usercol')
                     ->select(DB::raw($select))
                     ->where('ColID', $col->ColID)
                     ->get();

		return response()->json($ratings);
    }
	
    public function nearby(Request $request, $colIDString)
    {
		$col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }
		
		$colsnearby = DB::table('colsnearby')
                     ->select(DB::raw('ColIDString, Col, Latitude, Longitude, Distance, Direction'))
                     ->where('MainColID', $col->ColID)
					 ->orderBy('Distance', 'ASC')
                     ->get();

		return response()->json($colsnearby);
    }
	
    public function first(Request $request, $colIDString)
    {
		$col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }
		
		$first = Passage::where('ColID', $col->ColID)
					 ->orderBy('Edition', 'DESC')
					 ->orderBy('EventID', 'DESC')
                     ->get();

		return response()->json($first);
    }
	
	private function top($top){
		/* get urls*/
		$stattypes = StatType::get();
		$countries = Country::get();
		
		foreach($top as $t){
			foreach($stattypes as $st){
				if ($st->StatTypeID == $t->StatTypeID){
					$t->stat_url = $st->URL;
					break;
				}
			}	
			
			if ($t->GeoID == 0){
				$t->country_url = "all";
			} else {
				foreach($countries as $c){
					if ($c->CountryID == $t->GeoID){
						$t->country_url = strtolower($c->CountryAbbr);
						break;
					}
				}	
			}
		}

		return response()->json($top);		
	}
	
    public function topprofile(Request $request, $fileName)
    {
		$profile = Profile::where('FileName', $fileName)->first();

        if ($profile == null) {
            return response(['success' => false], 404);
        }
		
		$top = Stat::where('ProfileID', $profile->ProfileID)
					 ->orderBy('ProfileID', 'ASC')
					 ->orderBy('StatTypeID', 'ASC')
					 ->orderBy('GeoID', 'ASC')
					 ->orderBy('Rank', 'ASC')
                     ->get();	
					 
		return $this->top($top);	
	}
	
    public function topcol(Request $request, $colIDString)
    {
		$col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }
		
		$top = Stat::where('ColID', $col->ColID)
					 ->orderBy('ProfileID', 'ASC')
					 ->orderBy('StatTypeID', 'ASC')
					 ->orderBy('GeoID', 'ASC')
					 ->orderBy('Rank', 'ASC')
                     ->get();
		
		return $this->top($top);
    }
	
    public function profile(Request $request, $fileName)
    {
		$profile = Profile::where('FileName', $fileName)->first();

        if ($profile == null) {
            return response(['success' => false], 404);
        }
		
		$profile->Distance = formatStat(1,$profile->Distance);
		$profile->DistanceCat = getStatCat(1,$profile->Distance);
		$profile->HeightDiff = formatStat(2,$profile->HeightDiff);
		$profile->HeightDiffCat = getStatCat(2,$profile->HeightDiff);
		$profile->AvgPerc = formatStat(3,$profile->AvgPerc);
		$profile->AvgPercCat = getStatCat(3,$profile->AvgPerc);
		$profile->MaxPerc = formatStat(4,$profile->MaxPerc);
		$profile->MaxPercCat = getStatCat(4,$profile->MaxPerc);
		$profile->ProfileIdx = formatStat(5,$profile->ProfileIdx);
		$profile->ProfileIdxCat = getStatCat(5,$profile->ProfileIdx);

		return response()->json($profile);
    }
}