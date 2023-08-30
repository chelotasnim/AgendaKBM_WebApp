<script>
    $(document).ready(function() {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
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
    });
</script>