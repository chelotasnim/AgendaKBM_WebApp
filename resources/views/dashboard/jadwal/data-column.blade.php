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
        
        let cloneCount = 0;
        let removeCount = 0;

        const jamAwal = {
            senin: 0,
            selasa: 0,
            rabu: 0,
            kamis: 0,
            jumat: 0
        };

        const jumlahJam = {
            senin: 0,
            selasa: 0,
            rabu: 0,
            kamis: 0,
            jumat: 0
        };

        const days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        $.each(days, function(index, day) {
            let totalHours = (Number($('#' + day + ' .total-' + day + ':last').val()) + 1);
            jamAwal[day] = totalHours;
            jumlahJam[day] = totalHours;
            $('#' + day + '-container-n').attr('id', day + '-container-' + totalHours);
            const latestContainer = $('#' + day + '-container-' + totalHours);
            $('.jam_ke_' + day, latestContainer).addClass('jam_new_' + day);
            $('.jam_new_' + day, latestContainer).removeClass('jam_ke_' + day);
            $('.jam_new_' + day, latestContainer).val(totalHours);
            $('.time-count', latestContainer).html(totalHours);
            $('#' + 'guru-' + day + '-n').attr('id', 'guru-' + day + '-' + totalHours);
            $('#' + 'mapel-' + day + '-n').attr('id', 'mapel-' + day + '-' + totalHours);

            $('select', latestContainer).select2({ theme: 'bootstrap4' });
        });

            $('.clone-btn').click(function() {
                jumlahJam[$(this).attr('data-day')]++;
                cloneCount = jumlahJam[$(this).attr('data-day')];

                if(cloneCount < jamAwal[$(this).attr('data-day')]) {
                    $('#remove-in-' + $(this).attr('data-day')).attr('style', 'display:none');
                } else {
                    $('#remove-in-' + $(this).attr('data-day')).attr('style', 'display:flex;gap:8px');
                };

                const clonedBlock = $('#' + $(this).attr('data-day') + '-container-' + (cloneCount - 1)).clone();
                clonedBlock.attr('id', $(this).attr('data-day') + '-container-' + cloneCount);
                clonedBlock.find('#guru-' + $(this).attr('data-day') + '-' + (cloneCount - 1)).attr('id', 'guru-' + $(this).attr('data-day') + '-' + cloneCount);
                clonedBlock.find('#mapel-' + $(this).attr('data-day') + '-' + (cloneCount - 1)).attr('id', 'mapel-' + $(this).attr('data-day') + '-' + cloneCount);
                clonedBlock.find('.select2').remove();
                clonedBlock.find('input').val('');
                clonedBlock.find('select').prop('selectedIndex', 0);
                clonedBlock.find('.time-count').text(cloneCount);
                clonedBlock.find('.jam_new_' + $(this).attr('data-day')).val(cloneCount);
                $('#' + $(this).attr('data-day') + '-container-' + (cloneCount - 1)).after(clonedBlock);
                clonedBlock.find('select').select2({ theme: 'bootstrap4' });
                $('select').select2({ theme: 'bootstrap4' });

                flatpickr('.as-time', {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true
                });
            });

            $('.remove-btn').click(function() {
                removeCount = jumlahJam[$(this).attr('data-day')];
                jumlahJam[$(this).attr('data-day')]--;

                $('#' + $(this).attr('data-day') + '-container-' + removeCount).remove();

                if(removeCount <= jamAwal[$(this).attr('data-day')] + 1) {
                    $('#remove-in-' + $(this).attr('data-day')).attr('style', 'display:none');
                } else {
                    $('#remove-in-' + $(this).attr('data-day')).attr('style', 'display:flex;gap:8px');
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

        $('#column-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            let error = '';
            $.each(days, function(index, day) {
                const totalGuru = $('select[name="guru_' + day + '[]"]');
                let countTotalGuru = 0;
                totalGuru.each(function() {
                    if($(this).val() !== null) {
                        countTotalGuru++;
                    };
                });
    
                const totalMapel = $('select[name="mapel_' + day + '[]"]');
                let countTotalMapel = 0;
                totalMapel.each(function() {
                    if($(this).val() !== null) {
                        countTotalMapel++;
                    };
                });
    
                const totalStar = $('input[name="start_' + day + '[]"]');
                let countTotalStart = 0;
                totalStar.each(function() {
                    if($(this).val() !== '') {
                        countTotalStart++;
                    };
                });
    
                const totalEnd = $('input[name="end_' + day + '[]"]');
                let countTotalEnd = 0;
                totalEnd.each(function() {
                    if($(this).val() !== '') {
                        countTotalEnd++;
                    };
                });
    
                const countTotal = [countTotalGuru, countTotalMapel, countTotalStart, countTotalEnd];
                const maxData = Math.max.apply(null, countTotal);
                const minData = Math.min.apply(null, countTotal);
    
                if(maxData == minData) {
                    if(maxData != 0 && minData != 0) {
                        let schedule_data = [];
                        for(let i = 0; i < maxData; i++) {
                            let schedule = [];
                            schedule.push($('select[name="mapel_' + day + '[]"]').eq(i).val());
                            schedule.push($('select[name="guru_' + day + '[]"]').eq(i).val());
                            schedule.push(day);
                            schedule.push($('input[name="jam_new_' + day + '[]"]').eq(i).val());
                            schedule.push($('input[name="start_' + day + '[]"]').eq(i).val());
                            schedule.push($('input[name="end_' + day + '[]"]').eq(i).val());
                            schedule_data.push(schedule);

                        };

                        parseSchedule = {schedule_group: schedule_data, nama_jadwal: $('[name="main_jadwal"]').val()};
                        $.ajax({
                            url: "{{ url('dashboard/jadwal/store/schedule') }}",
                            contentType: 'application/json',
                            data: JSON.stringify(parseSchedule),
                            type: 'post'
                        });
                    };
                };
            });
            window.location.reload();
        });
    });
</script>