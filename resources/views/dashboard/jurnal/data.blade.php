<script>
    $(document).ready(function() {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        flatpickr('.as-time', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });

        $('#reservation').daterangepicker()

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        if($('#add-form').length > 0) {
            $('#add-form')[0].reset();
            function removeEl() {
                $('.toast').remove();
            }
            setTimeout(removeEl, 4000);
        };

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

        $('#add-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            $.ajax({
                url: `{{ url('dashboard/add_jurnal') }}`,
                data: $('#add-form').serialize(),
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
                        $('#add-form')[0].reset();
                        $('#select2-inputGuru-container', $('#add-form')).text('Pilih Guru Pengajar');
                        $('#select2-inputKelas-container', $('#add-form')).text('Pilih Kelas');
                    };
                    
                    function removeEl() {
                        $('.toast').remove();
                    }
                    setTimeout(removeEl, 4000);

                    removeLoading();
                }
            });
        });

        $('#search-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            $.ajax({
                url: `{{ url('dashboard/get_jurnal') }}`,
                data: $('#search-form').serialize(),
                type: 'post',
                success: function(result) {
                    if(result.notification != null) {
                        let dumpErr = '';
                        for (let key in result.notification) {
                            if (result.notification.hasOwnProperty(key)) {
                                dumpErr += result.notification[key];
                            };
                        };
                        $('#toast-container').html(dumpErr);
                    };

                    if(result.success == true) {
                        let table_data = '';
                        let count = 1;
                        $.each(result.main_data, function(index, jurnal) {
                            table_data += `
                                <tr>
                                    <td class="text-center">${count++}</td>
                                    <td class="text-center">${jurnal.tanggal}</td>
                                    <td>${jurnal.guru_mapel.mapel.nama_mapel}</td>
                                    <td>${jurnal.guru_mapel.guru.name}</td>
                                    <td class="text-center">${jurnal.jam_mulai}</td>
                                    <td class="text-center">${jurnal.jam_selesai}</td>
                                    <td class="text-center">${jurnal.total_siswa} Orang</td>
                                    <td class="text-center">${jurnal.tidak_hadir} Orang</td>
                                    <td>${jurnal.materi}</td>
                                    <td class="text-center">
                                        <span class="action-group"><button type="button" data-toggle="modal" data-target="#modal-edit" onclick="modalEdit('${jurnal.id}','${jurnal.guru_mapel_id}','${jurnal.guru_mapel.mapel.nama_mapel} | ${jurnal.guru_mapel.guru.name}','${jurnal.kelas}','${jurnal.tanggal}','${jurnal.jam_mulai}','${jurnal.jam_selesai}','${jurnal.total_siswa}','${jurnal.tidak_hadir}','${jurnal.materi}')" class="modal-edit-btn btn btn-sm btn-warning"><i class="fas fa-edit"></i></button></span>
                                    </td>
                                </tr>
                            `;
                        });

                        if(table_data == '') {
                            table_data += `
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <small class="text-secondary">Tidak Ada Jurnal Mengajar Kelas ${$('#inputKelas').val()} per tanggal ${$('#reservation').val()}</small>
                                    </td>
                                </tr>
                            `;
                        };

                        $('#data-col').html(`
                            <div class="card">
                                <div class="card-header">
                                <h3 class="card-title mt-2">Jurnal Mengajar</h3>
                                <div class="card-tools">
                                    <button id="export-btn" class="btn bg-teal">Ekspor Data</button>
                                </div>
                                </div>
                                <div class="card-body">
                                <table id="siswa-table" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Tanggal</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Nama Guru</th>
                                        <th class="text-center">Mulai</th>
                                        <th class="text-center">Selesai</th>
                                        <th class="text-center">Total Siswa</th>
                                        <th class="text-center">Tidak Hadir</th>
                                        <th>Materi Pembelajaran</th>
                                        <th class="text-center">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="siswa-tbody">
                                        ${table_data}
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        `);
                    };

                    function removeEl() {
                        $('.toast').remove();
                    }
                    setTimeout(removeEl, 4000);

                    removeLoading();
                }
            });
        });

        $('#edit-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            $.ajax({
                url: `{{ url('dashboard/edit_jurnal') }}`,
                data: $('#edit-form').serialize(),
                type: 'post',

                success: function(result) {
                    if(result.success === true) {
                        window.location = `{{ url('dashboard/jurnal') }}`;
                    };
    
                    let dumpErr = '';
                        for (let key in result.notification) {
                            if (result.notification.hasOwnProperty(key)) {
                                dumpErr += result.notification[key];
                            };
                        };
                        $('#toast-container').html(dumpErr);
    
                        function removeEl() {
                            $('.toast').remove();
                        }
                        setTimeout(removeEl, 4000);
    
                        removeLoading();
                }
            });
        });
    });

    function modalEdit(id, guru_mapel_id, guru, kelas, tanggal, jam_mulai, jam_selesai, total_siswa, tidak_hadir, materi) {
        $('#main-edit-param', $('#modal-edit')).val(id);
        $('#must-be-param-1', $('#modal-edit')).val(guru_mapel_id);
        $('#select2-must-be-param-1-container', $('#modal-edit')).text(guru);
        $('#must-be-param-2', $('#modal-edit')).val(kelas);
        $('#select2-must-be-param-2-container', $('#modal-edit')).text(kelas);
        $('#must-be-param-3', $('#modal-edit')).val(tanggal);
        $('#must-be-param-4', $('#modal-edit')).val(jam_mulai);
        $('#must-be-param-5', $('#modal-edit')).val(jam_selesai);
        $('#must-be-param-6', $('#modal-edit')).val(total_siswa);
        $('#must-be-param-7', $('#modal-edit')).val(tidak_hadir);
        $('#must-be-param-8', $('#modal-edit')).text(materi);
    };
</script>