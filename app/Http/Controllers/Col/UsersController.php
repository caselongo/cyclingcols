<?php

namespace App\Http\Controllers\Col;

use App\Col;
use App\Http\Controllers\Controller;
/*use Carbon\Carbon;*/
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
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
}