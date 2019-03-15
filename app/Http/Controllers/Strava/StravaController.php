<?php

namespace App\Http\Controllers\Strava;

use App\Col;
//use App\Country;
//use App\User;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
		$uri = "https://www.strava.com/oauth/authorize?client_id=8752&response_type=code&redirect_uri=https://www.cyclingcols.com/strava/cols/#/&approval_prompt=force";
		if (request()->getHost() == "localhost"){
			$uri = "https://www.strava.com/oauth/authorize?client_id=8752&response_type=code&redirect_uri=http://localhost:8000/strava/cols/#/&approval_prompt=force";
		}
		
        return \Redirect::to($uri);	
    }
	
	public function cols(Request $request){
		$code = isset($_GET['code']) ? $_GET['code'] : null; 
		
		if (!$code){
			return \Redirect::to('strava/error');	
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
		
		if (!$access_token || !$athlete){
			return \Redirect::to('strava/error');
		}
		
		$user = Auth::user();
		
		$maxActivityID = $user->strava_max_activity_id; //$user->MaxActivityID;
		$maxActivityID = 0;
		if (!$maxActivityID) $maxActivityID = 0;
		
		$activities = $this->getActivities($access_token);
		
		$cols = array();
		
		$id = 0;
		foreach($activities as $activity){
			if ($activity->id > $maxActivityID){ //new activity
				$date = new Carbon($activity->start_date_local);
				
				/* get maximum latlng window of activity */
				$distance = $activity->distance;
				$distance_direct = distance($activity->start_latlng[0],$activity->start_latlng[1],$activity->end_latlng[0],$activity->end_latlng[1],"K");
				$d_distance = ($distance/1000 - $distance_direct) / 2;
				
				$lat_min = min($activity->start_latlng[0],$activity->end_latlng[0]);
				$lat_max = max($activity->start_latlng[0],$activity->end_latlng[0]);
				$lng_min = min($activity->start_latlng[1],$activity->end_latlng[1]);
				$lng_max = max($activity->start_latlng[1],$activity->end_latlng[1]);
				
				$lat_min -= distanceToLatitude($d_distance);
				$lat_max += distanceToLatitude($d_distance);
				
				$lng_min -= distanceToLongitude($d_distance, ($lat_min + $lat_max) / 2);
				$lng_max += distanceToLongitude($d_distance, ($lat_min + $lat_max) / 2);
				
				/* check if cols exists in maximum latlng window */
				$first = $this->getColsQuery($lat_min, $lng_min, $lat_max, $lng_max)->first();	
				
				if ($first){
					$coords = $this->getLatLngStream($activity->id, $access_token)->latlng->data;		
					$this->addCols($coords, $cols, $date);
				}
				
				if ($activity->id > $id) $id = $activity->id;
			}
		}
		
		if ($id > $maxActivityID){
			$user->strava_max_activity_id = $id;
		}
		$user->strava_last_updated_at = Carbon::now('Europe/Amsterdam');
		$user->save();
		
		return view('pages.stravacols')
			->with('cols',$cols);
	}
	
	private function getColsQuery($lat_min, $lng_min, $lat_max, $lng_max){
		return Col::where('Latitude', '>=', $lat_min * 1000000)
			->where('Latitude', '<=', $lat_max * 1000000)
			->where('Longitude', '>=', $lng_min * 1000000)
			->where('Longitude', '<=', $lng_max * 1000000);		
	}
	
	private function addCols($coords,&$cols,$date){
		if (count($coords) == 0) return null;
		
		$lat_min = $coords[0][0];
		$lat_max = $lat_min;
		
		$lng_min = $coords[0][1];
		$lng_max = $lng_min;
		
		foreach($coords as $coord){
			$lat = $coord[0];
			if ($lat < $lat_min) $lat_min = $lat;
			else if ($lat > $lat_max) $lat_max = $lat;
			
			$lng = $coord[1];
			if ($lng < $lng_min) $lng_min = $lng;
			else if ($lng > $lng_max) $lng_max = $lng;
		}
		
		$cols_ = $this->getColsQuery($lat_min, $lng_min, $lat_max, $lng_max)->get();
			
		foreach($cols_ as $col_){
			
			
			foreach($coords as $coord){
				$d = distance($col_->Latitude/1000000, $col_->Longitude/1000000, $coord[0], $coord[1], "K");
		
				if ($d < 0.2){
					$col_found = false;
					
					foreach($cols as $col){
						if ($col->ColID == $col_->ColID){
							if ($date < $col->Date){
								$col->Date = $date;
							}
							
							$col_found = true;
							break;
						}
					}
					
					if (!$col_found){		
						$col__ = new \stdClass();
						$col__->ColID = $col_->ColID;
						$col__->col = $col_;
						$col__->Date = $date;

						$cols[] = $col__;
					}
					
					break;
				}
			}
		}
		
		return;
	}
	
	private function getActivities($access_token){
		$client = new \GuzzleHttp\Client();
		
		$headers = [
			'Authorization' => 'Bearer ' . $access_token
		];
		
		$response = $client->request('GET', 'https://www.strava.com/api/v3/athlete/activities', [
			'query' => [
				'page' => 1,
				'after' => 1536786664,
				'before' => 1538169064,
				'per_page' => 30
				//1538169064
				//1536932064
				
			],
			'headers' => $headers,
			'verify' => false
		]);
		
		return json_decode($response->getBody()->getContents());		
	}
	
	private function getActivity($id, $access_token){
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
	
	private function getLatLngStream($id, $access_token){
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
		 return view('pages.stravaerror');
	}

}