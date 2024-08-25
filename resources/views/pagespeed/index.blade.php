@extends('layouts.app')

@section('title', __('messages.app_name'))

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">{{ __('messages.get_metrics_of_page_speed') }}</h1>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <label for="url" class="font-semibold mb-2">{{ __('messages.url') }}:</label>
            <input type="url" id="url" name="url" class="w-full p-2 border border-gray-300 rounded mb-1"
                placeholder="https://broobe.com" required aria-describedby="urlHelp">
            <small id="urlHelp" class="text-gray-600">{{ __('messages.url_message') }}.</small>

            <div class="mb-4 mt-4">
                <p class="font-semibold mb-2">{{ __('messages.categories') }}:</p>
                <div class="flex flex-wrap">
                    @foreach ($categories as $category)
                        @php
                            $checkboxId = 'category_' . $category->id;
                        @endphp
                        <label for="{{ $checkboxId }}" class="inline-flex items-center mr-4 mb-2">
                            <input type="checkbox" id="{{ $checkboxId }}" name="category"
                                value="{{ $category->name }}" class="form-checkbox">
                            <span class="ml-2">{{ __('messages.'.strtolower($category->name)) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <p class="font-semibold mb-2">{{ __('messages.strategy') }}:</p>
                <select id="strategy" class="w-full p-2 border border-gray-300 rounded">
                    <option value="">{{ __('messages.select_strategy') }}</option>
                    @foreach ($strategies as $strategy)
                        <option value="{{ $strategy->name }}">{{ $strategy->name }}</option>
                    @endforeach
                </select>
            </div>

            <button id="fetch-metrics" class="bg-blue-500 text-white p-2 rounded w-full">{{ __('messages.get_metrics') }}</button>

            <!-- Aquí se mostrarán los resultados -->
            <div id="results" class="mt-6 hidden">
                <h2>{{ __('messages.results') }}</h2>

                <div id="chart-container mt-4">
                    <canvas id="chart"></canvas>
                    <input type="hidden" name="accessibility" id="accessibility">
                    <input type="hidden" name="best-practices" id="best-practices">
                    <input type="hidden" name="performance" id="performance">
                    <input type="hidden" name="seo" id="seo">
                </div>
            </div>

            <button id="save-metrics" class="bg-blue-500 text-white p-2 rounded w-full hidden">{{ __('messages.save_metrics') }}</button>
        </div>
    </div>

    @include('pagespeed.scripts')
@endsection
