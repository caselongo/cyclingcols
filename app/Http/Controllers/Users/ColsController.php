<?php

namespace App\Http\Controllers\Users;

use App\Col;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColsController extends Controller
{


    public function index()
    {

        $user = Auth::user();

        $cols = $user->cols()->get();

        return view('cols.overview', compact('cols', 'user'));

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request, $colID)
    {
        //Two options
        //Done
        //mark favorite

        $user = Auth::user();
        $col = Col::where('ColID', $colID)->first();

        if ($col == null) {
            return response(['success' => false], 404);
        }

        $array = [];
        foreach (['done'=>'Done', 'rating'=>'Rating', 'favorite'=>'Favorate','todo'=>'TODO'] as $postParam =>$databaseField) {
            if ($request->input($postParam) != null) {

                if($databaseField == 'rating') {
                    $array[$databaseField] = $request->input($postParam);
                } else{
                    $array[$databaseField] = true;
                }
            }
        }
        
        if ($user->cols()->where('cols.ColId', $colID)->first() != null) {
            $user->cols()->updateExistingPivot($col->id, $array, false);
        } else {
            $user->cols()->attach($col->ColID, $array);
        }

        return response(['success' => true], 200);

    }
}


//** return object
// ['collID' => id, 'rating'=>int, 'favorite'=>boolean]
//
// */