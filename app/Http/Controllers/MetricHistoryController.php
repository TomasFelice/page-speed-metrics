<?php

namespace App\Http\Controllers;

use App\Models\MetricHistoryRun;

class MetricHistoryController extends Controller
{
    public function index()
    {
        // El with se utiliza para cargar la relación de la estrategia. Esta funcionalidad la brinda Eloquent.
        $metrics = MetricHistoryRun::with('strategy')->orderBy('created_at', 'desc')->get();
        return view('metrics.index', compact('metrics'));
    }

}
