<?php

namespace App\Http\Services\FreeAstrologyApi;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class FreeAstrologyApi {

    /**
     * ApiKey
     *
     * @param [string] $apiKey
     */
    protected $apiKey;

    protected $ayanansha = 'lahiri';

    public function __construct(String $apiKey, $ayanansha = null) 
    {
        $this->apiKey = $apiKey;

        if ($ayanansha) {
            $this->ayanansha = $ayanansha;
        }
    }

    protected function buildClient()
    {
        return new Client([
            'base_uri' => 'https://json.freeastrologyapi.com',
            'headers' => [
                'Content-Type'     => 'application/json',
                'x-api-key'      => $this->apiKey
            ]
        ]);
    }

    public function request($url, $chartData)
    {
        $chartData['settings']['ayanansa'] = $this->ayanansha;
        $chartData['settings']['observation_point'] = 'topocentric';
        try {
            $client = $this->buildClient();
            return $this->handleResponse($client->request('GET', $url, $chartData));
        } catch (\Throwable $th) {
            throw $$th;
        }
    }

    protected function handleResponse(ResponseInterface $response) : array
    {
        return json_decode($response->getBody(), true);
    }

    public function getChart(string $division, array $chartData) : array
    {
        $division = 'd' . $division;
        
        if ($division == '9') {
            $division = 'navansa';
        }

        $url = $division . '-chart-info';

        if ($division =='1' || $division =='rasi') {
            $url= 'planets';
        }

        return $this->request($url, $chartData);
    }

}