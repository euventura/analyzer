<?php

namespace App\Jobs;

use App\Models\Entity;
use App\Services\Vedastro\Vedastro;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessEntities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $entity;
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
    public function handle() : array
    {
        $mapService = new Vedastro();
        $mapService->setDatetime($this->entity->birth_date);
        $mapService->setLocation($this->entity->coordinates);
        $mapService->setTimeZone($this->entity->timezone);
        dd($mapService->getMap());
        return $mapService->getData();
    }
}
