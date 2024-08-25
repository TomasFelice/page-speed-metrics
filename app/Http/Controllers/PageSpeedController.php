<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MetricHistoryRun;
use App\Models\Strategy;
use App\Services\PageSpeedService;
use Illuminate\Http\Request;

class PageSpeedController extends Controller
{

    protected $pageSpeedService;

    /**
     * PageSpeedController constructor. Realizo la inyeccion de dependencias
     *
     * @param PageSpeedService $pageSpeedService
     */
    public function __construct(PageSpeedService $pageSpeedService)
    {
        $this->pageSpeedService = $pageSpeedService;
    }

    /**
     * Carga la pantalla principal para consultar las metricas
     *
     * @return Factory|View
     */
    public function index()
    {
        $categories = Category::all();
        $strategies = Strategy::all();

        return view('pagespeed.index', compact('categories', 'strategies'));
    }

    /**
     * Obtiene la peticion de AJAX y consulta el web service de Google para obtener las metricas
     *
     * @param Request $request
     * @return string
     */
    public function fetchMetrics(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'categories' => 'required|array',
            'strategy' => 'required|string|exists:strategies,name'
        ]);

        $result = $this->pageSpeedService->fetchMetrics($validated);

        return response()->json($result);
    }

    /**
     * Recibe la solicitud vía ajax y almacena la metrica en la base de datos
     *
     * @param Request $request
     * @return string
     */
    public function storeMetricRun(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'strategy' => 'required|exists:strategies,name',
            'metrics' => 'required|array'
        ]);

        $strategyId = Strategy::where('name', $validated['strategy'])->first()->id;
        $metrics = $validated['metrics'];
        
        $metricRun = MetricHistoryRun::create([
            'url' => $validated['url'],
            'strategy_id' => $strategyId,
            'accessibility_metric' => $metrics['ACCESSIBILITY'] ?? null,
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
