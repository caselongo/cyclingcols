<?php

namespace App\Http\Controllers\General;

use App\Country;
use App\Region;
use App\SubRegion;
use App\Ride;
use App\Banner;
use App\Col;
use App\NewItem;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class GeneralController extends Controller
{
	/* views */
	public function home(Request $request)
	{
		return view('pages.home');
	}	
	
	public function rides(Request $request)
	{
		return view('pages.rides');
	}	
	
	public function help(Request $request)
	{
		return view('pages.help');
	}		
	
	public function about(Request $request)
	{
		return view('pages.about');
	}	
	
	public function new(Request $request)
	{
		$newitems = \App\NewItem::orderBy('DateSort','DESC')->orderBy('ColIDString','ASC')->orderBy('IsNew','DESC')->get();
		
		$datesort = 0;
		$colidstring = "";
		
		$cols = array();
		$col;
		
		foreach($newitems as $newitem){
			if ($newitem->DateSort != $datesort || $newitem->ColIDString != $colidstring){
				
				$col = new \stdClass();
				$col->DateSort = $newitem->DateSort;
				$col->DateString = Carbon::createFromFormat('Ymd',$newitem->DateSort)->format('j M Y');
				$col->DiffForHumans = Carbon::createFromFormat('Ymd',$newitem->DateSort)->diffForHumans();
				$col->ColIDString = $newitem->ColIDString;
				$col->Col = $newitem->Col;
				$col->Country1 = $newitem->Country1;
				$col->Country2 = $newitem->Country2;
				$col->Height = $newitem->Height;
				$col->IsNew = false;
				$col->Profiles = array();
				
				array_push($cols,$col);
				
				$datesort = $newitem->DateSort;
				$colidstring = $newitem->ColIDString;
				
				$col_ = \App\Col::where("ColID",$newitem->ColID)->first();
				
				if ($col_){
					$col->CoverPhotoPosition = $col_->CoverPhotoPosition;
				}
			}
			
			if ($newitem->IsNewCol){
				$col->IsNew = true;
			}
			
			$profile = new \stdClass();
			$profile->ProfileID = $newitem->ProfileID;
			$profile->Side = $newitem->Side;
			$profile->Category = $newitem->Category;
			$profile->FileName = $newitem->FileName;
			$profile->IsNew = $newitem->IsNew;
			
			$start = \App\Profile::where("ProfileID",$newitem->ProfileID)->get();
			if ($start){
				$profile->Start = $start[0]->Start;
			}
			
			array_push($col->Profiles,$profile);
		}
		
		return view('pages.new')
			->with('newitems',$cols);
	}
	
	/* service */
    public function _countries(Request $request)
    {
		$countries = Country::get();
		
		return response()->json($countries);
    }

    public function _regions(Request $request)
    {
		$regions = Region::get();
		
		return response()->json($regions);
    }

    public function _subregions(Request $request)
    {
		$subregions = SubRegion::get();
		
		return response()->json($subregions);
    }

    public function _rides(Request $request)
    {
		$rides = Ride::orderBy('DateSort','DESC')->get();
		
		return response()->json($rides);
    }

    public function _banners_all(Request $request){
		return $this->_banners($request, null);
	}
	
    public function _banners(Request $request, $colIDString)
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