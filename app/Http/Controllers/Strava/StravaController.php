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
        $uri = "https://www.strava.com/oauth/authorize?client_id=8752&response_type=code&redirect_uri=http://localhost:8000/strava/process/#/&approval_prompt=force";
//		}

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
		
 		ProcessAthlete::dispatch($user, $athlete, $access_token)->onQueue('athlete')->delay(10);

        return \Redirect::to('/athlete');
    }
	
	private function stravaError(){
		Session::put('stravaError', true);
		return \Redirect::to('strava/error');
	}

    public function cols(Request $request)
    {
        $user = Auth::user();

        $cols = $user->cols()->where("StravaNew", true)->orderBy("pivot_ClimbedAt", "DESC")->get();

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
}