<?php

namespace App\Http\Controllers\Cols;

use App\Col;
use App\Http\Controllers\Controller;
/*use Carbon\Carbon;*/
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ColsController extends Controller
{

    public function all(Request $request)
    {
		$all = DB::table('colsearch')
                    ->select(DB::raw('ColIDString, Col AS label, Country1, Country2, Height'))
                    ->orderBy('Priority', 'ASC')
                    ->get();

		return response()->json($all);
    }
	
    public function photos(Request $request)
    {
		$photos = Col::whereNotNull('CoverPhotoPosition')
					->where('CoverPhotoMainPage', 1)
                    ->inRandomOrder()
					->take(100)
                    ->get();

		return response()->json($photos);
    }
}