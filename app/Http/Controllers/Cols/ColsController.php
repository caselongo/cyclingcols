<?php

namespace App\Http\Controllers\Cols;

use App\Col;
use App\UserCol;

use App\Http\Controllers\Controller;
/*use Carbon\Carbon;*/
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ColsController extends Controller
{
	/* service */
    public function _cols(Request $request)
    {
		$user = Auth::user();
		
		if ($user == null){
			$all = Col::select('ColIDString','Col','Latitude','Longitude','Height','Country1ID','Country2ID')->get();
		} else {
			$climbed = UserCol::select('UserID','ColID','ClimbedAt')
                   ->where('UserID', $user->id)
                   ->groupBy('ColID');
			
			$all = Col::select('cols.ColIDString','cols.Col','cols.Latitude','cols.Longitude','cols.Height','cols.Country1ID','cols.Country2ID','climbed.ClimbedAt')
				->leftJoinSub($climbed, 'climbed', function ($join) {
					$join->on('cols.ColID', '=', 'climbed.ColID');
				})->get();			
		}

		return response()->json($all);
    }
	
    public function _search(Request $request)
    {
		$search = DB::table('colsearch')
                    ->select(DB::raw('ColIDString, Col AS label, Country1, Country2, Height'))
                    ->orderBy('Priority', 'ASC')
                    ->get();

		return response()->json($search);
    }
	
    public function _photos(Request $request)
    {
		$photos = Col::whereNotNull('CoverPhotoPosition')
					->where('CoverPhotoMainPage', 1)
                    ->inRandomOrder()
					->take(100)
                    ->get();

		return response()->json($photos);
    }
}