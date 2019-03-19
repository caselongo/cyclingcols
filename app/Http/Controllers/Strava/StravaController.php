<?php

namespace App\Http\Controllers\Strava;

use App\Col;
use App\UserCol;
use App\Activity;

//use App\Country;
//use App\User;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use App\Jobs\ProcessActivity;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;;
use Illuminate\Support\Facades\DB;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Polyline;

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
		
        $activities = $this->getActivities($access_token);

        $cols = array();
		$activities_done = array();

        foreach ($activities as $activity) {
			$activity_existing = $user->activities->where('ActivityID', $activity->id)->first();
			
            if ($activity_existing == null && $activity->type == "Ride") { //new activity
			    $date = new Carbon($activity->start_date_local);
                $date->setTime(0, 0, 0);

				/* get maximum latlng window of activity */
                $distance = $activity->distance;
                $distance_direct = distance($activity->start_latlng[0], $activity->start_latlng[1], $activity->end_latlng[0], $activity->end_latlng[1], "K");
                $d_distance = ($distance / 1000 - $distance_direct) / 2;

                $lat_min = min($activity->start_latlng[0], $activity->end_latlng[0]);
                $lat_max = max($activity->start_latlng[0], $activity->end_latlng[0]);
                $lng_min = min($activity->start_latlng[1], $activity->end_latlng[1]);
                $lng_max = max($activity->start_latlng[1], $activity->end_latlng[1]);

                $lat_min -= distanceToLatitude($d_distance);
                $lat_max += distanceToLatitude($d_distance);

                $lng_min -= distanceToLongitude($d_distance, ($lat_min + $lat_max) / 2);
                $lng_max += distanceToLongitude($d_distance, ($lat_min + $lat_max) / 2);
				
				//DB::enableQueryLog();
			
				$activity = $user->activities()->create([
					//'CreatedAt' => Carbon::now('Europe/Amsterdam'),
					//'UpdatedAt' => Carbon::now('Europe/Amsterdam'),
					'AthleteID' => $athlete->id,
					'ActivityID' => $activity->id,
					'LatitudeMin' => $lat_min * 1000000,
					'LatitudeMax' => $lat_max * 1000000,
					'LongitudeMin' => $lng_min * 1000000,
					'LongitudeMax' => $lng_max * 1000000
				]);
				
				ProcessActivity::dispatch($user, $activity, $date, $access_token)->delay(10);
            }
        }

        /* unflag previous strava cols */
		$user->cols()->where('StravaNew', true)
            ->update(['StravaNew' => false]);

		/* update user */
		$user->strava_athlete_id = $athlete->id;
		$user->strava_last_updated_at = Carbon::now('Europe/Amsterdam');
        $user->save();

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

    private function getActivities($access_token)
    {
        $client = new \GuzzleHttp\Client();

        $headers = [
            'Authorization' => 'Bearer ' . $access_token
        ];

        $response = $client->request('GET', 'https://www.strava.com/api/v3/athlete/activities', [
            'query' => [
                'page' => 1,
                'after' => 1536786664,
                //'before' => 1538169064,
                'per_page' => 100
                //before 1538169064
                //before 1536932064
                //after 1536786664

            ],
            'headers' => $headers,
            'verify' => false
        ]);

        return json_decode($response->getBody()->getContents());
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