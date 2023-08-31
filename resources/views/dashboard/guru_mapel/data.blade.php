<script>
    function refreshTable() {
        $('#guru-mapel-table').DataTable().ajax.reload(null, false);
    };

    $(document).ready(function() {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#guru-mapel-table').DataTable({
            language: {
                errorAlert: null
            },
            ajax: {
                url: `{{ url('dashboard/get_guru_mapel') }}`,
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
                        return `<span class="badge bg-teal">${data.guru.kode}</span>`;
                    }
                },
                { 
                    data: null,
                    render: function(data) {
                        return data.guru.name;
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        if(data.guru_mapel != null) {
                            return `<span class="badge bg-teal">${data.guru.kode},${data.guru_mapel}</span>`;
                        } else {
                            return `<span class="badge bg-teal">${data.guru.kode}</span>`;
                        };
                    }
                },
                { 
                    data: null,
                    render: function(data) {
                        return data.mapel.nama_mapel;
                    }
                },
                { data: 'id' }
            ],
            order: [[1, 'asc']],
            createdRow: function (row) {
                $('td', row).eq(0).addClass('text-center');
                $('td', row).eq(1).addClass('text-center');
                $('td', row).eq(3).addClass('text-center');
            },
            language: {
                loadingRecords: 'Sedang Mengolah Data...',
                emptyTable: 'Belum Ada Data Mata Pelajaran'
            }
        });
        setInterval(refreshTable, 1000);

        let rows = 1;
        $('#clone-btn').click(function() {
            if(rows > 0) {
                $('#remove-btn').attr('style', 'display: block');
            } else {
                $('#remove-btn').attr('style', 'display: none');
            };

            const new_row = $('#guru-mapel-' + rows).clone();
            new_row.attr('id', 'guru-mapel-' + (rows + 1));
            $('#guru-mapel-' + rows).addClass('mb-3');
            new_row.find('#input-guru-' + rows).attr('id', 'input-guru-' + (rows + 1));
            new_row.find('#input-mapel-' + rows).attr('id', 'input-mapel-' + (rows + 1));
            new_row.find('.select2').remove();
            new_row.find('select').prop('selectedIndex', 0);
            $('#guru-mapel-' + rows).after(new_row);
            new_row.find('select').select2({ theme: 'bootstrap4' });
            $('select').select2({ theme: 'bootstrap4' });

            rows++;
        });

        $('#remove-btn').click(function() {
            if(rows < 3) {
                $('#remove-btn').attr('style', 'display: none');
            } else {
                $('#remove-btn').attr('style', 'display: block');
            };

            $('#guru-mapel-' + rows).remove();
            
            rows--;
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

        $('#add-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            let guru_mapels = [];
            let process = false;

            const get_guru_input = $('select[name="guru_id[]"]');
            let count_guru = 0;
            get_guru_input.each(function() {
                if($(this).val() != null) {
                    count_guru++;
                };
            });

            const get_mapel_input = $('select[name="mapel_id[]"]');
            let count_mapel = 0;
            get_mapel_input.each(function() {
                if($(this).val() != null) {
                    count_mapel++
                };
            });

            const count_total = [count_guru, count_mapel];
            const max_val = Math.max.apply(null, count_total);
            const min_val = Math.min.apply(null, count_total);

            if(max_val == min_val && max_val != 0 && min_val != 0) {
                for(let i = 0; i < max_val; i++) {
                    let guru_mapel_row = [];
                    guru_mapel_row.push($('select[name="guru_id[]"]').eq(i).val());
                    guru_mapel_row.push($('select[name="mapel_id[]"]').eq(i).val());
                    guru_mapels.push(guru_mapel_row);
                };

                process = true;
            };

            if(process == true) {
                const parse_data = { guru_mapels };
                $.ajax({
                    url: `{{ url('dashboard/guru_mapel') }}`,
                    contentType: 'application/json',
                    data: JSON.stringify(parse_data),
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
                            $('select').val('');
                            $('select').select2({ theme: 'bootstrap4' });
                        };
                            
                        function removeEl() {
                            $('.toast').remove();
                        }
                        setTimeout(removeEl, 4000);

                        removeLoading();
                    }
                });
            } else {
                removeLoading();
            };
        });
    });
</script>