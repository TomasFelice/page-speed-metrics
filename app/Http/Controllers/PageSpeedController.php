<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MetricHistoryRun;
use App\Models\Strategy;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class PageSpeedController extends Controller
{

    protected $PAGE_SPEED_URL = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';

    public function index()
    {
        $categories = Category::all();
        $strategies = Strategy::all();

        return view('pagespeed.index', compact('categories', 'strategies'));
    }

    public function fetchMetrics(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'categories' => 'required|array',
            'strategy' => 'required|string|exists:strategies,name'
        ]);

        // $queryParams = [
        //     'url' => $validated['url'],
        //     'strategy' => $validated['strategy'],
        //     'key' => env('GOOGLE_API_KEY')
        // ];

        // foreach ($validated['categories'] as $category) {
        //     $queryParams['category'][] = $category;
        // }

        // $queryParams = http_build_query($queryParams);

        $queryParams = 'url=' . $validated['url'] . '&key=' . env('GOOGLE_API_KEY') . '&strategy=' . $validated['strategy'];

        foreach ($validated['categories'] as $category) {
            $queryParams .= '&category=' . $category;
        }

        error_log('Query Params: ' . $queryParams);

        try {
            $client = new Client(['verify' => false]);
            // $response = $client->request('GET', $this->PAGE_SPEED_URL, [
            //     'query' => $queryParams
            // ]);

            $response = $client->request('GET', $this->PAGE_SPEED_URL . '?' . $queryParams);

            if ($response) {
                $contents = json_decode($response->getBody()->getContents());
                $metrics = $contents->lighthouseResult->categories;
                error_log('Metrics: ' . print_r($contents, true));
                $data = [];
                foreach ($metrics as $key => $metric) {
                    $data[$key] = $metric->score;
                }
            }

            error_log('Response: ' . print_r($data, true));

            echo json_encode([
                'success' => true,
                'data' => $data,
                'status' => 200,
            ]);

        } catch (GuzzleException $e) {
            echo json_encode([
                'success' => false,
                'data' => null,
                'status' => 500,
                'error' => $e->getMessage()
            ]);
        }
    }


    public function storeMetricRun(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'strategy' => 'required|exists:strategies,name',
            'metrics' => 'required|array'
        ]);

        $strategyId = Strategy::where('name', $validated['strategy'])->first()->id;

        $metrics = [];
        foreach ($validated['metrics'] as $key => $metric) {
            $metrics[$metric['name']] = $metric['value'];
        }
        
        $metricRun = MetricHistoryRun::create([
            'url' => $validated['url'],
            'strategy_id' => $strategyId,
            'accesibility_metric' => $metrics['ACCESSIBILITY'] ?? null,
            'pwa_metric' => $metrics['PWA'] ?? null,
            'performance_metric' => $metrics['PERFORMANCE'] ?? null,
            'seo_metric' => $metrics['SEO'] ?? null,
            'best_practices_metric' => $metrics['BEST_PRACTICES'] ?? null,
        ]);

        echo json_encode([
            'success' => true,
            'data' => $metricRun,
            'status' => 200
        ]);
    }

}
