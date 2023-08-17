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
                        $('#select2-inputMapel-container', $('#add-form')).text('Pilih Mata Pelajaran');
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
                                    <td>${jurnal.mapel.nama_mapel}</td>
                                    <td>${jurnal.guru.name}</td>
                                    <td class="text-center">${jurnal.jam_mulai}</td>
                                    <td class="text-center">${jurnal.jam_selesai}</td>
                                    <td class="text-center">${jurnal.total_siswa} Orang</td>
                                    <td class="text-center">${jurnal.tidak_hadir} Orang</td>
                                    <td>${jurnal.materi}</td>
                                </tr>
                            `;
                        });

                        if(table_data == '') {
                            table_data += `
                                <tr>
                                    <td colspan="9" class="text-center">
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

                    removeLoading();
                }
            });
        });
    });
</script>