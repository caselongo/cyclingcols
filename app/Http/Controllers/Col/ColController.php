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
	/*  views */
	public function col(Request $request, $colIDString)
	{
		$col = \App\Col::where('ColIDString',$colIDString)->first();
		
		if (is_null($col))
		{
			return Redirect::to('/');
		}

		$profiles = \App\Profile::where('ColID',$col->ColID)->get();
		
		$user = Auth::user();
		$usercol = null;
		if($user != null)
		{
			$usercol = $user->cols()->where('cols.ColID','=',$col->ColID)->first();
		}
		
		return view('pages.col')
			->with('col',$col)
			->with('profiles',$profiles)
			->with('usercol',$usercol);
	}	
	
	/*  service */
    public function _user(Request $request, $colIDString)
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
	
    public function _users(Request $request, $colIDString)
    {
		$col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }
		
		$users = $col->users();
		
		$users = $users->orderBy('pivot_CreatedAt', 'Desc')->limit(10)->get();


		$returnHTML = view('sub.colusers')->with('users', $users)->render();
		return response()->json(array('success' => true, 'html'=>$returnHTML));		
		//return response()->json($users);
    }
	
    public function _nearby(Request $request, $colIDString)
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
	
    public function _first(Request $request, $colIDString)
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
	
	private function _top($top){
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
	
    public function _topprofile(Request $request, $fileName)
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
					 
		return $this->_top($top);	
	}
	
    public function _topcol(Request $request, $colIDString)
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
		
		return $this->_top($top);
    }
	
    public function _profile(Request $request, $fileName)
    {
		$profile = Profile::where('FileName', $fileName)->first();

        if ($profile == null) {
            return response(['success' => false], 404);
        }
		
		$profile->DistanceFormatted = formatStat(1,$profile->Distance);
		$profile->DistanceCat = getStatCat(1,$profile->Distance);
		$profile->HeightDiffFormatted = formatStat(2,$profile->HeightDiff);
		$profile->HeightDiffCat = getStatCat(2,$profile->HeightDiff);
		$profile->AvgPercFormatted = formatStat(3,$profile->AvgPerc);
		$profile->AvgPercCat = getStatCat(3,$profile->AvgPerc);
		$profile->MaxPercFormatted = formatStat(4,$profile->MaxPerc);
		$profile->MaxPercCat = getStatCat(4,$profile->MaxPerc);
		$profile->ProfileIdxFormatted = formatStat(5,$profile->ProfileIdx);
		$profile->ProfileIdxCat = getStatCat(5,$profile->ProfileIdx);

		return response()->json($profile);
    }
}