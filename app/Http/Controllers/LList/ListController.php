<?php

namespace App\Http\Controllers\LList;

use App\LList;
use App\ListSection;
use App\ListCol;
use App\User;
use App\Col;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
{
	/* views */	
	public function all(Request $request)
    {
        return $this->list($request, null);
	}
	
    private function __list(Request $request, $slug, $edit)
    {
		$lists = LList::where('UserID','=',0);

		$user = Auth::user();
		if ($user != null){
			$lists = $lists->orWhere('UserID','=',$user->id);
		}
		$lists = $lists->orderBy('UserID')->get();
		
		$list = null;
		$sections = null;
		$users = null;
		
		if (!is_null($slug)){
			$list = LList::where('Slug',$slug)->first();
			if (!is_null($list)){
				$user = User::where('id','=',$list->UserID)
					->select('users.name', 'users.slug')
					->first();
					
				$list->User = $user;
			} else {
				return \Redirect::to('list');	
			}
		}
       	
        return view('pages.list')
			->with('lists',$lists)
			->with('slug',$slug)
			->with('list',$list)
			->with('edit',$edit);
	}
	
    public function list(Request $request, $slug)
    {
		return $this->__list($request, $slug, 0);
	}
	
    public function list_edit(Request $request, $slug)
    {
		$user = Auth::user();
		if ($user == null){
			return \Redirect::to('list/' . $slug);	
		}
		
		$list = LList::where('Slug',$slug)
			->where('UserID','=',$user->id)
			->first();
			
		if ($list == null){
			return \Redirect::to('list/' . $slug);	
		}

		return $this->__list($request, $slug, 1);
	}

	/* service */
    public function _create(Request $request)
    {
		$user = Auth::user();
		
		if ($user != null){
			$list = new LList;

			$list->Name = $request->name;
			$list->UserID = $user->id;

			$list->save();
		}

        return response(['success' => true, 'slug' => $list->Slug], 200);
	}

    public function _update(Request $request)
    {
		$user = Auth::user();
		
		if ($user != null){
			$l = new LList;
			$slug = $l->get_slug($request->name);

			LList::where('Slug','=',$request->slug)
				->update(['Name' => $request->name, 'Slug' => $slug]);

        	return response(['success' => true, 'slug' => $slug], 200);
		}

		return response(['success' => true], 200);
	}

    public function _delete(Request $request)
    {
		$user = Auth::user();
		
		if ($user != null){
			$list = LList::where('Slug','=',$request->slug)->first();

			if ($list != null){
				ListCol::where('ListID', '=', $list->ID)->delete();
				ListSection::where('ListID', '=', $list->ID)->delete();
				LList::where('ID', '=', $list->ID)->delete();
			}
		}

        return response(['success' => true], 200);
	}

    public function _lists(Request $request, $userID)
    {
		$lists = LList::where('UserID',$userID)->get();

		foreach($lists as $list){
			$colCount = 0;
			$climbedCount = 0;

			foreach($list->cols as $col){
				$colCount++;

				if ($col->col->climbedByMe()){
					$climbedCount++;
				}
			}

			$list->ColCount = $colCount;
			$list->ClimbedCount = $climbedCount;
		}

		$user = Auth::user();
		$isOwner = false;

		if ($user != null){
			$isOwner = ($user->id == $userID);	
		}

		$returnHTML = view('sub.lists')
			->with('lists', $lists)
			->with('isOwner', $isOwner)
			->render();
		
		return response()->json(array('success' => true, 'html'=>$returnHTML));	

	}

    public function _list(Request $request, $slug)
    {
		$list = LList::where('Slug',$slug)->first();

		if ($list != null){

			$user = Auth::user();
			$isOwner = false;

			if ($user != null){
				$isOwner = ($user->id == $list->UserID);	
			}

			$sections = $list->sections()->orderBy('Sort')->get();

			$sections = $list->sections()->orderBy('Sort')->get();
				
			//users		
			$users = User::join('usercol', 'usercol.UserID', '=', 'users.id')
							->join('cols', 'cols.ColID', '=', 'usercol.ColID')
							->join('listcol', 'listcol.ColID', '=', 'usercol.ColID')
							->where('listcol.ListID','=',$list->ID)
							->where('usercol.StravaNew','=',0);
				
			$users = $users			
				->groupBy('users.id')
				->orderBy(DB::raw('count(listcol.ID)'), 'DESC')
				->select('users.id', 'users.name', 'users.slug', DB::raw('count(DISTINCT listcol.ColID) as count'))
				->limit(10)
				->get();

			//cols
			$cols = ListCol::join('cols', 'cols.ColID', '=', 'listcol.ColID')
				->where('listcol.ListID', '=', $list->ID)
				->select('cols.Col', 'cols.ColIDString', 'cols.Latitude', 'cols.Longitude')
				->get();

			$htmlSections = view('sub.listsections')
				->with('list', $list)
				->with('sections', $sections)
				//->with('isOwner', $isOwner)
				->with('edit', $request->edit)
				->render();

			$htmlUsers = view('sub.listusers')
				->with('list', $list)
				//->with('cols', $cols)
				->with('users', $users)
				->render();
			
			return response()->json(array('success' => true, 'cols' => $cols, 'htmlSections' => $htmlSections, 'htmlUsers' => $htmlUsers));	
		} else {
			return response()->json(array('success' => true, 'cols' => null, 'htmlSections' => null, 'htmlUsers' => null));	
		}
	}
	
    public function _create_section(Request $request)
    {
		$user = Auth::user();
		
		if ($user != null){
			$list = LList::where('Slug','=',$request->list)->first();

			if ($list != null){

				$section = new ListSection;

				$section->Name = $request->name;
				$section->ListID = $list->ID;

				$section->save();
			}
		}

        return response(['success' => true], 200);
	}

    public function _update_section(Request $request)
    {
		$user = Auth::user();
		
		if ($user != null){
			ListSection::where('ID','=',$request->id)
				->update(['Name' => $request->name]);
		}

        return response(['success' => true], 200);
	}

    public function _delete_section(Request $request)
    {
		$user = Auth::user();
		
		/*if ($user != null){
			ListCol::where('SectionID', '=', $request->id)->delete();
			ListSection::where('ID', '=', $request->id)->delete();
			
		}*/

		if ($user != null){
			$sec = ListSection::where('ID', '=', $request->id)->first();

			if ($sec != null){
				$list = LList::where('ID', '=', $sec->ListID)->first();

				if ($list != null){
					if ($list->UserID == $user->id){
						ListCol::where('SectionID', '=', $request->id)->delete();
						ListSection::where('ID', '=', $request->id)->delete();
						return response(['success' => true], 200);
					}
				}
			}
		}

        return response(['success' => true], 403);
	}
	
    public function _create_col(Request $request)
    {
		$user = Auth::user();
		
		if ($user != null){
			$list = LList::where('Slug','=',$request->list)->first();
			$sectionid = $request->sectionid;

			$cols = $request->cols;

			if ($cols == null && $request->col != null){
				$cols = array($request->col);
			}

			if ($list != null && $cols != null){
				foreach($cols as $colidstring){
					$col = Col::where('ColIDString','=', $colidstring)->first();

					if ($col != null){
						if ($request->sectionid == 0){
							$section = ListSection::where('ListID','=',$list->ID)
								->whereNull('Name')
								->first();

							if ($section == null){
								$sec = new ListSection;
								$sec->ListID = $list->ID;

								$sec->save();
								
								$section = ListSection::where('ListID','=',$list->ID)
								->whereNull('Name')
								->first();
							}

							if ($section != null){
								$sectionid = $section->ID;
							}

						}

						if ($sectionid > 0){
							$sort = 1;

							$cols = ListCol::where('SectionID','=',$sectionid)
									->orderBy('Sort','DESC')
									->first();

							if ($cols != null){
								$sort = $cols->Sort + 1;
							}


							$lc = new ListCol;

							$lc->ListID = $list->ID;
							$lc->SectionID = $sectionid;
							$lc->ColID = $col->ColID;
							$lc->Sort = $sort;

							$lc->save();
						}
					}
				}
			}
		}

        return response(['success' => true], 200);
	}

    public function _delete_col(Request $request)
    {
		$user = Auth::user();
		
		if ($user != null){
			if ($request->id != null){
				$col = ListCol::where('ID', '=', $request->id)->first();

				if ($col != null){
					$list = LList::where('ID', '=', $col->ListID)->first();

					if ($list != null){
						if ($list->UserID == $user->id){
							ListCol::where('ID', '=', $request->id)->delete();
							
							return response(['success' => true], 200);
						}
					}
				}
			} elseif ($request->list != null && $request->col != null) {
				$list = LList::where('Slug', '=', $request->list)->first();
				$col_ = Col::where('ColIDString', '=', $request->col)->first();
				if ($list != null && $col_ != null){
					$col = ListCol::where('ListID', '=', $list->ID)
						->where('ColID','=',$col_->ColID)
						->delete();
						
					return response(['success' => true], 200);
				}
			}

			$this->__delete_dummy_section($colFrom->ListID);				
		}

        return response(['success' => true], 403);
	}
	
    public function _sort_col(Request $request)
    {
		$user = Auth::user();
		
		if ($user != null){
			$colFrom = ListCol::where('ID','=',$request->idfrom)->first();
			$colTo = null;
			$sectionToID = 0;
			$sortTo = 0;

			if ($request->idto > 0){
				$colTo = ListCol::where('ID','=',$request->idto)->first();
				$sectionToID = $colTo->SectionID;
				$sortTo = $colTo->Sort;
			} else {
				$sectionToID = -$request->idto;
				$sortTo = ListCol::where('SectionID','=',$sectionToID)->max('Sort');
				if ($sortTo == null){
					$sortTo = 1;
				} else {
					$sortTo++;
				}
			}

			if ($colFrom != null && $sectionToID > 0){
				$sectionFromID = $colFrom->SectionID;
				$sortFrom = $colFrom->Sort;

				if ($sectionFromID != $sectionToID){
					$sortFrom = 100000;

					ListCol::where('SectionID','=',$sectionToID)
						->where('Sort','>=',$sortTo)
						->update(['Sort' => DB::raw('Sort + 1')]);

					ListCol::where('ID','=',$request->idfrom)
							->update(['SectionID' => $sectionToID, 'Sort' => $sortTo]);	
						
					//if ($sectionFromID == 0){
					$this->__delete_dummy_section($colFrom->ListID);
						/*$count = ListCol::where('ListID','=',$colFrom->ListID)
							->where('SectionID','=',$sectionFromID)
							->count();

						if ($count == 0){	
							ListSection::where('ListID','=',$colFrom->ListID)
								->where('ID', '=', $sectionFromID)->delete();
						}*/
					//}
				} else {
					if ($sortFrom != null && $sortTo != null){
						if ($sortFrom > $sortTo){
							ListCol::where('SectionID','=',$sectionToID)
								->whereBetween('Sort',[$sortTo, $sortFrom])
								->update(['Sort' => DB::raw('CASE WHEN Sort = ' . $sortFrom . ' THEN ' . $sortTo . ' ELSE Sort + 1 END')]);
						} else if ($sortFrom < $sortTo){
							ListCol::where('SectionID','=',$sectionToID)
								->whereBetween('Sort',[$sortFrom, $sortTo])
								->update(['Sort' => DB::raw('CASE WHEN Sort = ' . $sortFrom . ' THEN ' . $sortTo . ' ELSE Sort - 1 END')]);
						}
					}
				}
			}
		}

        return response(['success' => true], 200);
	}

	private function __delete_dummy_section($listID){
		$sec = ListSection::where('ListID','=',$listID)
			->whereNull('Name')->first();

		if ($sec != null){
			$count = ListCol::where('ListID','=',$listID)
			->where('SectionID','=',$sec->ID)
			->count();

			if ($count == 0){	
				ListSection::where('ID','=',$sec->ID)->delete();
			}
		}
	}
}