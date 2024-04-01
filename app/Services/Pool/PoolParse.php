<?php

namespace App\Services\Pool;

use App\Models\PoolEntity;

class PoolParse
{
    protected $data = [];

    protected $content;
    public function run(PoolEntity $entity) : PoolEntity
    {
        $data = [];
        if ($entity->status != 'content') {
            throw new \Exception("Invalid Content", 1);
            
        }
        $this->content = new \DOMDocument();
        $this->content->loadHTML($entity->page_content, LIBXML_NOERROR);
        $this->parseInfo();
        $this->parseBio();
        $entity->info = $this->data;
        $entity->status = 'info';
        $entity->save();
        return $entity;

    }
    protected function parseInfo()
    {
        $xPath = new \DOMXPath($this->content);
        $r =  $xPath->query("//*[@id=\"mw-content-text\"]/div[1]/table[1]/tbody/tr");

        foreach($r as $el) {
            $lastType = null;
            foreach ($el->childNodes as $child) {

                if (!trim($child->nodeValue)) {
                    continue;
                }

                if ($child->nodeValue == 'Data source' || $child->nodeValue == 'Astrology data') {
                    break;
                }
                
                if ($lastType == null) {
                    $lastType = strtolower($child->nodeValue);
                    continue;
                }
                

                if ($lastType == 'name') {
                    $tempInfo = array_values(array_filter( explode("\n", $child->nodeValue)));
                    $this->data[$lastType] = $tempInfo[0];
                    $gender = explode(':', $tempInfo[1]);
                    $this->data[strtolower($gender[0])] = $gender[1];
                    continue;
                }
                $this->data[$lastType] = trim($child->nodeValue);
                $lastType = null;

            }
        }
    }

    protected function parseBio()
    {
        $xPath = new \DOMXPath($this->content);
        $r =  $xPath->query("//*[@id=\"mw-content-text\"]/div[1]");

        $dataIndex = null;
        foreach($r[0]->childNodes as $el) {
            if (! $el instanceof \DOMElement) {
                continue;
            }

            if($el->tagName == 'h2')
            {
                $dataIndex = \Str::slug($el->nodeValue);
                continue;
            }

            if ($dataIndex)
            {
                $this->data[$dataIndex][] = $el->nodeValue; 
            }
        }
    }

    protected function parseRelation()
    {

    }

    protected function parseEvents()
    {

    }
    protected function parseCategories()
    {
        
    }

}