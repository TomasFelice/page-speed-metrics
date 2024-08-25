<script type="module">
    $(document).ready(function() {
        $('#metrics-table').DataTable({
            "order": [
                [6, "desc"]
            ],
            "paging": true, 
            "searching": true,
            "info": true,
            "lengthChange": true,
            "language": {
                "url": "{{ __('messages.datatables_language') }}"
            }
        });
    });
</script>
