<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('metric_history_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strategy_id')->constrained(
                table: 'strategies',
                column: 'id',
                indexName: 'fk_metric_history_runs_strategy_id'
            );
            $table->decimal('url', 4, 2);
            $table->decimal('accessibility_metric', 4, 2);
            $table->decimal('pwa_metric', 4, 2);
            $table->decimal('performance_metric', 4, 2);
            $table->decimal('best_practices_metric', 4, 2);
            $table->decimal('seo_metric', 4, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metric_history_runs');
    }
};
