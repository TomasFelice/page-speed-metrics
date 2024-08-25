<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class PageSpeedService
{
    protected const API_URL = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Obtiene las metricas de Google PageSpeed
     *
     * @param array $params
     * @return array
     */
    public function fetchMetrics(array $params): array
    {
        $queryParams = 'url=' . $params['url'] . '&key=' . env('GOOGLE_API_KEY') . '&strategy=' . $params['strategy'];

        foreach ($params['categories'] as $category) {
            $queryParams .= '&category=' . $category;
        }

        $response = null;
        try {
            $response = $this->client->request('GET', self::API_URL . '?' . $queryParams);
        } catch (GuzzleException $e) {
            return ['success' => false, 'data' => null, 'status' => 500, 'error' => $e->getMessage()];
        }

        $contents = json_decode($response->getBody()->getContents());

        $metrics = $contents->lighthouseResult->categories;
        $data = [];
        foreach ($metrics as $key => $metric) {
            $data[$key] = $metric->score;
        }

        return ['success' => true, 'data' => $data, 'status' => 200];
    }
}
