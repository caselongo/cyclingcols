<?php

namespace App\Http\Controllers\Strava;

use App\Http\Controllers\Controller;

use App\Col;
use App\UserCol;
use App\Activity;

use App\Jobs\ProcessAthlete;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;;
use Illuminate\Support\Facades\DB;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

//use Polyline;

class StravaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* views */
    public function connect(Request $request)
    {
//		$uri = "https://www.strava.com/oauth/authorize?client_id=8752&response_type=code&redirect_uri=https://www.cyclingcols.com/strava/process/#/&approval_prompt=force";
//		if (request()->getHost() == "localhost"){
//       $uri = "https://www.strava.com/oauth/authorize?client_id=8752&response_type=code&redirect_uri=http://localhost:8000/strava/process/#/&approval_prompt=force";
//		}

		$uri = "https://www.strava.com/oauth/authorize?client_id=8752&response_type=code&redirect_uri=" . $request->getSchemeAndHttpHost() . "/strava/process&scope=activity:read_all";

        return \Redirect::to($uri);
    }

    public function process(Request $request)
    {
        set_time_limit(600);
		
		$now = Carbon::now('Europe/Amsterdam');

        $code = isset($_GET['code']) ? $_GET['code'] : null;

        if (!$code) {
			return $this->stravaError();
        }

        $client = new \GuzzleHttp\Client();
		
		$response_athlete = $client->request('POST', 'https://www.strava.com/oauth/token', [
            'form_params' => [
                'client_id' => \App\Constants::StravaClientId,
                'client_secret' => \App\Constants::StravaClientSecret,
                'code' => $code
            ],
            /*'stream_context' => [
                'ssl' => [
                    'allow_self_signed' => true
                ]
            ],*/
            'verify' => false
        ]);

        $content = json_decode($response_athlete->getBody()->getContents());

        $access_token = $content->access_token;
        $athlete = $content->athlete;

        if (!$access_token || !$athlete) {
			return $this->stravaError();
        }
		
        $user = Auth::user();
		
		$page = 1;
 		ProcessAthlete::dispatch($user, $athlete, $page, $access_token)->onQueue('athlete')->delay(10);
		
		/* unflag previous strava cols */
		$user->cols()->where('StravaNew', true)
			->update(['StravaNew' => false]);

		/* update user */
		$user->strava_athlete_id = $athlete->id;
		$user->strava_last_updated_at = Carbon::now('Europe/Amsterdam');
		$user->strava_processing = true;
		$user->save();
		
		$request->offsetUnset('scope');

        return \Redirect::to('/athlete');
    }
	
	private function stravaError(){
		Session::put('stravaError', true);
		return \Redirect::to('strava/error');
	}

    public function cols(Request $request)
    {
        $user = Auth::user();

        $cols = $user->colsNew()->where("StravaNew", true)->orderBy("pivot_ClimbedAt", "DESC")->get();

        return view('pages.stravacols')
            ->with('cols', $cols);
    }
	
	public function error(Request $request){
		$stravaError = Session::get('stravaError') ?? false;
        Session::forget('stravaError');

		if(!$stravaError) {
            return \Redirect::to('/');
        }
		
		return view('pages.stravaerror');
	}
	   
	public function _status(Request $request, $processed)
    {
		$user = Auth::user();
		
		if ($user == null){		
            return response(['success' => false], 404);
		}
		
		$count = $user->colsNew()->count();
		
		if ($processed = "0" || $processed == "false" || !$processed) $processed = false;
		else $processed = true;		

		$returnHTML = view('sub.stravastatus')
			->with('strava_last_updated_at', $user->strava_last_updated_at)
			->with('strava_processing', $user->strava_processing)
			->with('processed', $processed)
			->with('count', $count)
			->render();
		
		return response()->json(array('success' => true, 'html' => $returnHTML, 'strava_processing' => $user->strava_processing));	
    }
}