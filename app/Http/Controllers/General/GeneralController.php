<?php

namespace App\Http\Controllers\General;

use App\Country;
use App\Region;
use App\SubRegion;

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
}