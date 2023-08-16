<script>
    $(document).ready(function() {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        flatpickr('.as-time', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
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
                url: `{{ url('dashboard/add_jurnal') }}`,
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
                        $('#select2-inputGuru-container', $('#add-form')).text('Pilih Guru Pengajar');
                        $('#select2-inputMapel-container', $('#add-form')).text('Pilih Mata Pelajaran');
                        $('#select2-inputKelas-container', $('#add-form')).text('Pilih Kelas');
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
</script>