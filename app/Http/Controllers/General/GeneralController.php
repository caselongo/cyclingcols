<?php

namespace App\Http\Controllers\General;

use App\Country;
use App\Region;
use App\SubRegion;
use App\Banner;
use App\Col;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    public function countries(Request $request)
    {
		$countries = Country::get();
		
		return response()->json($countries);
    }

    public function regions(Request $request)
    {
		$regions = Region::get();
		
		return response()->json($regions);
    }

    public function subregions(Request $request)
    {
		$subregions = SubRegion::get();
		
		return response()->json($subregions);
    }

    public function banners_all(Request $request){
		return $this->banners($request, null);
	}
	
    public function banners(Request $request, $colIDString)
    {
		$colid = 0;
		
		if ($colIDString != null){
			$col = Col::where('ColIDString', $colIDString)->first();
			
			if ($col != null){
				$colid = $col->ColID;
			} else {
				$colid = -1;
			}
		}
		
		$banners = Banner::select('RedirectURL', 'BannerFileName')
					->where('ColID', $colid)
					->where('Active', 1)
                    ->inRandomOrder()
					->get();
		
		return response()->json($banners);
    }
}