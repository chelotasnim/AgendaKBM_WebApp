<script>
    function refreshTable() {
        $('#jenjang-kelas-table').DataTable().ajax.reload();
    };

    $(document).ready(function () {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        $('#add-form')[0].reset();
        function removeEl() {
            $('.toast').remove();
        }
        setTimeout(removeEl, 4000);

        $('#jenjang-kelas-table').DataTable({
            language: {
                errorAlert: null
            },
            ajax: {
                url: 'get_jenjang_kelas',
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
                { data: 'jenjang' },
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
                        return `<span class="action-group"><button type="button" data-toggle="modal" data-target="#modal-edit" onclick="modalEdit('` + data.jenjang + `', '` + data.status + `')" class="modal-edit-btn btn btn-sm btn-warning"><i class="fas fa-edit"></i></button><button type="button" data-toggle="modal" data-target="#modal-delete" onclick="modalDelete('` + data.jenjang + `')" class="modal-delete-btn btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button></span>` ;
                    }
                }
            ],
            createdRow: function (row) {
                $('td', row).eq(0).addClass('text-center');
                $('td', row).eq(3).addClass('text-center');
            },
            language: {
                loadingRecords: 'Sedang Mengolah Data...',
                emptyTable: 'Belum Ada Data Jenjang'
            }
        });

        setInterval(refreshTable, 1000);

        $('#add-form').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                url: 'jenjang_kelas',
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
                url: 'delete_jenjang_kelas',
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
                url: 'edit_jenjang_kelas',
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
    });

    function modalDelete(jenjang_kelas) {
        $('#must-be-param', $('#modal-delete')).val(jenjang_kelas);
        $('#param-delete', $('#modal-delete')).text(jenjang_kelas);
    };

    function modalEdit(jenjang_kelas, status) {
        $('#main-edit-param', $('#modal-edit')).val(jenjang_kelas);
        $('#must-be-param-1', $('#modal-edit')).val(jenjang_kelas);
        $('#must-be-param-2', $('#modal-edit')).val(status);
        if(status == 1) {
            $('#select2-must-be-param-2-container', $('#modal-edit')).text('Aktif');
        } else {
            $('#select2-must-be-param-2-container', $('#modal-edit')).text('Tidak Aktif');
        };
    };
</script>
