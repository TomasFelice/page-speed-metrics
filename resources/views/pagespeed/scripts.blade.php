<script type="module">
    // Defino la variable global para el chart
    let chart;

    function getColor(score) {

        if (score >= 0.9) {
            return 'rgba(54, 162, 235, 0.5)';
        } else if (score >= 0.7) {
            return 'rgba(255, 206, 86, 0.5)';
        } else if (score >= 0.4) {
            return 'rgba(255, 99, 132, 0.5)';
        } else {
            return 'rgba(255, 99, 132, 0.5)';
        }
    }

    function createChart(data) {

        chart = new Chart(document.getElementById('chart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: Object.keys(data),
                datasets: [{
                    label: '{{ __("messages.results") }}',
                    data: Object.values(data),
                    backgroundColor: Object.values(data).map(value => getColor(value)),
                    borderColor: Object.values(data).map(value => getColor(value).replace('0.5', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 1
                    }
                }
            }
        });
    }

    $(document).ready(function() {
        $('#strategy').select2({
            placeholder: "{{ __('messages.select_strategy') }}",
            allowClear: true
        });

        $('#fetch-metrics').click(function() {

            const url = $('#url').val();
            const categories = $('input[name="category"]:checked').map(function() {
                return $(this).val();
            }).get();
            const strategy = $('#strategy').val();

            if (!url || categories.length === 0 || !strategy) {
                return Swal.fire('Error', '{{ __("messages.please_complete_all_fields") }}', 'error');
            }

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
                    Swal.fire({
                        title: '{{ __("messages.getting_metrics") }}...',
                        html: '{{ __("messages.please_wait") }}',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    })
                },
                success: function(response) {

                    if (response.error || !response.data) {
                        return Swal.fire('Error', '{{ __("messages.error_getting_metrics") }}', 'error');
                    }

                    if (chart) {
                        chart.destroy();
                    }

                    createChart(response.data);

                    Swal.close();

                    $('#accessibility').val(response.data['accessibility'] || 0);
                    $('#best-practices').val(response.data['best-practices'] || 0);
                    $('#performance').val(response.data['performance'] || 0);
                    $('#seo').val(response.data['seo'] || 0);

                    $('#results').removeClass('hidden');
                    $('#save-metrics').removeClass('hidden');
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    Swal.fire('Error', '{{ __("messages.error_getting_metrics") }}',
                        'error');
                }
            });
        });

        $('#save-metrics').click(function() {
            const url = $('#url').val();
            const strategy = $('#strategy').val();
            const metrics = {
                'ACCESSIBILITY': $('#accessibility').val(),
                'BEST_PRACTICES': $('#best-practices').val(),
                'PERFORMANCE': $('#performance').val(),
                'SEO': $('#seo').val()
            };

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
                        return Swal.fire('Error', response.error, 'error');
                    }

                    Swal.fire('{{ __("messages.success") }}', '{{ __("messages.metrics_saved") }}', 'success');
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', '{{ __("messages.error_saving_metrics") }}',
                        'error');
                }
            });
        });

    });
</script>
