<script>
    $(document).ready(function() {
        $('select').select2({ theme: 'bootstrap4' });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
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
    
        $('#reset-jadwal').on('submit', function(event) {
            event.preventDefault();
    
            setLoading();
    
            $.ajax({
                url: `{{ url('dashboard/reset_jadwal') }}`,
                data: $('#reset-jadwal').serialize(),
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
                        $('#reset-jadwal')[0].reset();
                        $('select').select2({ theme: 'bootstrap4' });
                    };
    
                    function removeEl() {
                        $('.toast').remove();
                    }
                    setTimeout(removeEl, 4000);
    
                    removeLoading();
                }
            });
        });
    
        $('#import-jadwal').on('submit', function(event) {
            event.preventDefault();
    
            setLoading();
    
            var fileInput = document.getElementById('jadwalExcel');
            var file = fileInput.files[0];
            var formData = new FormData(this);
            formData.append('jadwal_excel', file);
            $.ajax({
                url: `{{ url('dashboard/import_jadwal') }}`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
    
                success: function(result) {
                    let dumpErr = '';
                    for (let key in result.notification) {
                        if (result.notification.hasOwnProperty(key)) {
                            dumpErr += result.notification[key];
                        };
                    };
                    $('#toast-container').html(dumpErr);
    
                    if(result.success === true) {
                        $('#import-jadwal')[0].reset();
                        $('select').select2({ theme: 'bootstrap4' });
                    };
    
                    function removeEl() {
                        $('.toast').remove();
                    }
                    setTimeout(removeEl, 4000);
    
                    removeLoading();
                    },
                error: function(xhr, status, error) {
                    removeLoading();
                    console.error(xhr.responseText);
                }
            });
        });

        $('#search-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            $.ajax({
                url: `{{ url('dashboard/get_jadwal') }}`,
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

                        $.each(result.main_data, function(index, jadwal) {
                            if(jadwal.previous_null == true) {
                                table_data += `
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <small class="text-secondary">Tidak Ada Jam Ke 0</small>
                                        </td>
                                    </tr>
                                `;
                            };

                            if(jadwal.rest == true) {
                                table_data += `
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <small class="text-secondary">Istirahat</small>
                                        </td>
                                    </tr>
                                `;
                            };

                            table_data += `
                                <tr>
                                    <td class="text-center">${count++}</td>
                                    <td class="text-center">
                                        <span class="badge bg-teal">${jadwal.jam_ke}</span>
                                    </td>
                                    <td>${jadwal.guru_mapel.guru.name}</td>
                                    <td>${jadwal.guru_mapel.mapel.nama_mapel}</td>
                                    <td class="text-center">${jadwal.mulai} WIB</td>
                                    <td class="text-center">${jadwal.selesai} WIB</td>
                                    <td class="text-center">
                                        <span class="action-group"><button type="button" data-toggle="modal" data-target="#modal-edit" onclick="modalEdit('${jadwal.id}')" class="modal-edit-btn btn btn-sm btn-warning"><i class="fas fa-edit"></i></button></span>
                                    </td>
                                </tr>
                            `;
                        });

                        if(result.found == false) {
                            table_data += `
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <small class="text-secondary">Tidak Ada Jadwal Kelas</small>
                                    </td>
                                </tr>
                            `;
                        };

                        $('#data-col').html(`
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title mt-2">Jadwal Kelas</h3>
                                </div>
                                <div class="card-body">
                                <table id="jadwal-table" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Jam Ke</th>
                                        <th>Guru</th>
                                        <th>Mata Pelajaran</th>
                                        <th class="text-center">Jam Mulai</th>
                                        <th class="text-center">Jam Selesai</th>
                                        <th class="text-center">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="jadwal-tbody">
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