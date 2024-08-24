@extends('layouts.app')

@section('title', 'Historial de Métricas')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Historial de Métricas</h1>

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">URL</th>
                <th class="py-2 px-4 border-b">Estrategia</th>
                <th class="py-2 px-4 border-b">Performance</th>
                <th class="py-2 px-4 border-b">Accesibilidad</th>
                <th class="py-2 px-4 border-b">PWA</th>
                <th class="py-2 px-4 border-b">SEO</th>
                <th class="py-2 px-4 border-b">Mejores Prácticas</th>
                <th class="py-2 px-4 border-b">Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($metrics as $metric)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $metric->url }}</td>
                    <td class="py-2 px-4 border-b">{{ $metric->strategy->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $metric->performance_metric ?? 'N/A' }}</td>
                    <td class="py-2 px-4 border-b">{{ $metric->accesibility_metric ?? 'N/A' }}</td>
                    <td class="py-2 px-4 border-b">{{ $metric->pwa_metric ?? 'N/A' }}</td>
                    <td class="py-2 px-4 border-b">{{ $metric->seo_metric ?? 'N/A' }}</td>
                    <td class="py-2 px-4 border-b">{{ $metric->best_practices_metric ?? 'N/A' }}</td>
                    <td class="py-2 px-4 border-b">{{ $metric->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
