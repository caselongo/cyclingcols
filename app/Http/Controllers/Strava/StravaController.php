<?php

namespace App\Http\Controllers\Strava;

use App\Col;
use App\UserCol;
use App\Activity;

//use App\Country;
//use App\User;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

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
                'client_id' => 8752,
                'client_secret' => '149ff5792ba900da9a5beb578a03051dbb0cf3ba',
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

                /* check if cols exists in maximum latlng window */
                $first = $this->getColsQuery($lat_min, $lng_min, $lat_max, $lng_max)->first();

                if ($first != null) {
                    $coords = $this->getLatLngStream($activity->id, $access_token);
                    if(!property_exists($coords,'latlng')) {
                        continue;
                    }

                    $coords = $coords->latlng->data;
                    $this->addCols($coords, $cols, $activity->id, $date);
					
					/* create activity object to store in App\Activity later */
					$activity__ = new \stdClass();
					$activity__->ActivityID = $activity->id;
					$activity__->LatitudeMin = $lat_min;
					$activity__->LatitudeMax = $lat_max;
					$activity__->LongitudeMin = $lng_min;
					$activity__->LongitudeMax = $lng_max;

					$activities_done[] = $activity__;
                }

            }
        }

        /* unflag previous strava cols */
		$user->cols()->where('StravaNew', true)
            ->update(['StravaNew' => false]);
			
        foreach ($cols as $col) {

            $array = [];
            $col_ = $user->cols()->where('cols.ColID', $col->ColID)->first();

            if ($col_ != null) {
                $date = Carbon::today();
                if ($col_->pivot->ClimbedAt) {
                    $date = Carbon::parse($col_->pivot->ClimbedAt);
                }
				
				$stravaActivityIDs = $col_->pivot->StravaActivityIDs;
				if ($stravaActivityIDs == null){
					$stravaActivityIDs = $col->ActivityID;
				} else if (strrpos(";" . $stravaActivityIDs . ";", ";" . $col->ActivityID . ";") === false){
					$stravaActivityIDs += ";" + $col->ActivityID;
				}
				
				$array['UpdatedAt'] = $now;
                $array['StravaActivityIDs'] = $stravaActivityIDs;
                if ($col->Date < $date) $array['ClimbedAt'] = $col->Date;
				
                $user->cols()->updateExistingPivot($col->ColID, $array, false);
            } else {
                $array['UpdatedAt'] = $now;
                $array['CreatedAt'] = $now;
                $array['ClimbedAt'] = $col->Date;
                $array['StravaNew'] = true;
                $array['StravaActivityIDs'] = $col->ActivityID;
                $user->cols()->attach($col->ColID, $array);
            }
        }
		
		/*  store activities processed */
		foreach($activities_done as $activity_done){
			$user->activities()->create([
				'CreatedAt' => $now,
				'AthleteID' => $athlete->id,
				'ActivityID' => $activity_done->ActivityID,
				'LatitudeMin' => $activity_done->LatitudeMin * 1000000,
				'LatitudeMax' => $activity_done->LatitudeMax * 1000000,
				'LongitudeMin' => $activity_done->LongitudeMin * 1000000,
				'LongitudeMax' => $activity_done->LongitudeMax * 1000000
			]);
		}

		/* update user */
		$user->strava_athlete_id = $athlete->id;
		$user->strava_last_updated_at = Carbon::now('Europe/Amsterdam');
        $user->save();

        return \Redirect::to('strava/cols');
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

    private function getColsQuery($lat_min, $lng_min, $lat_max, $lng_max)
    {
        return Col::where('Latitude', '>=', $lat_min * 1000000)
            ->where('Latitude', '<=', $lat_max * 1000000)
            ->where('Longitude', '>=', $lng_min * 1000000)
            ->where('Longitude', '<=', $lng_max * 1000000);
    }

    private function addCols($coords, &$cols, $activityId, $date)
    {
        if (count($coords) == 0) return null;

        $lat_min = $coords[0][0];
        $lat_max = $lat_min;

        $lng_min = $coords[0][1];
        $lng_max = $lng_min;

        foreach ($coords as $coord) {
            $lat = $coord[0];
            if ($lat < $lat_min) $lat_min = $lat;
            else if ($lat > $lat_max) $lat_max = $lat;

            $lng = $coord[1];
            if ($lng < $lng_min) $lng_min = $lng;
            else if ($lng > $lng_max) $lng_max = $lng;
        }

        $cols_ = $this->getColsQuery($lat_min, $lng_min, $lat_max, $lng_max)->get();

        foreach ($cols_ as $col_) {


            foreach ($coords as $coord) {
                $d = distance($col_->Latitude / 1000000, $col_->Longitude / 1000000, $coord[0], $coord[1], "K");

                if ($d < 0.2) {
                    //$col_found = false;

                    /*foreach ($cols as $col) {
                        if ($col->ColID == $col_->ColID) {
                            if ($date < $col->Date) {
                                $col->Date = $date;
                            }

                            $col_found = true;
                            break;
                        }
                    }*/

                    //if (!$col_found) {
                        $col__ = new \stdClass();
                        $col__->ColID = $col_->ColID;
                        //$col__->col = $col_;
                        $col__->Date = $date;
						$col__->ActivityID = $activityId;

                        $cols[] = $col__;
                    //}

                    break;
                }
            }
        }

        return;
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
                'after' => 1537917506,
                'before' => 1538169064,
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

    private function getActivity($id, $access_token)
    {
        $client = new \GuzzleHttp\Client();

        $headers = [
            'Authorization' => 'Bearer ' . $access_token
        ];

        $response = $client->request('GET', 'https://www.strava.com/api/v3/activities/' . $id, [
            'query' => [
                'include_all_efforts ' => false
            ],
            'headers' => $headers,
            'verify' => false
        ]);

        return json_decode($response->getBody()->getContents());
    }

    private function getLatLngStream($id, $access_token)
    {
        $client = new \GuzzleHttp\Client();

        $headers = [
            'Authorization' => 'Bearer ' . $access_token
        ];

        $response = $client->request('GET', 'https://www.strava.com/api/v3/activities/' . $id . "/streams", [
            'query' => [
                'keys' => 'latlng',
                'key_by_type' => true
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