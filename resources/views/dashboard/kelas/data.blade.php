<script>
    function refreshTable() {
        $('#kelas-table').DataTable().ajax.reload(null, false);
    };

    $(document).ready(function () {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $('#add-form')[0].reset();
        function removeEl() {
            $('.toast').remove();
        }
        setTimeout(removeEl, 4000);

        var table = $('#kelas-table').DataTable({
            language: {
                errorAlert: null
            },
            ajax: {
                url: 'get_kelas',
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
                    data: null, 
                    render: function(data) {
                        return data.jenjang.jenjang + ' ' + data.name;
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        return data.jenjang.jenjang;
                    }
                },
                {
                    data: 'name'
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
                        return `<span class="action-group"><a href="{{ url('dashboard/jadwal/` + data.id + `') }}" class="btn btn-sm btn-info"><i class="fal fa-calendar-alt"></i></a><button type="button" data-toggle="modal" data-target="#modal-edit" onclick="modalEdit('` + data.jenjang.jenjang + `', '` + data.jenjang_kelas_id + `', '` + data.name + `', '` + data.status + `')" class="modal-edit-btn btn btn-sm btn-warning"><i class="fas fa-edit"></i></button><button type="button" data-toggle="modal" data-target="#modal-delete" onclick="modalDelete('` + data.id + `')" class="modal-delete-btn btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button></span>` ;
                    }
                }
            ],
            createdRow: function (row) {
                $('td', row).eq(0).addClass('text-center');
                $('td', row).eq(2).addClass('d-none');
                $('td', row).eq(3).addClass('d-none');
                $('td', row).eq(4).addClass('text-center');
            },
            language: {
                loadingRecords: 'Sedang Mengolah Data...',
                emptyTable: 'Belum Ada Data Kelas'
            }
        });

        $('#export-btn').on('click', function() {
            var documentTitle = [document.title];

            var customHeader = table.columns().header().toArray().map(function(header) {
                return $(header).text();
            });

            var exportData = table.buttons.exportData({
                columns: [3, 4, 5],
                header: false,
                format: {
                    header: function (data, columnIdx) {
                        return customHeader[columnIdx];
                    }
                }
            });

            var modifiedData = [];
            for (var i = 0; i < exportData.body.length; i++) {
                if (exportData.body[i][2] === 'Aktif') {
                    exportData.body[i][2] = '1';
                } else if (exportData.body[i][2] === 'Tidak Aktif') {
                    exportData.body[i][2] = '0';
                }
                modifiedData.push([exportData.body[i][0], exportData.body[i][1], exportData.body[i][2]]);
            }

            var workbook = XLSX.utils.book_new();
            var worksheet = XLSX.utils.aoa_to_sheet([documentTitle, ["Jenjang", "Nama Kelas", "Status"], ...modifiedData]);
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

            var xlsxData = XLSX.write(workbook, { bookType: "xlsx", type: "array" });

            var blob = new Blob([xlsxData], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });

            var link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "EksporDataKelas.xlsx";
            document.body.appendChild(link);
            link.click();
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

        $('#add-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            $.ajax({
                url: 'kelas',
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
                        $('#select2-inputJenjang-container', $('#add-form')).text('Pilih Jenjang Kelas');
                    };
                    
                    function removeEl() {
                        $('.toast').remove();
                    }
                    setTimeout(removeEl, 4000);

                    removeLoading();
                }
            });
        });

        $('#delete-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            $.ajax({
                url: 'delete_kelas',
                data: $('#delete-form').serialize(),
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
                        $('#modal-delete').modal('toggle');
                    };

                    $('#delete-form')[0].reset();

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
                url: 'edit_kelas',
                data: $('#edit-form').serialize(),
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
                        $('#edit-form')[0].reset();
                        $('#modal-edit').modal('toggle');
                    };

                    function removeEl() {
                        $('.toast').remove();
                    }
                    setTimeout(removeEl, 4000);

                    removeLoading();
                }
            });
        });

        $('#import-kelas-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            var fileInput = document.getElementById('kelasExcel');
                var file = fileInput.files[0];
                var formData = new FormData();
                formData.append('kelas_excel', file);

                $.ajax({
                    url: 'import_kelas', // Replace with your controller route
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
                            $('#import-kelas-form')[0].reset();
                            $('#modal-default').modal('toggle');
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

        $('#naik-kelas-form').on('submit', function() {
            event.preventDefault();

            setLoading();

            $.ajax({
                url: 'naik_kelas',
                data: $('#naik-kelas-form').serialize(),
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
                        $('#modal-naik-kelas').modal('toggle');
                    };

                    function removeEl() {
                        $('.toast').remove();
                    }
                    setTimeout(removeEl, 4000);

                    removeLoading();
                }
            });
        });
    });

    function modalDelete(id) {
        $('#must-be-param', $('#modal-delete')).val(id);
        $('#param-delete', $('#modal-delete')).text(id);
    };

    function modalEdit(jenjang, jenjang_kelas_id, kelas, status) {
        $('#main-edit-param', $('#modal-edit')).val(kelas);
        $('#second-edit-param', $('#modal-edit')).val(jenjang_kelas_id);
        $('#must-be-param-1', $('#modal-edit')).val(jenjang_kelas_id);
        $('#must-be-param-2', $('#modal-edit')).val(kelas);
        $('#must-be-param-3', $('#modal-edit')).val(status);
        $('#select2-must-be-param-1-container', $('#modal-edit')).text(jenjang);
        if(status == 1) {
            $('#select2-must-be-param-3-container', $('#modal-edit')).text('Aktif');
        } else {
            $('#select2-must-be-param-3-container', $('#modal-edit')).text('Tidak Aktif');
        };
    };
</script>
