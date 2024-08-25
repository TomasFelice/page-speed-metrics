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
            if (app.locale == 'en') {
                app.locale = 'es';
            } else {
                app.locale = 'en';
            }
            location.reload();
        })
    });
</script>