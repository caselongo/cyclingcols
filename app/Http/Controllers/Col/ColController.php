<?php

namespace App\Http\Controllers\Col;

use App\Col;
use App\Passage;
use App\Stat;
use App\StatType;
use App\Country;

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
	
    public function top(Request $request, $colIDString)
    {
		$col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }
		
		$top = Stat::where('ColID', $col->ColID)
					 ->orderBy('StatTypeID', 'ASC')
					 ->orderBy('GeoID', 'ASC')
					 ->orderBy('Rank', 'ASC')
                     ->get();
		
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
			
			foreach($countries as $c){
				if ($c->CountryID == $t->GeoID){
					$t->country_url = strtolower($c->CountryAbbr);
					break;
				}
			}	
		}

		return response()->json($top);
    }
}