<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageSpeedController;
use App\Http\Controllers\MetricHistoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PageSpeedController::class, 'index'])->name('pagespeed.index');
Route::post('/fetch-metrics', [PageSpeedController::class, 'fetchMetrics'])->name('pagespeed.fetchMetrics');
Route::post('/store-metric-run', [PageSpeedController::class, 'storeMetricRun'])->name('pagespeed.storeMetricRun');

Route::get('/metrics', [MetricHistoryController::class, 'index'])->name('metrics.index');
Route::post('/metrics/{id}', [MetricHistoryController::class, 'destroy'])->name('metrics.destroy');

Route::get('/change-language/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'es'])) {
        session(['locale' => $lang]);
    }
    return redirect()->back();
})->name('change.language');
