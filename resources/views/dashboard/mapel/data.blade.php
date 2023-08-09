<script>
    function refreshTable() {
        $('#mapel-table').DataTable().ajax.reload();
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

        var table = $('#mapel-table').DataTable({
            language: {
                errorAlert: null
            },
            ajax: {
                url: 'get_mapel',
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
                { data: 'nama_mapel' },
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
                        return `<span class="action-group"><button type="button" data-toggle="modal" data-target="#modal-edit" onclick="modalEdit('` + data.nama_mapel + `', '` + data.status + `')" class="modal-edit-btn btn btn-sm btn-warning"><i class="fas fa-edit"></i></button><button type="button" data-toggle="modal" data-target="#modal-delete" onclick="modalDelete('` + data.nama_mapel + `')" class="modal-delete-btn btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button></span>` ;
                    }
                }
            ],
            createdRow: function (row) {
                $('td', row).eq(0).addClass('text-center');
                $('td', row).eq(2).addClass('text-center');
            },
            language: {
                loadingRecords: 'Sedang Mengolah Data...',
                emptyTable: 'Belum Ada Data Mata Pelajaran'
            }
        });

        $('#export-btn').on('click', function() {
            var documentTitle = [document.title];

            var customHeader = table.columns().header().toArray().map(function(header) {
                return $(header).text();
            });

            var exportData = table.buttons.exportData({
                columns: [2, 3],
                header: false,
                format: {
                    header: function (data, columnIdx) {
                        return customHeader[columnIdx];
                    }
                }
            });

            var modifiedData = [];
            for (var i = 0; i < exportData.body.length; i++) {
                if (exportData.body[i][1] === 'Aktif') {
                    exportData.body[i][1] = '1';
                } else if (exportData.body[i][1] === 'Tidak Aktif') {
                    exportData.body[i][1] = '0';
                }
                modifiedData.push([exportData.body[i][0], exportData.body[i][1]]);
            }

            var workbook = XLSX.utils.book_new();
            var worksheet = XLSX.utils.aoa_to_sheet([documentTitle, ["Mata Pelajaran", "Status"], ...modifiedData]);

            XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");

            var xlsxData = XLSX.write(workbook, { bookType: "xlsx", type: "array" });

            var blob = new Blob([xlsxData], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });

            var link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "EksporDataMapel.xlsx";
            document.body.appendChild(link);
            link.click();
        });

        setInterval(refreshTable, 1000);

        $('#add-form').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                url: 'mapel',
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
                        $('#select2-inputStatus-container', $('#add-form')).text('Aktif');
                    };
                    
                    function removeEl() {
                        $('.toast').remove();
                    }
                    setTimeout(removeEl, 4000);
                }
            });
        });

        $('#delete-form').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                url: 'delete_mapel',
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
                }
            });
        });

        $('#edit-form').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                url: 'edit_mapel',
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
                }
            });
        });

        $('#import-mapel-form').on('submit', function(event) {
            event.preventDefault();
            var fileInput = document.getElementById('mapelExcel');
                var file = fileInput.files[0];
                var formData = new FormData();
                formData.append('mapel_excel', file);

                $.ajax({
                    url: 'import_mapel', // Replace with your controller route
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
                            $('#import-mapel-form')[0].reset();
                            $('#modal-default').modal('toggle');
                        };

                        function removeEl() {
                            $('.toast').remove();
                        }
                        setTimeout(removeEl, 4000);
                        },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });

    function modalDelete(mapel) {
        $('#must-be-param', $('#modal-delete')).val(mapel);
        $('#param-delete', $('#modal-delete')).text(mapel);
    };

    function modalEdit(mapel, status) {
        $('#main-edit-param', $('#modal-edit')).val(mapel);
        $('#must-be-param-1', $('#modal-edit')).val(mapel);
        $('#must-be-param-2', $('#modal-edit')).val(status);
        if(status == 1) {
            $('#select2-must-be-param-2-container', $('#modal-edit')).text('Aktif');
        } else {
            $('#select2-must-be-param-2-container', $('#modal-edit')).text('Tidak Aktif');
        };
    };
</script>
