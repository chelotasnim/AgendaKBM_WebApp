<script>
    function refreshTable() {
        $('#guru-table').DataTable().ajax.reload(null, false);
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

        var table = $('#guru-table').DataTable({
            ajax: {
                url: 'get_guru',
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
                { data: 'name' },
                { data: 'username' },
                { data: 'email' },
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
                        return `<span class="action-group"><button type="button" data-toggle="modal" data-target="#modal-edit" onclick="modalEdit('` + data.name + `', '` + data.username + `', '` + data.email + `', '` + data.status + `')" class="modal-edit-btn btn btn-sm btn-warning"><i class="fas fa-edit"></i></button><button type="button" data-toggle="modal" data-target="#modal-delete" onclick="modalDelete('` + data.username + `')" class="modal-delete-btn btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button></span>` ;
                    }
                }
            ],
            createdRow: function (row) {
                $('td', row).eq(0).addClass('text-center');
                $('td', row).eq(4).addClass('text-center');
            },
            language: {
                loadingRecords: 'Sedang Mengolah Data...',
                emptyTable: 'Belum Ada Data Guru'
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

        $('#add-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            $.ajax({
                url: 'guru',
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

                    removeLoading();
                }
            });
        });

        $('#delete-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            $.ajax({
                url: 'delete_guru',
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
                        $('#delete-form')[0].reset();
                        $('#modal-delete').modal('toggle');
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
                url: 'edit_guru',
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

        $('#export-btn').on('click', function() {
            var documentTitle = [document.title];

            var customHeader = table.columns().header().toArray().map(function(header) {
                return $(header).text();
            });

            var exportData = table.buttons.exportData({
                columns: [2, 3, 4, 5],
                header: false,
                format: {
                    header: function (data, columnIdx) {
                        return customHeader[columnIdx];
                    }
                }
            });

            var modifiedData = [];
            for (var i = 0; i < exportData.body.length; i++) {
                if (exportData.body[i][3] === 'Aktif') {
                    exportData.body[i][3] = '1';
                } else if (exportData.body[i][3] === 'Tidak Aktif') {
                    exportData.body[i][3] = '0';
                }

                exportData.body[i][4] = 'Demi Keamanan Password Tidak Diimpor';
                modifiedData.push([exportData.body[i][0], exportData.body[i][1], exportData.body[i][2], exportData.body[i][4], exportData.body[i][3]]);
            }

            var workbook = XLSX.utils.book_new();
            var worksheet = XLSX.utils.aoa_to_sheet([documentTitle, ["Nama Guru", "Username Akun", "Email", "Password", "Status"], ...modifiedData]);

            XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");

            var xlsxData = XLSX.write(workbook, { bookType: "xlsx", type: "array" });

            var blob = new Blob([xlsxData], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });

            var link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "EksporDataAkunGuru.xlsx";
            document.body.appendChild(link);
            link.click();
        });

        $('#import-guru-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            var fileInput = document.getElementById('guruExcel');
                var file = fileInput.files[0];
                var formData = new FormData();
                formData.append('guru_excel', file);

                $.ajax({
                    url: 'import_guru',
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
                            $('#modal-default').modal('toggle');
                        };

                        $('#import-guru-form')[0].reset();

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
    });

    function modalDelete(username) {
        $('#must-be-param', $('#modal-delete')).val(username);
        $('#param-delete', $('#modal-delete')).text(username);
    };

    function modalEdit(name, username, email, status) {
        $('#main-edit-param', $('#modal-edit')).val(username);
        $('#second-edit-param', $('#modal-edit')).val(email);
        $('#must-be-param-1', $('#modal-edit')).val(name);
        $('#must-be-param-2', $('#modal-edit')).val(username);
        $('#must-be-param-3', $('#modal-edit')).val(email);
        $('#must-be-param-4', $('#modal-edit')).val(status);
        if(status == 1) {
            $('#select2-must-be-param-4-container', $('#modal-edit')).text('Aktif');
        } else {
            $('#select2-must-be-param-4-container', $('#modal-edit')).text('Tidak Aktif');
        };
    };
</script>
