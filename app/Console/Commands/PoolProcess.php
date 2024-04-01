<?php

namespace App\Console\Commands;

use App\Models\PoolEntity;
use App\Services\Pool\PoolDownload;
use App\Services\Pool\PoolNormalize;
use App\Services\Pool\PoolParse;
use Illuminate\Console\Command;

class PoolProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pool:process {status?}';

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
        $entityToProcess = PoolEntity::limit(1)->orderBy('id')->where('status', '<>', PoolEntity::STATUS_DONE);

        if ($this->argument(('status'))) {
            $entityToProcess->where('status', $this->argument(('status')));
        }
        $entityToProcess = $entityToProcess->get();
        $entityToProcess = $entityToProcess->first();
        if (!$entityToProcess)
        {
            $this->info('nothing to process');
            return;
        }
        $entity = $this->router($entityToProcess);
        $this->info($entity->content);
    }

    protected function router(PoolEntity $poolEntity)
    {
        switch ($poolEntity->status) {
            case 'pending':
                $class = new PoolDownload();
                return $class->run($poolEntity);
            case 'content':
                $class = new PoolParse();
                return $class->run($poolEntity);
            case 'info':
                $class = new PoolNormalize();
                return $class->run($poolEntity);
        }

    }
}
