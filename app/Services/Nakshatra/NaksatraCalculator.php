<?php

namespace App\Services\Nakshatra;

class NakshatraCalculator 
{

    protected $list = [];
    public function __construct()
    {
        $this->list = require 'list.php';
    }

    public function calc(Int $rasi, float $degree)
    {
        $fullDegree = (30 * $rasi) + $degree;
        $nakshatraNumber = round($fullDegree / 13.2);
        $pada = round($fullDegree / 3.2);
        $nakshatra = $this->list[round($nakshatraNumber)];
        
        return [
            'nakshatra' => $nakshatra,
            'number' => $nakshatraNumber,
            'pada' => $pada
        ];
    }
}