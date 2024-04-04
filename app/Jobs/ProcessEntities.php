<?php

namespace App\Jobs;

use App\Models\Entity;
use App\Models\Graha;
use App\Services\Vedastro\Vedastro;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Math;

class ProcessEntities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $entity;

    protected $dashaData;

    protected $mapData;

    protected $divisions = [
        1 => 'AllPlanetSigns',
        2 => 'AllPlanetHoraSign',
        3 => 'AllPlanetDrekkanaSign',
        4 => 'AllPlanetChaturthamsaSign',
        5 => 'AllPlanetPanchamsaSign',
    ];
    /**
     * Create a new job instance.
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $mapService = new Vedastro();
        $mapService->setDatetime($this->entity->birth_date);
        $mapService->setLocation($this->entity->coordinates);
        $mapService->setTimeZone($this->entity->timezone);
        $this->mapData = $mapService->getMap();
        // AllPlanetStrength
        //HouseAllPlanetOccupies
        // AllPlanetConstellation
        // AllHouseSign
    }

    protected function buildBhavas()
    {

        foreach($this->mapData['AllHouseSign'] as $houseNumber => $houseData) {
            $singleHouseData = explode(' : ', $houseData);
            $sign = $singleHouseData[0];
            $degree = explode(' ', $singleHouseData[1]);
            $degree = Math::DmstoDec(filter_var($degree[0], FILTER_SANITIZE_NUMBER_INT), filter_var($degree[1], FILTER_SANITIZE_NUMBER_INT), filter_var($degree[2], FILTER_SANITIZE_NUMBER_INT));

        }
    }

    protected function buildPlanets()
    {
        foreach($this->divisions as $division => $dataIndex)
        {
            $planetData = $this->mapData[$dataIndex];
            $graha = new Graha();
        }
    }
}
