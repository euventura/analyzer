<?php

namespace App\Console\Commands;

use App\Models\PoolEntity;
use Illuminate\Console\Command;

class addEntityToPoll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pool:add {url}';

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
        $model = PoolEntity::create(['url' => $this->argument('url')]);
        $this->info($model);
        return 1;
    }
}
