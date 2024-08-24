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
            // 'categories' => 'required|array',
            'strategy' => 'required|string|exists:strategies,name'
        ]);

        $queryParams = [
            'url' => $validated['url'],
            'strategy' => $validated['strategy'],
            'key' => env('GOOGLE_API_KEY')
        ];

        // foreach ($validated['categories'] as $category) {
        //     $queryParams['category'][] = $category;
        // }
        foreach (Category::all() as $category) {
            $queryParams['category'][] = $category;
        }

        try {
            $client = new Client();
            $response = $client->request('GET', $this->PAGE_SPEED_URL, [
                'query' => $queryParams
            ]);

            if ($response) {
                $response = json_decode($response->getBody()->getContents());
                $metrics = $response->lighthouseResult->categories;

                $data = [];
                foreach ($metrics as $key => $metric) {
                    $data[$key] = $metric->score;
                }
            }


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
            'strategy_id' => 'required|exists:strategies,id',
            'metrics' => 'required|array'
        ]);

        $metricRun = MetricHistoryRun::create([
            'url' => $validated['url'],
            'strategy_id' => $validated['strategy_id'],
            'accesibility_metric' => $validated['metrics']['ACCESSIBILITY'] ?? null,
            'pwa_metric' => $validated['metrics']['PWA'] ?? null,
            'performance_metric' => $validated['metrics']['PERFORMANCE'] ?? null,
            'seo_metric' => $validated['metrics']['SEO'] ?? null,
            'best_practices_metric' => $validated['metrics']['BEST_PRACTICES'] ?? null,
        ]);

        echo json_encode([
            'success' => true,
            'data' => $metricRun,
            'status' => 200
        ]);
    }

}
