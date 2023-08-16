<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('/plugins/fontawesome-5-pro-master/css/all.css') }}">
    <link rel="shortcut icon" href="{{ asset('/assets/app-images/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('mobile/css/style.css') }}">
    <script src="{{ asset('/plugins/jquery/jquery.min.js') }}"></script>
    <title>Aplikasi Agenda KBM | Masuk</title>
</head>
<body>
    <div class="welcome-cover">
        <img class="left-wave" src="{{ asset('auth/images/wave.png') }}" draggable="false"/>
        <div class="logo">
            <img src="{{ asset('/assets/app-images/agenda_logo_white.png') }}" draggable="false"/>
        </div>
    </div>
    <div class="page">
        <div id="alert-container">
        </div>
        <header>
            <div class="main-header">
                <img src="{{ asset('mobile/assets/wave.png') }}" draggable="false"/>
                <div class="app-label">
                    <p id="user-name-here"></p>
                    <h1>
                        <span>Agenda</span>
                        <br>
                        KBM
                    </h1>
                </div>
                <div class="timer">
                    <span class="hours"></span>
                    <br>
                    <span class="minutes"></span>
                </div>
            </div>
            <div class="wave-illusion">
                <div class="cover"></div>
            </div>
        </header>
        <div id="section-wrapper">
            <div id="home-wrapper" class="section">
                <div class="floating-header">
                    <div class="icon" style="padding-left: 25px">
                        <i class="fal fa-clipboard-list-check"></i>
                    </div>
                    <div class="title">Isi Jurnal Saat Ini</div>
                </div>
                <div class="schedule-list">
                    <div class="list-heading">
                        <p>07:00</p>
                        <p>XII RPL 1</p>
                        <p>10:00</p>
                    </div>
                    <div class="jurnal-detail">
                        <h1>PAI dan Budi Karakter</h1>
                        <p>Ahmad Salehudin</p>
                        <form id="jurnal-form" method="post" class="jurnal-form">
                            <input type="text" name="jadwal_id" style="display: none" readonly>
                            <div class="row">
                                <div class="input-group">
                                    <label>Total Siswa</label>
                                    <input type="number" name="total_siswa" placeholder="--;--">
                                </div>
                                <div class="input-group">
                                    <label>Siswa Tidak Hadir</label>
                                    <input type="number" name="tidak_hadir" placeholder="--;--">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group wide">
                                    <label>Materi Pembelajaran</label>
                                    <textarea name="materi" placeholder="Isi sedikit tentang materi disini"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn on">Isi Jurnal</button>
                            <a href="{{ url('teacher') }}" class="btn badge on grey">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('mobile/js/script.js') }}"></script>
    <script>
        $(document).ready(function() {
            const user_id = "{{ Auth::guard('teacher')->user()->id }}";
            let this_schedule = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);

            $('[name="jadwal_id"]').val(this_schedule);
            $('#jurnal-form').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: `{{ url('api/send_jurnal') }}`,
                    type: 'post',
                    data: $('#jurnal-form').serialize(),
                    success: function(result) {
                        if(result.success == true) {
                            window.location.href = `{{ url('teacher') }}`;
                        };

                        let dumpErr = '';
                        for (let key in result.notification) {
                            if (result.notification.hasOwnProperty(key)) {
                                dumpErr += `
                                    <div class="alert-box">
                                        <div class="alert-icon">
                                            <i class="fal fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="alert-content">${result.notification[key]}</div>
                                    </div>
                                `;
                            };
                        };
                        $('#alert-container').html(dumpErr);
                    }
                });
            });
        });
    </script>
</body>
</html>