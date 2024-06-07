<?php

namespace App\Services;

use GuzzleHttp\Client;

class WilayahService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getProvinces()
    {
        $response = $this->client->get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getRegencies($provinceId)
    {
        $response = $this->client->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/$provinceId.json");
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getDistricts($regencyId)
    {
        $response = $this->client->get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/$regencyId.json");
        return json_decode($response->getBody()->getContents(), true);
    }
}
