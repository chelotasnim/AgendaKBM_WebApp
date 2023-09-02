<script>
    function check_r_c() {
        const th = document.querySelectorAll('thead tr th');
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const columns = row.querySelectorAll('td');
            if(columns[0].classList.contains('merge-border') && columns[0].parentElement.previousElementSibling == undefined) {
                const new_td = '<td class="placeholder"></td>';
                row.insertAdjacentHTML('afterbegin', new_td);
                row.insertAdjacentHTML('afterbegin', new_td);
            } else if(columns[0].classList.contains('placeholder') && columns[0].parentElement.previousElementSibling != undefined) {
                columns[0].remove();
                columns[1].remove();
            };
        });
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

        var table = null;
        function do_dataTable() {
            table = $('#guru-mapel-table').DataTable({
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
                            let status = 'teal';
                            if(data.status === 0) {
                                status = 'danger';
                            };

                            if(data.guru_mapel != null) {
                                return `<span class="badge bg-${status}">${data.guru.kode},${data.guru_mapel}</span>`;
                            } else {
                                return `<span class="badge bg-${status}">${data.guru.kode}</span>`;
                            };
                        },
                        orderable: false,
                        searchable: false
                    },
                    { 
                        data: null,
                        render: function(data) {
                            return data.mapel.nama_mapel;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `<span class="action-group"><button type="button" data-toggle="modal" data-target="#modal-edit" onclick="modalEdit('` + data.id + `','` + data.mapel_id + `','` + data.mapel.nama_mapel + `','` + data.status + `')" class="modal-edit-btn btn btn-sm btn-warning"><i class="fas fa-edit"></i></button></span>` ;
                        }
                    }
                ],
                order: [[0, 'asc']],
                createdRow: function (row) {
                    $('td', row).eq(0).addClass('text-center');
                    $('td', row).eq(2).addClass('text-center');
                },
                initComplete: function(settings, json) {
                    var cellsToMerge = [];
                    var currentCellValue = null;

                    table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                        var cellValue = this.data().guru.kode;

                        if (cellValue === currentCellValue) {
                            cellsToMerge.push(rowIdx);
                        } else {
                            if (cellsToMerge.length > 1) {
                                mergeCells(cellsToMerge, 0);
                                mergeCells(cellsToMerge, 1);
                            }

                            currentCellValue = cellValue;
                            cellsToMerge = [rowIdx];
                        }
                    });

                    if (cellsToMerge.length > 1) {
                        mergeCells(cellsToMerge, 0);
                        mergeCells(cellsToMerge, 1);
                    };
                },
                language: {
                    loadingRecords: 'Sedang Mengolah Data...',
                    emptyTable: 'Belum Ada Data Guru Mapel'
                }
            });
        };
        do_dataTable();
        setInterval(check_r_c, 1000);

        function mergeCells(rowIndexes, columnIndex) {
            var firstRow = rowIndexes[0];
            var rowspan = rowIndexes.length;

            table.cell({ row: firstRow, column: columnIndex }).nodes().to$().attr('rowspan', rowspan);

            for (var i = 1; i < rowIndexes.length; i++) {
                table.cell({ row: rowIndexes[i], column: columnIndex }).nodes().to$().remove();
                table.cell({ row: rowIndexes[i], column: columnIndex + 1 }).nodes().to$().addClass('merge-border');
            }
        };

        let rows = 1;
        $('#clone-btn').click(function() {
            if(rows > 0) {
                $('#remove-btn').attr('style', 'display: block');
            } else {
                $('#remove-btn').attr('style', 'display: none');
            };

            const new_row = $('#guru-mapel-' + rows).clone();
            new_row.attr('id', 'guru-mapel-' + (rows + 1));
            new_row.attr('data-temporary', 'true');
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
                            rows = 1;
                            $('#remove-btn').attr('style', 'display: none');
                            $('div[data-temporary="true"]').remove();
                            $('select').val('');
                            $('select').select2({ theme: 'bootstrap4' });
                            table.destroy();
                            do_dataTable();
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

        $('#edit-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            $.ajax({
                url: `{{ url('dashboard/edit_guru_mapel') }}`,
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
                            table.destroy();
                            do_dataTable();
                        };
                            
                        function removeEl() {
                            $('.toast').remove();
                        }
                        setTimeout(removeEl, 4000);

                        removeLoading();
                }
            });
        });
        
        $('#import-guru-mapel-form').on('submit', function(event) {
            event.preventDefault();

            setLoading();

            var fileInput = document.getElementById('gurumapelExcel');
                var file = fileInput.files[0];
                var formData = new FormData();
                formData.append('guru_mapel_excel', file);

                $.ajax({
                    url: `{{ url('dashboard/import_guru_mapel') }}`,
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
                            $('#import-guru-mapel-form')[0].reset();
                            $('#modal-default').modal('toggle');
                            table.destroy();
                            do_dataTable();
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
    });

    function modalEdit(id, mapel_id, mapel, status) {
        $('#main-edit-param', $('#modal-edit')).val(id);
        $('#must-be-param-1', $('#modal-edit')).val(mapel_id);
        $('#select2-must-be-param-1-container', $('#modal-edit')).text(mapel);
        $('#must-be-param-2', $('#modal-edit')).val(status);
        if(status == 1) {
            $('#select2-must-be-param-2-container', $('#modal-edit')).text('Aktif');
        } else {
            $('#select2-must-be-param-2-container', $('#modal-edit')).text('Tidak Aktif');
        };
    };
</script>