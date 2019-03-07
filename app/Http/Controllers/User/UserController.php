<?php

namespace App\Http\Controllers\User;

use App\Col;
use App\Country;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	/* views */	
    public function welcome(Request $request)
    {
        return view('pages.welcome');
    }
	
    public function index(Request $request)
    {
        $user = Auth::user();

        $_query = $user->cols();
		
		$_done = clone $_query;
        $_done = $_done->wherePivot('Done', 1);
		
		$_done_year = clone $_done;
		$_done_year->wherePivot('CreatedAt','>=',Carbon::now()->startOfYear());
		
		$_done_lastyear = clone $_done;
		$_done_lastyear->wherePivot('CreatedAt','<',Carbon::now()->startOfYear())->wherePivot('CreatedAt','>=',Carbon::now()->addYear(-1)->startOfYear());

        /*$countryID = 0;
        $total = null;
        if ($request->get('country') != null && $request->get('country') != 0 ) {

            $countryID = $request->get('country');
            $query = $query->where(function ($subQuery) use ($countryID) {

                return $subQuery->where('Country1ID', $countryID)->orWhere('Country2ID', $countryID);
            });

            $total = Col::where(function ($subQuery) use ($countryID) {

                return $subQuery->where('Country1ID', $countryID)->orWhere('Country2ID', $countryID);
            })->count();
        }

        if($total == null)
        {
            $total = Col::count();
        }*/


        //$todo = clone $_query;
        //$todo = $todo->wherePivot('ToDo',1)->orderBy('pivot_CreatedAt','desc')->get();

		/* rated */
        $ratings = clone $_query;
        $ratings = $ratings->wherePivot('Rating','>',0);
		
		$rating_count = $ratings->count();
		
		$ratings = $ratings->orderBy('pivot_Rating', 'Desc')->limit(10)->get();

		/* claimed */	
        $done_count = $_done->count();
		
		$done = clone $_done;
		$done = $done->orderBy('pivot_CreatedAt', 'desc')->limit(10)->get();

		/* claimed this year */	
		$done_year_count = $_done_year->count();

		/* claimed last year */	
		$done_lastyear_count = $_done_lastyear->count();
		
		/* countries */
		$countries = Country::get();
		
		$col_count_user_max = 1;
			
		foreach($countries as $country){
			$d = clone $_done;
			$dy = clone $_done_year;
			$dy = $dy->wherePivot('CreatedAt','>=',Carbon::now()->startOfYear());
			
			$country->col_count = $country->col_count();
			$country->col_count_user = $d->where('Country1ID', $country->CountryID)->count() + $d->where('Country2ID', $country->CountryID)->count();
			$country->col_count_user_year = $dy->where('Country1ID', $country->CountryID)->count() + $dy->where('Country2ID', $country->CountryID)->count();
			
			if ($country->col_count_user > $col_count_user_max){
				$col_count_user_max = $country->col_count_user;
			}
		}
		
		foreach($countries as $country){
			if ($country->col_count_user > 0){
				if ($country->col_count_user_year > 0){
					$country->width_year = 0.1 + round($country->col_count_user_year/$col_count_user_max,1) * 0.9;
				} else {
					$country->width_year = 0;
				}
				$country->width = 0.1 + round($country->col_count_user/$col_count_user_max,1) * 0.9 - $country->width_year;
			} else {
				$country->width_year = 0;
				$country->width = 0;
			}
			
			//if ($country->col_count_user > 0 && $country->col_count_perc == 0){
			//	$country->col_count_perc = "<1";
			//}
		}
		
		$countries = $countries->sortByDesc('col_count_user');
		
        //$doneThisYear = clone $query;
        //$doneThisYear = $doneThisYear->wherePivot('Done', 1)->wherePivot('CreatedAt','>=',Carbon::now()->startOfYear())->get();

        return view('cols.overview', compact('user', 'done', 'done_count', 'done_year_count', 'done_lastyear_count', 'countries', 'ratings', 'rating_count'));
    }

	/* service */

    public function _store(Request $request)
    {
		$colIDString = $request->input("colIDString");

        $user = Auth::user();
        $col = Col::where('ColIDString', $colIDString)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }

        $array = [];
        foreach (['done' => 'Done', 'rating' => 'Rating', 'favorite' => 'Favorate', 'todo' => 'ToDo'] as $postParam => $databaseField) {
            if ($request->input($postParam) != null) {

                if ($postParam == 'rating') {
                    $array[$databaseField] = $request->input($postParam);
                } elseif ($postParam = 'done') {
                    $array[$databaseField] = $request->input($postParam) === 'true';
                }
            }
        }

        if ($user->cols()->where('cols.ColID', $col->ColID)->first() != null) {

            $array['UpdatedAt'] = Carbon::now();
            $user->cols()->updateExistingPivot($col->ColID, $array, false);
        } else {

            $array['UpdatedAt'] = Carbon::now('Europe/Amsterdam');
            $array['CreatedAt'] = Carbon::now('Europe/Amsterdam');
            $user->cols()->attach($col->ColID, $array);
        }

        return response(['success' => true], 200);
    }
}