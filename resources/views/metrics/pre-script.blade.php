<script>
    function destroyMetric(id) {
        Swal.fire({
            title: '{{ __("messages.are_you_sure") }}',
            text: '{{ __("messages.you_wont_be_able_to_revert_this") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __("messages.yes_delete_it") }}',
            cancelButtonText: '{{ __("messages.cancel") }}',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/metrics/' + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                '{{ __("messages.deleted") }}',
                                '{{ __("messages.metric_deleted") }}',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error',
                                '{{ __("messages.something_went_wrong") }}',
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error',
                            '{{ __("messages.something_went_wrong") }}',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
