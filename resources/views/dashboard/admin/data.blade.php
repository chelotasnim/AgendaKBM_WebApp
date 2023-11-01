<script>
    function refreshTable() {
        $('#admin-table').DataTable().ajax.reload(null, false);
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

        var table = $('#admin-table').DataTable({
            language: {
                errorAlert: null
            },
            ajax: {
                url: 'get_admin',
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
                        if(data.id === 1) {
                            return `<span class="action-group"><button type="button" data-toggle="modal" data-target="#modal-edit" onclick="modalEdit('` + data.name + `', '` + data.email + `','` + data.status + `')" class="modal-edit-btn btn btn-sm btn-warning"><i class="fas fa-edit"></i></button></span>`;
                        } else {
                            return `<span class="action-group"><button type="button" data-toggle="modal" data-target="#modal-edit" onclick="modalEdit('` + data.name + `', '` + data.email + `','` + data.status + `')" class="modal-edit-btn btn btn-sm btn-warning"><i class="fas fa-edit"></i></button><button type="button" data-toggle="modal" data-target="#modal-delete" onclick="modalDelete('` + data.email + `')" class="modal-delete-btn btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button></span>` ;
                        };
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
                emptyTable: 'Belum Ada Data Admin'
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
                url: 'admin',
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
                url: 'delete_admin',
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
                url: 'edit_admin',
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
        });

    function modalDelete(email) {
        $('#must-be-param', $('#modal-delete')).val(email);
        $('#param-delete', $('#modal-delete')).text(email);
    };

    function modalEdit(name, email, status) {
        $('#main-edit-param', $('#modal-edit')).val(email);
        $('#must-be-param-1', $('#modal-edit')).val(name);
        $('#must-be-param-2', $('#modal-edit')).val(email);
        $('#must-be-param-3', $('#modal-edit')).val(status);
        if(status == 1) {
            $('#select2-must-be-param-3-container', $('#modal-edit')).text('Aktif');
        } else {
            $('#select2-must-be-param-3-container', $('#modal-edit')).text('Tidak Aktif');
        };
    };
</script>
