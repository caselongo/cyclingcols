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
				$col->DateString = getHumanDate(Carbon::createFromFormat('Ymd',$newitem->DateSort));
				//$col->DiffForHumans = Carbon::createFromFormat('Ymd',$newitem->DateSort)->diffForHumans();
				$col->ColIDString = $newitem->ColIDString;
				$col->Col = $newitem->Col;
				$col->Country1 = $newitem->Country1;
				$col->Country1IDString = $newitem->Country1IDString;
				$col->Country2 = $newitem->Country2;
				$col->Country2IDString = $newitem->Country2IDString;
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
		$countries = Country::where("NrCols",">",0)->get();
		
		return response()->json($countries);
    }

    public function _regions(Request $request)
    {
		$regions = Region::where("NrCols",">",0)->get();
		
		return response()->json($regions);
    }

    public function _subregions(Request $request)
    {
		$subregions = SubRegion::where("NrCols",">",0)->get();
		
		return response()->json($subregions);
    }

    public function _rides(Request $request)
    {
		$rides = Ride::orderBy('DateSort','DESC')->get();
		
		return response()->json($rides);
    }

    public function _banners(Request $request)
    {
		$colIDString = $request->query('col', 'home');
		$count = $request->query('cnt', 1000);
		$contact = $request->query('ct', true);
		if($contact == "false") $contact = false;
		
		$colid = 0;
		
		if ($colIDString != null && $colIDString != "home"){
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
					->limit($count)
					->get();
		
		$returnHTML = view('sub.banners')
			->with('banners', $banners)
			->with('contact', $contact)
			->render();
		
		return response()->json(array('success' => true, 'html' => $returnHTML));	
    }
}