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

        const days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        $.each(days, function(key, day) {
            let countKBM = $('#' + day + ' .total-' + day + ':last').val();
            for(let i = 0; i <= countKBM; i++) {
                $('select').select2({ theme: 'bootstrap4' });
                let val_of_guru = $('#val-guru-' + day + '-' + i).val();
                $('#guru-' + day + '-' + i).val(val_of_guru);
                let val_of_mapel = $('#val-mapel-' + day + '-' + i).val();
                $('#mapel-' + day + '-' + i).val(val_of_mapel);
            };
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

        $('#change-form').on('submit', function(event) {
            event.preventDefault(); 

            setLoading();

            let schedule_data = [];
            let success = 0;
            $.each(days, function(index, day) {
                let totalHours = 0;
                totalHours += (Number($('#' + day + ' .total-' + day + ':last').val()) + 1);

                for(let i = 0; i < totalHours; i++) {
                    let schedule = [];
                    schedule.push($('input[name="id_on_' + day + '[]"]').eq(i).val());
                    schedule.push($('select[name="mapel_' + day + '[]"]').eq(i).val());
                    schedule.push($('select[name="guru_' + day + '[]"]').eq(i).val());
                    schedule.push($('input[name="start_' + day + '[]"]').eq(i).val());
                    schedule.push($('input[name="end_' + day + '[]"]').eq(i).val());
                    schedule_data.push(schedule);
                };
            });

            mainData = {
                id_jadwal: $('[name="id_jadwal"]').val(),
                nama_jadwal: $('[name="nama_jadwal"]').val(),
                deskripsi_jadwal: $('[name="deskripsi_jadwal"]').val(),
                status: $('[name="status"]').val()
            };
            $.ajax({
                url: 'main',
                contentType: 'application/json',
                data: JSON.stringify(mainData),
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
                }
            });

            parseSchedule = {schedule_group: schedule_data};
            $.ajax({
                url: 'schedule',
                contentType: 'application/json',
                data: JSON.stringify(parseSchedule),
                type: 'post',
    
                success: function(schedule_result) {
                    let dumpErr = '';
                    for (let key in schedule_result.notification) {
                        if (schedule_result.notification.hasOwnProperty(key)) {
                            dumpErr += schedule_result.notification[key];
                        };
                    };
                    $('#toast-container').append(dumpErr);
                            
                    function removeEl() {
                        $('.toast').remove();
                    }
                    setTimeout(removeEl, 4000);
                }
            });

            removeLoading();
        });
    });
</script>