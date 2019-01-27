<?php

namespace App\Http\Controllers\Users;

use App\Col;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColsController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = $user->cols();
        $countryID = 0;
        if ($request->get('country') != null && $request->get('country') != 0 ) {

            $countryID = $request->get('country');
            $query = $query->where(function ($subQuery) use ($countryID) {

                return $subQuery->where('Country1ID', $countryID)->orWhere('Country2ID', $countryID);
            });
        }


        $ratings = $query->orderBy('pivot_Rating', 'Desc')->get();
        $done = $query->wherePivot('Done', 1)->orderBy('pivot_CreatedAT', 'desc')->get();

        return view('cols.overview', compact('ratings', 'user', 'done','countryID'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request, $colID)
    {

        $user = Auth::user();
        $col = Col::where('ColID', $colID)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }

        $array = [];
        foreach (['done' => 'Done', 'rating' => 'Rating', 'favorite' => 'Favorate', 'todo' => 'TODO'] as $postParam => $databaseField) {
            if ($request->input($postParam) != null) {

                if ($postParam == 'rating') {
                    $array[$databaseField] = $request->input($postParam);
                } elseif ($postParam = 'done') {
                    $array[$databaseField] = $request->input($postParam) === 'true';
                }
            }
        }

        if ($user->cols()->where('cols.ColId', $colID)->first() != null) {

            $array['UpdatedAT'] = Carbon::now();
            $user->cols()->updateExistingPivot($col->ColID, $array, false);
        } else {

            $array['UpdatedAT'] = Carbon::now();
            $array['CreatedAT'] = Carbon::now();
            $user->cols()->attach($col->ColID, $array);
        }

        return response(['success' => true], 200);
    }
}


//** return object
// ['collID' => id, 'rating'=>int, 'favorite'=>boolean]
//
// */