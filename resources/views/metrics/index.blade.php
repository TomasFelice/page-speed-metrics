@extends('layouts.app')

@section('title', __('messages.app_name'))

@section('content')

@include('metrics.pre-script')

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">{{ __('messages.metrics_history') }}</h1>

    <table id="metrics-table" class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">{{ __('messages.url') }}</th>
                <th class="py-2 px-4 border-b">{{ __('messages.strategy') }}</th>
                <th class="py-2 px-4 border-b">{{ __('messages.performance') }}</th>
                <th class="py-2 px-4 border-b">{{ __('messages.accessibility') }}</th>
                <th class="py-2 px-4 border-b">{{ __('messages.seo') }}</th>
                <th class="py-2 px-4 border-b">{{ __('messages.best_practices') }}</th>
                <th class="py-2 px-4 border-b">{{ __('messages.created_at') }}</th>
                <th class="py-2 px-4 border-b">{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($metrics as $metric)
            <tr>
                <td class="py-2 px-4 border-b">{{ $metric->url }}</td>
                <td class="py-2 px-4 border-b">{{ $metric->strategy->name }}</td>
                <td class="py-2 px-4 border-b">{{ $metric->performance_metric ?? 'N/A' }}</td>
                <td class="py-2 px-4 border-b">{{ $metric->accessibility_metric ?? 'N/A' }}</td>
                <td class="py-2 px-4 border-b">{{ $metric->seo_metric ?? 'N/A' }}</td>
                <td class="py-2 px-4 border-b">{{ $metric->best_practices_metric ?? 'N/A' }}</td>
                <td class="py-2 px-4 border-b">{{ $metric->created_at->format('Y-m-d H:i:s') }}</td>
                <td class="py-2 px-4 border-b text-center">
                    <button data-id="{{ $metric->id }}" class="flex justify-center items" onclick="destroyMetric({{$metric->id}})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('metrics.scripts')

@endsection
