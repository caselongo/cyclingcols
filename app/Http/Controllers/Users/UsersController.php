<?php

namespace App\Http\Controllers\Users;

use App\Col;
use App\Country;
use App\User;

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
		$users_most = User::join('usercol','usercol.UserID', '=', 'users.id')
			->groupBy('users.id')
			->orderBy(DB::raw('count(usercol.id)'), 'DESC')
			->limit(10)
			->get(['users.id', 'users.name', DB::raw('count(usercol.id) as cols')]);

        return view('pages.users', compact('users_most'));
    }

}