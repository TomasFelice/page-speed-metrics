<!-- Scripts -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/app.js') }}" type="module"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js" type="module"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#change-language').on('click', function() {
            const currentLocale = '{{ app()->getLocale() }}';
            const newLocale = currentLocale === 'en' ? 'es' : 'en';
            let url = '{{ route('change.language', ['lang' => ':lang']) }}';
            url = url.replace(':lang', newLocale);

            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    _token: '{{ csrf_token() }}',
                    locale: newLocale
                },
                success: function(response) {
                    Swal.fire({
                        title: (
                            newLocale === 'es' ?
                            '{{ __("messages.success", [], "es") }}' :
                            '{{ __("messages.success", [], "en") }}'
                        ),
                        text: (
                            newLocale === 'es' ?
                            '{{ __("messages.new_language_is", [], "es") }}: Español' :
                            '{{ __("messages.new_language_is", [], "en") }}: English'
                        ),
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                },
                error: function() {
                    Swal.fire('Error', '{{ __('messages.error_changing_language') }}',
                        'error');
                }
            });
        })
    });
</script>
