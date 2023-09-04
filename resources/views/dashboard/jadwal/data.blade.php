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

</script>