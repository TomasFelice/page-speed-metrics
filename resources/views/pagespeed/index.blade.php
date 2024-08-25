@extends('layouts.app')

@section('title', 'Obtener Métricas de Google PageSpeed')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Obtener Métricas de Google PageSpeed</h1>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <input type="text" id="url" class="w-full p-2 border border-gray-300 rounded mb-4" placeholder="Ingresa la URL">

        <div class="mb-4">
            <p class="font-semibold mb-2">Categorías:</p>
            <div class="flex flex-wrap">
                @foreach($categories as $category)
                    <label class="inline-flex items-center mr-4 mb-2">
                        <input type="checkbox" name="categories[]" value="{{ $category->name }}" class="form-checkbox">
                        <span class="ml-2">{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mb-4">
            <p class="font-semibold mb-2">Estrategia:</p>
            <select id="strategy" class="w-full p-2 border border-gray-300 rounded">
                @foreach($strategies as $strategy)
                    <option value="{{ $strategy->name }}">{{ $strategy->name }}</option>
                @endforeach
            </select>
        </div>

        <button id="fetch-metrics" class="bg-blue-500 text-white p-2 rounded w-full">Obtener Métricas</button>

        <!-- Aquí se mostrarán los resultados -->
        <div id="results" class="mt-6"></div>

        <button id="save-metrics" class="bg-blue-500 text-white p-2 rounded w-full hidden">Guardar Métricas</button>
    </div>
</div>

<section id="loading-modal">
    <div id="loading-content"></div>
</section>


<style>
.loading {
	z-index: 20;
	position: absolute;
	top: 0;
	left:-5px;
	width: 100%;
	height: 100%;
    background-color: rgba(0,0,0,0.4);
    display: flex;
    justify-content: center;
    align-items: center;
}
.loading-content {
	position: absolute;
	border: 16px solid #f3f3f3; /* Light grey */
	border-top: 16px solid #3498db; /* Blue */
	border-radius: 50%;
	width: 50px;
	height: 50px;
	animation: spin 2s linear infinite;
}
	
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script type="module">
$(document).ready(function() {
    $('#fetch-metrics').click(function() {

        const url = $('#url').val();
        const categories = $('input[name="categories[]"]:checked').map(function() {
            return $(this).val();
        }).get();
        const strategy = $('#strategy').val();

        $.ajax({
            url: "{{ route('pagespeed.fetchMetrics') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                url: url,
                categories: categories,
                strategy: strategy
            },
            dataType: "json",
            beforeSend: function() {
                // Agrego las clases loading y loading-content
                $('#loading-modal').addClass('loading');
                $('#loading-content').addClass('loading-content');
                $('#loading-modal h4').removeClass('hidden');
            },
            success: function(response) {

                $('#loading-modal').removeClass('loading');
                $('#loading-content').removeClass('loading-content');
                $('#loading-modal h4').addClass('hidden');

                if (response.error) {
                    return alert(response.error);
                }

                const obj = response.data;
                let results = '<h2 class="text-xl font-bold mb-4">Resultados</h2>';

                for (const key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        const element = obj[key];
                        results += `<p class="mb-2" data-metric="${key}" data-value="${element}"><span class="font-semibold">${key}:</span> ${element}</p>`;
                    }
                }

                $('#results').html(results);
                $('#save-metrics').removeClass('hidden');
            },
            error: function(xhr, status, error) {
                $('#loading-modal').removeClass('loading');
                $('#loading-content').removeClass('loading-content');
                $('#loading-modal h4').addClass('hidden');
                console.error('Error:', error);
            }
        });
    });

    $('#save-metrics').click(function() {
        const url = $('#url').val();
        const strategy = $('#strategy').val();
        const metrics = $('p[data-metric]').map(function() {
            const name = $(this).attr('data-metric').toUpperCase();
            const value = $(this).attr('data-value');
            // Necesito que name sea el nombre del atributo y el value sea el valor del atributo. Ejemplo: ['PERFORMANCE': 90.01]
            return { name, value };
        }).get();

        console.log(metrics);
        $.ajax({
            url: "{{ route('pagespeed.storeMetricRun') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                url: url,
                strategy: strategy,
                metrics: metrics
            },
            dataType: "json",
            success: function(response) {
                if (response.error) {
                    return alert(response.error);
                }

                alert('Métricas guardadas correctamente');
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
});
</script>
@endsection
