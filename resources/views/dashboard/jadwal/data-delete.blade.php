<script>
    $(document).ready(function() {
        $('select').select2({ theme: 'bootstrap4' });

        flatpickr('.as-time', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let schedule = [];
        $('.remove-btn').on('click', function() {
            schedule.push($(this).parent().parent().prev().attr('data-schedule-id'));
            $(this).parent().parent().prev().remove();
        });

        function setLoading() {
            $('body').append(`
                <div class="loading-animation">
                    <i class="fas fa-spinner-third"></i>
                </div>
            `);
        };

        function removeLoading() {
            $('.loading-animation').remove();
        };

        parseSchedule = { schedules_id: schedule };
        $('#delete-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            $.ajax({
                url: "{{ url('dashboard/jadwal/delete/schedule') }}",
                contentType: 'application/json',
                data: JSON.stringify(parseSchedule),
                type: 'post',
                success: function(result) {
                    let dumpErr = '';
                    for (let key in result.notification) {
                        if (result.notification.hasOwnProperty(key)) {
                            dumpErr += result.notification[key];
                        };
                    };
                    $('#toast-container').append(dumpErr);
                        
                    function removeEl() {
                        $('.toast').remove();
                    }
                    setTimeout(removeEl, 4000);

                    removeLoading();
                }
            });
        });
    });
</script>