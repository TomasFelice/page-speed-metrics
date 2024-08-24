<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetricHistoryRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'strategy_id',
        'url',
        'accessibility_metric',
        'pwa_metric',
        'performance_metric',
        'best_practices_metric',
        'seo_metric',
    ];

    public function strategy()
    {
        return $this->belongsTo(Strategy::class);
    }

    public function getAverageMetricAttribute()
    {
        return ($this->accessibility_metric + $this->pwa_metric + $this->performance_metric + $this->best_practices_metric + $this->seo_metric) / 5;
    }

}
