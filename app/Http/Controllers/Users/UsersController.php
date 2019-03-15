<?php

namespace App\Http\Controllers\Users;

use App\Col;
use App\Country;
use App\User;
use App\UserCol;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
	protected $sorttypeurl_default = "climbed";
	protected $countryurl_default = "eur";
	
    public function __construct()
    {
        $this->middleware('auth');
    }

	/* views */	
	public function index(Request $request)
    {
		/* overview */
		$total = UserCol::count();
		
		$total_year = UserCol::where('ClimbedAt','>=',Carbon::now()->startOfYear())->count();
		
		$total_lastyear = UserCol::where('ClimbedAt','<',Carbon::now()->startOfYear())->where('ClimbedAt','>=',Carbon::now()->addYear(-1)->startOfYear())->count();
		
		$users = User::count();
		
		$cols = UserCol::distinct()->count('ColID');
	
		/* users */
		$users_most = User::join('usercol','usercol.UserID', '=', 'users.id')
			->groupBy('users.id')
			->orderBy(DB::raw('count(usercol.id)'), 'DESC')
			->limit(10)
			->get(['users.id', 'users.name', DB::raw('count(usercol.id) as cols')]);
			
		$users_most_year = User::join('usercol','usercol.UserID', '=', 'users.id')
			->where('ClimbedAt','>=',Carbon::now()->startOfYear())
			->groupBy('users.id')
			->orderBy(DB::raw('count(usercol.id)'), 'DESC')
			->limit(10)
			->get(['users.id', 'users.name', DB::raw('count(usercol.id) as cols')]);
			
		/* cols */
		$cols_most = Col::join('usercol','usercol.ColID', '=', 'cols.ColID')
			->groupBy('cols.ColID')
			->orderBy(DB::raw('count(usercol.id)'), 'DESC')
			->limit(10)
			->get(['cols.ColIDString', 'cols.Col', 'cols.Country1', 'cols.Country2', DB::raw('count(usercol.id) as users')]);
			
		$cols_most_year = Col::join('usercol','usercol.ColID', '=', 'cols.ColID')
			->where('ClimbedAt','>=',Carbon::now()->startOfYear())
			->groupBy('cols.ColID')
			->orderBy(DB::raw('count(usercol.id)'), 'DESC')
			->limit(10)
			->get(['cols.ColIDString', 'cols.Col', 'cols.Country1', 'cols.Country2', DB::raw('count(usercol.id) as users')]);
			
		/* countries */
		$countries = Country::get();
		
		$col_count_user_max = 1;
			
		foreach($countries as $country){
			$col = Col::join('usercol','usercol.ColID', '=', 'cols.ColID')
				->whereRaw('cols.Country1ID = ' . $country->CountryID)
				->orWhereRaw('cols.Country2ID = ' . $country->CountryID)
				->groupBy('cols.ColID')
				->orderBy(DB::raw('count(usercol.id)'), 'DESC')
				->first(['cols.ColIDString', 'cols.Col', 'cols.Country1', 'cols.Country2', DB::raw('count(usercol.id) as users')]);
			
			if ($col){
				$country->ColIDString = $col->ColIDString;
				$country->Col = $col->Col;
				$country->Height = $col->Height;
				$country->Users = $col->users;
			}
		}
		
		$countries = $countries->sortBy('Users')->reverse();

        return view('pages.users', compact('users_most', 'users_most_year', 'cols_most', 'cols_most_year', 'countries', 'total', 'total_year', 'total_lastyear', 'users', 'cols'));
    }

}