<?php

namespace App\Services\Pool;

use App\Models\Entity;
use App\Models\PoolEntity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PoolNormalize
{
    protected $entity;

    protected $poolEntity;
    public function run(PoolEntity $poolEntity)  : PoolEntity
    {
        $this->poolEntity = $poolEntity;
        $this->entity = new Entity();
        $this->setName();
        $this->setBirthTime();
        $this->setBirthPlace();
        //$bio = $this->setBiography();
        $features = $this->setCategories();
        $events = $this->setEvents();
        $this->entity->save();
        $this->entity->properties()->createMany($features);
        $this->entity->events()->createMany($events);
        $this->poolEntity->status = 'done';
        $this->poolEntity->save();
        return $this->poolEntity;
        //throw new \Exception('fail on FileGetContents()');
    }

    protected function setName()
    {
        $data = $this->poolEntity->info;
        $this->entity->name = $data['name'];
        // if (isset($data['birthname'])) {
        //     return  $data['birthname'];
        // }
        // return

    }
    protected function setBirthTime()
    {
        $tz = explode('h', $this->poolEntity->info['timezone']);
        $tz2 = explode(' ', $tz[1]);
        $type = substr($tz2[0], strlen($tz2[0]) - 1, 1);
        $number = str_replace($type, '', $tz2[0]);
        if($type == 'w') {
            $number = $number * -1;
        }
        $fulltimezone = $number . '00';
        
        if (trim($tz[0]) == 'IST') {
            $fulltimezone = 'IST';
            $number = 53;
        }

        $born = $this->poolEntity->info['born on'];
        $bornTemp = explode(' (', $born);
        $formated = Carbon::createFromFormat('d F Y \a\t H:i', trim($bornTemp[0]), $fulltimezone);
        $this->entity->birth_date = $formated;
        $this->entity->timezone = $number;

    }

    public function setBirthPlace()
    {
        $places = explode(',',$this->poolEntity->info['place']);
        $city = $places[0];
        $country = $places[1];
        $location = [$this->DMStoDEC($places[2]), $this->DMStoDEC($places[3])];
        $this->entity->birth_place = $city;
        $this->entity->birth_country = $city;
        $this->entity->coordinates = DB::raw("ST_GeomFromText('POINT($location[0] $location[1])', 4326)") ;
        
    }

    protected function setBiography()
    {
        $biography = trim(implode('\n\l', $this->poolEntity->info['biography']));
        return $biography;
        
    }
    protected function setEvents()
    {
        $events = $this->poolEntity->info['events'];
        $insertData = [];
        foreach($events as $event) {
            $category = '_';
            $eventData = explode(':', $event);
            $line = $eventData[0];
            if (count($eventData) == 2) {
                $category = $eventData[0];
                $line = $eventData[1];
            }

            $lineData = str_split($line);
            $description = '';
            $date = '';
            $extra = '';
            $numberFound = false;
            $extraStart = false;
            foreach ($lineData as $char) {

                if ($char == ')') {
                    break;
                }

                if ($char == '(') {
                    $extraStart = true;
                    continue;
                }

                if ($extraStart) {
                    $extra = $extra . $char;
                    $numberFound = false;
                    $extraStart = true;
                    continue;
                }

                if (is_numeric($char) || $numberFound) {
                    $date = $date . $char;
                    $numberFound = true;
                    continue;
                }
                $description = $description . $char;
            }
            $date = trim($date);
            if (strlen($date) == 4) {
                $date = '01 January ' . $date;
            }
            try {
                $formatedDate = Carbon::createFromFormat('d F Y', trim($date));

            } catch (\Throwable $th) {
                continue;
            }

            $insertData[] = [
                'description' => trim($extra),
                'category' => $category,
                'name' => $description,
                'when' => Carbon::createFromFormat('d F Y', trim($date))->setTime(0,0,0,0)
            ];
        }
        return $insertData;
    }

    protected function setCategories()
    {
        $events = $this->poolEntity->info['categories'];
        $insertData = [];
        $events = array_filter($events);
        foreach ($events as $feature) {
            $tempData = explode(':', $feature);
            if (count($tempData) < 3){
                print_r($feature);
                continue;
            }

            $insertData[] = [
                'category' => str_replace("\u{A0}", '', trim($tempData[0])),
                'name' => str_replace("\u{A0}", '', trim($tempData[1])),
                'value' => str_replace("\u{A0}", '', trim($tempData[2])),
            ];
        }
        return $insertData;
    }
    function DMStoDEC($value)
    {

        $value = trim($value);
        $transformer = 1;
        $deg = substr($value,0, 2);
        $location = substr($value,2, 1);
        $min = substr($value,3, 2);
        $sec = substr($value,5, 2);
        if(!$sec) {
            $sec = 0;
        }
        if (in_array($location, ['s', 'w'])) {
            $transformer = -1;
        }

        return ($deg+((($min*60)+($sec))/3600)) * $transformer;
    }    
}