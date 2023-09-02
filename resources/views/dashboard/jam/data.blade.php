<script>
    function refreshTable() {
        $('#jam-table').DataTable().ajax.reload(null, false);
    };

    $(document).ready(function() {
        $('select').select2({ theme: 'bootstrap4' });

        flatpickr('.as-time', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });

        var table = $('#jam-table').DataTable({
            language: {
                errorAlert: null
            },
            ajax: {
                url: `{{ url('dashboard/get_jam') }}`,
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
                    data: 'hari',
                    render: function(data) {
                        if(data > 0) {
                            return 'Senin';
                        } else {
                            return 'Selasa - Jumat';
                        };
                    }
                },
                { data: 'mulai' },
                { 
                    data: 'jumlah',
                    render: function(data) {
                        return data + ' Jam KBM / Hari';
                    }
                },
                { 
                    data: 'durasi',
                    render: function(data) {
                        return data + ' Menit / Jam';
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        return `<span class="action-group"><button type="button" data-toggle="modal" data-target="#modal-edit" onclick="modalEdit('` + data.id + `','` + data.hari + `','` + data.mulai + `','` + data.jumlah + `','` + data.durasi + `')" class="modal-edit-btn btn btn-sm btn-warning"><i class="fas fa-edit"></i></button></span>` ;
                    }
                }
            ],
            createdRow: function (row) {
                $('td', row).eq(0).addClass('text-center');
                $('td', row).eq(2).addClass('text-center');
                $('td', row).eq(3).addClass('text-center');
                $('td', row).eq(4).addClass('text-center');
                $('td', row).eq(5).addClass('text-center');
            },
            language: {
                loadingRecords: 'Sedang Mengolah Data...',
                emptyTable: 'Belum Ada Pengaturan Jam Pelajaran'
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
                url: `{{ url('dashboard/jam') }}`,
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
                url: `{{ url('dashboard/edit_jam') }}`,
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

    function modalEdit(id, hari, mulai, jumlah, durasi) {
        $('#main-edit-param', $('#modal-edit')).val(id);
        $('#must-be-param-2', $('#modal-edit')).val(mulai);
        $('#must-be-param-3', $('#modal-edit')).val(jumlah);
        $('#must-be-param-4', $('#modal-edit')).val(durasi);
        if(status > 0) {
            $('#must-be-param-1', $('#modal-edit')).val('Senin');
        } else {
            $('#must-be-param-1', $('#modal-edit')).val('Selasa - Jumat');
        };
    };
</script>