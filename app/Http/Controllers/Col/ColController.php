<?php

namespace App\Http\Controllers\Col;

use App\Col;
use App\ColsNearby;
use App\Passage;
use App\Stat;
use App\StatType;
use App\Country;
use App\Region;
use App\SubRegion;
use App\Profile;
use App\UserCol;
use App\LList;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
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
		
		return view('pages.col')
			->with('col',$col)
			->with('profiles',$profiles);
	}	
	
	/*  service */
    public function _user(Request $request, $colIDString)
    {
		$user = Auth::user();
        $col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }
		
		$climbed = false;
		$climbedAt = null;
		$climbedAtText = null;
		$climbedAtStravaActivityIDs = null;
		
		if ($user != null){
			$usercol = UserCol::where('ColID', $col->ColID)->where('UserID', $user->id)->first();
			if ($usercol){
				$climbed = true;
				$climbedAt = $usercol->ClimbedAt;
				//$climbedAtText = $usercol->ClimbedAtText;
				$climbedAtText = Carbon::parse($usercol->ClimbedAt)->format('d M Y');
				$climbedAtStravaActivityIDs = $usercol->StravaActivityIDs;
			}
		}
		
		return response()->json(array('climbed' => $climbed, 'climbedAt' => $climbedAt, 'climbedAtText' => $climbedAtText, 'climbedAtStravaActivityIDs' => $climbedAtStravaActivityIDs));		
    }
	
    public function _users(Request $request, $colIDString)
    {
		$col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }
		
		$users = $col->users();
		
		$count = $users->count();
		
		$users = $users->orderBy('pivot_ClimbedAt', 'Desc')->limit(5)->get();

		$returnHTML = view('sub.colusers')
			->with('users', $users)
			->with('count', $count)
			->render();
		
		return response()->json(array('success' => true, 'html' => $returnHTML));	
    }

    public function _user_save(Request $request, $colIDString)
    {
		$user = Auth::user();

        if ($user == null) {
            return response(['success' => false], 404);
        }
		
        $col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }
		
		$climbedAt = Input::get('climbedAt');
		$c = null;
		if ($climbedAt){
			$c = Carbon::parse($climbedAt);
		}

        $array = [];

        if ($user->cols()->where('cols.ColID', $col->ColID)->first() != null) {
            $array['UpdatedAt'] = Carbon::now('Europe/Amsterdam');
			$array['ClimbedAt'] = $c;
            $user->cols()->updateExistingPivot($col->ColID, $array, false);
        } else {
            $array['UpdatedAt'] = Carbon::now('Europe/Amsterdam');
            $array['CreatedAt'] = Carbon::now('Europe/Amsterdam');
			$array['ClimbedAt'] = $c;
            $user->cols()->attach($col->ColID, $array);
        }

        return response(['success' => true], 200);
    }

    public function _user_delete(Request $request, $colIDString)
    {
		$user = Auth::user();

        if ($user == null) {
            return response(['success' => false], 404);
        }
		
        $col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }
		
		$user->cols()->detach($col->ColID);

        return response(['success' => true], 200);
    }
	
    public function _nearby(Request $request, $colIDString)
    {
		$col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }
		
		$nearby = ColsNearby::where('MainColID', $col->ColID)
					 ->orderBy('Distance', 'ASC')
                     ->get();

		$returnHTML = view('sub.colnearby')
			->with('nearby', $nearby)
			->render();
		
		return response()->json(array('success' => true, 'html'=>$returnHTML));	
    }
	
    public function _first_all(Request $request, $colIDString)
	{
		return $this->_first($request, $colIDString, null);
	}
	
	
    public function _first(Request $request, $colIDString, $limit)
    {
		$col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }
		
		$first = Passage::where('ColID', $col->ColID);
		
		$count = $first->count();
		
		$first = $first->orderBy('Edition', 'DESC')
					 ->orderBy('EventID', 'DESC');
					 
		if ($limit != null){
			$first = $first->limit($limit);
		}
		
		$first = $first->get();

		$returnHTML = view('sub.colfirst')
			->with('first', $first)
			->with('count', $count)
			->render();
		
		return response()->json(array('success' => true, 'html'=>$returnHTML, 'count' => $count));	
    }
	
	private function _top($top){
		/* get urls*/
		$stattypes = StatType::get();
		
		foreach($top as $t){
			foreach($stattypes as $st){
				if ($st->StatTypeID == $t->StatTypeID){
					$t->stat_url = $st->URL;
					break;
				}
			}	
			
			$t->geo_url = "";
			if ($t->GeoID == 0){
				$t->geo_url = "eur";
			} 
			if ($t->geo_url == "") {
				$countries = Country::get();
				foreach($countries as $c){
					if ($c->CountryID == $t->GeoID){
						$t->geo_url = strtolower($c->CountryIDString);
						break;
					}
				}	
			}
			if ($t->geo_url == "") {
				$regions = Region::get();
				foreach($regions as $r){
					if ($r->RegionID == $t->GeoID){
						$t->geo_url = strtolower($r->RegionIDString);
						break;
					}
				}	
			}
			if ($t->geo_url == "") {	
				$subregions = SubRegion::get();
				foreach($subregions as $sr){
					if ($sr->SubRegionID == $t->GeoID){
						$t->geo_url = strtolower($sr->SubRegionIDString);
						break;
					}
				}	
			}
		}

		return response()->json($top);		
	}
	
    public function _profile_top(Request $request, $fileName)
    {
		$profile = Profile::where('FileName', $fileName)->first();

        if ($profile == null) {
            return response(['success' => false], 404);
        }
		
		$top = Stat::where('ProfileID', $profile->ProfileID)
					 ->orderBy('ProfileID', 'ASC')
					 ->orderBy('StatTypeID', 'ASC')
					 ->orderBy('GeoLevel', 'ASC')	
					 //->orderBy('GeoID', 'ASC')
					 ->orderBy('Rank', 'ASC')
					 ->get();	
					 
		return $this->_top($top);	
	}
	
    public function _col_top(Request $request, $colIDString)
    {
		$col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }
		
		$top = Stat::where('ColID', $col->ColID)
					 ->orderBy('ProfileID', 'ASC')
					 ->orderBy('StatTypeID', 'ASC')
					 ->orderBy('GeoLevel', 'ASC')	
					 //->orderBy('GeoID', 'ASC')
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
	
    public function _lists(Request $request, $colIDString)
    {
		$user = Auth::user();

        if ($user == null) {
            return response(['success' => true], 200);
        }	
		
		$col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
		}

		$lists_all = LList::where('UserID','=',$user->id)
			->orderBy('Name')
			->get();

		foreach($lists_all as $list){
			$list->hasCol = false;
			foreach($list->cols as $col_){
				if ($col_->ColID == $col->ColID){
					$list->hasCol = true;
				}
			}
		}
		
		$lists = DB::table('lists')
            ->join('listcol', 'lists.ID', '=', 'listcol.ListID')
            ->where('lists.UserID', '=', $user->id)
			->where('listcol.ColID', '=', $col->ColID)
			->select('lists.Slug', 'lists.Name')
			->get();	

		$returnHTML = view('sub.collists')
		->with('lists', $lists)
		->with('lists_all', $lists_all)
		->render();
	
		return response()->json(array('success' => true, 'html'=>$returnHTML));	
    }
}