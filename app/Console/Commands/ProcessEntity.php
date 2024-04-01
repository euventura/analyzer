<?php

namespace App\Console\Commands;

use App\Jobs\ProcessEntities;
use App\Models\Entity;
use Illuminate\Console\Command;

class ProcessEntity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-entity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $entity = Entity::where('status', 'pending')
        ->limit(1)->get();
        $entity = $entity[0];
        ProcessEntities::dispatch($entity);
        $this->info('Add job to processing enity');
    }
}
