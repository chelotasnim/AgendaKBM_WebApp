<script>
    function refreshTable() {
        $('#jadwal-table').DataTable().ajax.reload(null, false);
    };

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

        function removeEl() {
            $('.toast').remove();
        }
        setTimeout(removeEl, 4000);

        $('#jadwal-table').DataTable({
            language: {
                errorAlert: null
            },
            ajax: {
                url: 'get_jadwal/' + $('[name="kelas_id"]').val(),
                dataSrc: '',
                error: function(xhr, status, error) {
                    console.error(error);
                }
            },
            columns: [
                { 
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { 
                    data: 'nama_jadwal'
                },
                { 
                    data: 'deskripsi_jadwal',
                    render: function(data) {
                        if(data != null) {
                            return data;
                        } else {
                            return 'Tidak Ada Deskripsi Jadwal';
                        };
                    }
                },
                { 
                    data: 'status',
                    render: function(data) {
                        if(data == '1') {
                            return '<span class="badge bg-teal">Aktif</span>';
                        } else {
                            return '<span class="badge bg-danger">Tidak Aktif</span>';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        return `<span class="action-group"><a type="button" href="{{ url('dashboard/jadwal/column/` + data.id + `') }}" class="modal-edit-btn btn btn-sm btn-info"><i class="fas fa-calendar-plus"></i></a><a type="button" href="{{ url('dashboard/jadwal/column_remove/` + data.id + `') }}" class="modal-edit-btn btn btn-sm btn-danger"><i class="fas fa-calendar-minus"></i></a><a type="button" href="{{ url('dashboard/jadwal/edit/` + data.id + `') }}" class="modal-edit-btn btn btn-sm btn-warning"><i class="fas fa-edit"></i></a></span>` ;
                    }
                }
            ],
            createdRow: function (row) {
                $('td', row).eq(0).addClass('text-center');
                $('td', row).eq(3).addClass('text-center');
                $('td', row).eq(4).addClass('text-center');
            },
            language: {
                loadingRecords: 'Sedang Mengolah Data...',
                emptyTable: 'Belum Ada Data Jadwal'
            }
        });

        setInterval(refreshTable, 1000);

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
       
        let cloneCount = 0;
        let removeCount = 0;
        const dayHoursCount = {
            senin: 0,
            selasa: 0,
            rabu: 0,
            kamis: 0,
            jumat: 0
        };

            $('.clone-btn').click(function() {
                dayHoursCount[$(this).attr('data-day')]++;
                cloneCount = dayHoursCount[$(this).attr('data-day')];

                if(cloneCount < 1) {
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
                clonedBlock.find('.jam_ke_' + $(this).attr('data-day')).val(cloneCount);
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
                removeCount = dayHoursCount[$(this).attr('data-day')];
                dayHoursCount[$(this).attr('data-day')]--;

                $('#' + $(this).attr('data-day') + '-container-' + removeCount).remove();

                if(removeCount < 2) {
                    $('#remove-in-' + $(this).attr('data-day')).attr('style', 'display:none');
                } else {
                    $('#remove-in-' + $(this).attr('data-day')).attr('style', 'display:flex;gap:8px');
                };
            });

        $('#add-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            let schedule_data = [];
            let error = '';
            let success = 0;
            const days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];

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
                        success += 1;
                        for(let i = 0; i < maxData; i++) {
                            let schedule = [];
                            schedule.push($('select[name="mapel_' + day + '[]"]').eq(i).val());
                            schedule.push($('select[name="guru_' + day + '[]"]').eq(i).val());
                            schedule.push(day);
                            schedule.push($('input[name="jam_ke_' + day + '[]"]').eq(i).val());
                            schedule.push($('input[name="start_' + day + '[]"]').eq(i).val());
                            schedule.push($('input[name="end_' + day + '[]"]').eq(i).val());
                            schedule_data.push(schedule);
                        };
                    } else {
                        error += '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Data KBM hari ' + day + ' tidak boleh kosong</div></div>';
                    };
                } else {
                    error += '<div class="toast toast-warning" aria-live="assertive"><div class="toast-message">Data KBM hari ' + day + ' tidak lengkap</div></div>';
                };
            });
            $('#toast-container').html(error);

            if(success === 5) {
                mainData = {
                    kelas_id: $('[name="kelas_id"]').val(),
                    nama_jadwal: $('[name="nama_jadwal"]').val(),
                    deskripsi_jadwal: $('[name="deskripsi_jadwal"]').val(),
                    status: $('[name="status"]').val()
                };

                parseSchedule = {schedule_group: schedule_data, nama_jadwal: $('[name="nama_jadwal"]').val()};
                function nextSend() {
                    $.ajax({
                        url: 'store/schedule',
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
                            $('#toast-container').html(dumpErr);

                            if(schedule_result.success === true) {
                                $('input').val('');
                                $('select').val('');
                                $('textarea').val('');

                                $('select').select2({ theme: 'bootstrap4' });
                            };
                            
                            function removeEl() {
                                $('.toast').remove();
                            }
                            setTimeout(removeEl, 4000);

                            removeLoading();
                        }
                    });
                };

                $.ajax({
                    url: 'store/main',
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
                        $('#toast-container').html(dumpErr);

                        if(result.success === true) {
                            nextSend();
                        };
                        
                        function removeEl() {
                            $('.toast').remove();
                        }
                        setTimeout(removeEl, 4000);
                    }
                });
            } else {
                removeLoading();

                $('#toast-container').html(error);
                function removeEl() {
                    $('.toast').remove();
                }
                setTimeout(removeEl, 4000);
            }
        });
    });
</script>