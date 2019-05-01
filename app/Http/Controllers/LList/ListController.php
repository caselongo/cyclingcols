<?php

namespace App\Http\Controllers\LList;

use App\LList;
use App\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
{
	/* views */	
	public function index_default(Request $request)
    {
        return $this->index($request, null);
	}
	
    public function index(Request $request, $slug)
    {
		$lists = LList::all();
		
		$list = null;
		$sections = null;
		
		if (!is_null($slug)){
			$list = LList::where('Slug',$slug)->first();
			if (!is_null($list)){
				$sections = $list->sections()->orderBy('Sort')->get();
			}
		}
		
		//users
		
		$users = User::join('usercol', 'usercol.UserID', '=', 'users.id')
						->join('cols', 'cols.ColID', '=', 'usercol.ColID')
						->join('listcol', 'listcol.ColID', '=', 'usercol.ColID');
			
		$users = $users			
			->groupBy('users.id')
			->orderBy(DB::raw('count(listcol.ID)'), 'DESC')
			->select('users.id', 'users.name', 'users.slug', DB::raw('count(listcol.id) as count'))
			->limit(10)
			->get();
       	
        return view('pages.list')
			->with('lists',$lists)
			->with('list',$list)
			->with('sections',$sections)
			->with('users',$users);
	}
}