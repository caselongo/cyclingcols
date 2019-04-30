<?php

namespace App\Jobs;

use App\User;

use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ProcessAthlete implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	
	protected $user;
	protected $athlete;
	protected $page;
	protected $access_token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $athlete, $page, $access_token)
    {
        $this->user = $user;
		$this->athlete = $athlete;
		$this->page = $page;
		$this->access_token = $access_token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		//echo $this->page;
		
		$activities = $this->getActivities();
		
		if (is_null($activities)) {	
			$this->finishAthlete();
			return;
		};
		
		echo "UserID: " . $this->user->id . "\r\n";
		echo "Page: " . $this->page . "\r\n";
		echo "#Activities: " . count($activities) . "\r\n";

		if (count($activities) == 0) {		
			$this->finishAthlete();
			return;
		};

        $cols = array();
		
        foreach ($activities as $activity) {
			$activity_existing = $this->user->activities->where('ActivityID', $activity->id)->first();
			
            if ($activity_existing == null && $activity->type == "Ride") { //new activity
			    $date = new Carbon($activity->start_date_local);
                $date->setTime(0, 0, 0);

				/* get maximum latlng window of activity */
                $distance = $activity->distance;
				/*if ($activity->id == 725690359 || $activity->id == 724625021){
					echo $distance;
					echo "\r\n";
				}  */              
				
				$distance_direct = distance($activity->start_latlng[0], $activity->start_latlng[1], $activity->end_latlng[0], $activity->end_latlng[1], "K");
				/*if ($activity->id == 725690359 || $activity->id == 724625021){
					echo $distance_direct;
					echo "\r\n";
				} */   

				if (is_nan($distance_direct)) $distance_direct = 0;
					
                $d_distance = ($distance / 1000 - $distance_direct) / 2;
				/*if ($activity->id == 725690359 || $activity->id == 724625021){
					echo $d_distance;
					echo "\r\n";
				}*/

                $lat_min = min($activity->start_latlng[0], $activity->end_latlng[0]);
                $lat_max = max($activity->start_latlng[0], $activity->end_latlng[0]);
                $lng_min = min($activity->start_latlng[1], $activity->end_latlng[1]);
                $lng_max = max($activity->start_latlng[1], $activity->end_latlng[1]);
				/*if ($activity->id == 725690359 || $activity->id == 724625021){
					echo $lat_min;
					echo "\r\n";
					echo $lat_max;
					echo "\r\n";
					echo $lng_min;
					echo "\r\n";
					echo $lng_max;
					echo "\r\n";
				}    */            

                $lat_min -= distanceToLatitude($d_distance);
                $lat_max += distanceToLatitude($d_distance);

                $lng_min -= distanceToLongitude($d_distance, ($lat_min + $lat_max) / 2);
                $lng_max += distanceToLongitude($d_distance, ($lat_min + $lat_max) / 2);
				/*if ($activity->id == 725690359 || $activity->id == 724625021){
					echo $lat_min;
					echo "\r\n";
					echo $lat_max;
					echo "\r\n";
					echo $lng_min;
					echo "\r\n";
					echo $lng_max;
					echo "\r\n";
				}   */             
				
				//DB::enableQueryLog();
			
				$activity = $this->user->activities()->create([
					//'CreatedAt' => Carbon::now('Europe/Amsterdam'),
					//'UpdatedAt' => Carbon::now('Europe/Amsterdam'),
					'AthleteID' => $this->athlete->id,
					'ActivityID' => $activity->id,
					'LatitudeMin' => $lat_min * 1000000,
					'LatitudeMax' => $lat_max * 1000000,
					'LongitudeMin' => $lng_min * 1000000,
					'LongitudeMax' => $lng_max * 1000000
				]);
				
				ProcessActivity::dispatch($this->user, $activity, $date, $this->access_token)->onQueue('activity');
				//ProcessActivity::dispatch($this->user, $activity, $date, $this->access_token)->delay(10);
            }
        }
		
		//if ($page == 1){

			/* unflag previous strava cols */
			//$this->user->cols()->where('StravaNew', true)
			//	->update(['StravaNew' => false]);

			/* update user */
			//$this->user->strava_athlete_id = $this->athlete->id;
			///$this->user->strava_last_updated_at = Carbon::now('Europe/Amsterdam');
			//$this->user->save();
		//}
		
		/*if ($this->page < 15){
			ProcessAthlete::dispatch($this->user, $this->athlete, $this->page + 1, $this->access_token)->onQueue('athlete');
		} else {			
			$this->finishAthlete();
			return;
		}*/
		
		ProcessAthlete::dispatch($this->user, $this->athlete, $this->page + 1, $this->access_token)->onQueue('athlete');
    }
	
	private function finishAthlete(){
		FinishAthlete::dispatch($this->user)->onQueue('activity')->delay(10);
	}

    private function getActivities()
    {
		$client = new \GuzzleHttp\Client();

        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token
        ];
		
		try{

			$response = $client->request('GET', 'https://www.strava.com/api/v3/athlete/activities', [
				'query' => [
					'page' => $this->page,
					'per_page' => 100
				],
				/*'query' => [
					'page' => 1,
					'after' => 1523752202,
					'before' => 1523838602,
					'per_page' => 100
					//before 1538169064
					//before 1536932064
					//after 1536786664

				],*/
				'headers' => $headers,
				'verify' => false
			]);
		}
		catch (RequestException $e) {
			echo "error";
			return null;
			
			
			//if ($e->hasResponse()) {
			//	echo Psr7\str($e->getResponse());
			//}
		}

        return json_decode($response->getBody()->getContents());
    }
}
