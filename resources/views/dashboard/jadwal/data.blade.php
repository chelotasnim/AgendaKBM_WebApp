<script>
    $(document).ready(function() {
        $('select').select2({ theme: 'bootstrap4' });
    });

    $.ajax({
        url: `{{ url('dashboard/import_jadwal') }}`, 
    });
</script>