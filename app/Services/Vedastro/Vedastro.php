<?php

namespace App\Services\Vedastro;
use Carbon\Carbon;
use GuzzleHttp\Client;

class Vedastro
{
    protected $baseUri = 'https://vedastroapi.azurewebsites.net/api/Calculate/';



    //AllTimeData/Location/S%C3%A3oPaulo,StateofS%C3%A3oPaulo,Brazil/Time/00:00/13/06/1988/-03:00

    protected $dateTime;

    protected $location;

    protected $timezone;

    protected function buildClient()
    {
        return new Client([
            'headers' => [
                'Content-Type'   => 'application/json',
            ]
        ]);
    }

    public function request($url)
    {
        try {
            $client = $this->buildClient();
            return $this->handleResponse($client->request('GET', $this->baseUri . '/' . $url));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected function handleResponse($response) : array
    {
        $body = json_decode($response->getBody(), true);
        if ($body['Status'] == 'Pass'){
            return $body['Payload'];
        }
        throw new \Exception("Error Processing Request", 1);
        
    }

    public function getMap() : array
    {

        return $this->request($this->buildUrl('AllTimeData'))['AllTimeData'];
    }

    public function getDasha() : array
    {
        return $this->request($this->buildUrl('DasaForLife', '/Levels/2/ScanYears/120/PrecisionHours/120'));
    }

    public function buildUrl($url, $endUrl = '')
    {
        $url .= '/Location/' . implode(',', $this->location) . '/';
        $url .= 'Time/' . $this->dateTime->format('H:i') . '/' . $this->dateTime->format('d/m/Y') . '/' . $this->timezone . '/' . $endUrl;
        return $url;
    }

    public function setDatetime(Carbon $datetime)
    {
        $this->dateTime = $datetime;
        $this->timezone = $datetime->getTimezone();
    }

    public function setLocation($latLong)
    {
        $this->location = [$latLong['lat'], $latLong['long']];
    }

    public function setTimezone($timezone)
    {
        if($timezone > 12 || $timezone < -12) {
            $timezone = $timezone / 10;
        }

        $this->timezone = $timezone;
    }



}