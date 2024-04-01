<?php

namespace App\Services\Pool;

use App\Models\PoolEntity;

class PoolDownload
{
    public function run(PoolEntity $entity)  : PoolEntity
    {
        $content = file_get_contents($entity->url);
        if ($content) {
            $entity->page_content = $content;
            $entity->status = 'content';
            $entity->save();
            return $entity;
        }

        throw new \Exception('fail on FileGetContents()');
    }
}