<?php

namespace App\Jobs;

use App\Col;
use App\Activity;
use App\User;

use App\Jobs\ProcessActivity;

use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ProcessActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	
	protected $user;
	protected $activity;
	protected $date;
	protected $access_token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Activity $activity, $date, string $access_token)
    {
        $this->user = $user;
		$this->activity = $activity;
		$this->date = $date;
		$this->access_token = $access_token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		//echo $this->access_token;
        //$this->activity->AthleteID = 123;

		//$this->activity->save();
		
		//return; 

		echo "ActivityID: " . $this->activity->ActivityID . "\r\n";
		//echo $this->activity->ActivityID;
		
		$count = "-";
			
		//echo "\r\n0\r\n";
		//echo Carbon::now();
		
		/* check if cols exists in maximum latlng window */
		$first = $this->getColsQuery($this->activity->LatitudeMin, $this->activity->LongitudeMin, $this->activity->LatitudeMax, $this->activity->LongitudeMax)->first();

		//echo "\r\n00\r\n";
		//echo Carbon::now();
		
		if ($first != null) {
			$coords = $this->getLatLngStream($this->activity->ActivityID, $this->access_token);
			
			
			//echo "\r\n5\r\n";
			//echo Carbon::now();
			
			if(property_exists($coords,'latlng')) {
				//echo "yes";
				
				$coords = $coords->latlng->data;

				$cols = $this->getCols($coords, $this->activity->ActivityID);
				
				//echo "\r\n6\r\n";
				//echo Carbon::now();
				
				$this->saveCols($cols);
					
				//echo "\r\n7\r\n";
				//echo Carbon::now();
				
				$count = count($cols);
			} else {
				//echo "no";
			}
		}	
		
		echo "ColsFound: " . $count . "\r\n";		
    }

    private function getCols($coords, $activityId)
    {
        if (count($coords) == 0) return null;

        $lat_min = $coords[0][0];
        $lat_max = $lat_min;

        $lng_min = $coords[0][1];
        $lng_max = $lng_min;
		
		$cols = array();
		
		//echo "lat_min";
		//echo $lat_min;
		//echo "lat_max";
		//echo $lat_max;
		//echo "\r\n1\r\n";
		//echo Carbon::now();

        foreach ($coords as $coord) {
            $lat = $coord[0];
            if ($lat < $lat_min) $lat_min = $lat;
            else if ($lat > $lat_max) $lat_max = $lat;

            $lng = $coord[1];
            if ($lng < $lng_min) $lng_min = $lng;
            else if ($lng > $lng_max) $lng_max = $lng;
        }
		
		/*$this->activity->update([
			'LatitudeMin' => $lat_min * 1000000,
			'LatitudeMax' => $lat_max * 1000000,
			'LongitudeMin' => $lng_min * 1000000,
			'LongitudeMax' => $lng_max * 1000000
		]);*/
		
		//echo $this->activity->LatitudeMin;
		
		$this->activity->LatitudeMin = $lat_min * 1000000;
		$this->activity->LatitudeMax = $lat_max * 1000000;
		$this->activity->LongitudeMin = $lng_min * 1000000;
		$this->activity->LongitudeMax = $lng_max * 1000000;
		$this->activity->save();
		
		//echo "\r\n2\r\n";
		//echo Carbon::now();

        $cols_ = $this->getColsQuery($lat_min * 1000000, $lng_min * 1000000, $lat_max * 1000000, $lng_max * 1000000)->get();		
		
		//echo "\r\n3\r\n";
		//echo Carbon::now();
		
		//echo("cols");
		//echo count($cols_);

        foreach ($cols_ as $col_) {
            foreach ($coords as $coord) {
                $d = distance($col_->Latitude / 1000000, $col_->Longitude / 1000000, $coord[0], $coord[1], "K");

                if ($d < 0.2) {
					//echo "col";
					//echo $col_->Col;
					
					$col__ = new \stdClass();
					$col__->ColID = $col_->ColID;
					$col__->ActivityID = $activityId;

					$cols[] = $col__;

                    break;
                }
            }
        }
		
		//echo count($cols);
		
		//echo "\r\n4\r\n";
		//echo Carbon::now();

        return $cols;
    }
	
    private function saveCols($cols)
    {
		
	    foreach ($cols as $col) {
			$now = Carbon::now('Europe/Amsterdam');

            $array = [];
            $col_ = $this->user->colsAll()->where('cols.ColID', $col->ColID)->first();

            if ($col_ != null) {
                $date = Carbon::today();
                if ($col_->pivot->ClimbedAt) {
                    $date = Carbon::parse($col_->pivot->ClimbedAt);
                }
				
				$stravaActivityIDs = $col_->pivot->StravaActivityIDs;
				//echo "\r\nold:" . $stravaActivityIDs . "\r\n";
				
				if ($stravaActivityIDs == null){
					$stravaActivityIDs = $col->ActivityID;
				} else if (strrpos(";" . $stravaActivityIDs . ";", ";" . $col->ActivityID . ";") === false){
					$stravaActivityIDs .= ";" . $col->ActivityID;
				}
				//echo "\r\nnew:" . $stravaActivityIDs . "\r\n";
				
				$array['UpdatedAt'] = $now;
                $array['StravaActivityIDs'] = $stravaActivityIDs;
                if ($this->date < $date) $array['ClimbedAt'] = $this->date;
				
                $this->user->colsAll()->updateExistingPivot($col->ColID, $array, false);
            } else {
                $array['UpdatedAt'] = $now;
                $array['CreatedAt'] = $now;
                $array['ClimbedAt'] = $this->date;
                $array['StravaNew'] = true;
                $array['StravaActivityIDs'] = $col->ActivityID;
                $this->user->cols()->attach($col->ColID, $array);
            }
        }
	}
	
	private function getColsQuery($lat_min, $lng_min, $lat_max, $lng_max)
    {
        return Col::where('Latitude', '>=', $lat_min - 1000)
            ->where('Latitude', '<=', $lat_max + 1000)
            ->where('Longitude', '>=', $lng_min - 1000)
            ->where('Longitude', '<=', $lng_max + 1000);
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
}
