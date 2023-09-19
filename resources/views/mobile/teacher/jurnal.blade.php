<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('/plugins/fontawesome-5-pro-master/css/all.css') }}">
    <link rel="shortcut icon" href="{{ asset('/assets/app-images/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('mobile/css/style.css') }}">
    <script src="{{ asset('/plugins/jquery/jquery.min.js') }}"></script>
    <title>Aplikasi Agenda KBM | Guru</title>
</head>
<body>
    <div class="loading-animation" style="display: none">
        <i class="fas fa-spinner-third"></i>
    </div>
    <div class="entire">
        <div class="frame">
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
                                <p id="start-time"></p>
                                <p id="kelas-name-tag"></p>
                                <p id="end-time"></p>
                            </div>
                            <div class="jurnal-detail">
                                <h1 id="subject-name"></h1>
                                <p id="teacher-name"></p>
                                <form id="jurnal-form" method="post" class="jurnal-form">
                                    @csrf
                                    <input type="text" name="guru_mapel_id" style="display: none" readonly>
                                    <input type="text" name="kelas" style="display: none" readonly>
                                    <input type="text" name="jam_mulai" style="display: none" readonly>
                                    <input type="text" name="jam_selesai" style="display: none" readonly>
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
                                    <a href="{{ url('teacher') }}" class="btn">Kembali</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('mobile/js/script.js') }}"></script>
    <script>
        $(document).ready(function() {
            const kelas_jam = `{{ session('kelas_jam') }}`;
            const kelas = kelas_jam.split('|')[0];
            const jam = kelas_jam.split('|')[1];

            $.ajax({
                url: `{{ url('teacher/get_jurnal/${kelas}/${jam}') }}`,
                type: 'get',
                success: function(result) {
                    let split_name = result.user_data.name.split(' ');
                    let name = '';

                    if(split_name.length > 1) {
                        name = split_name[0] + ' ' + split_name[1];
                    } else {
                        name = split_name[0];
                    };

                    $('#user-name-here').text('Hi! ' + name);
                    $('[name="guru_mapel_id"]').val(result.schedule.guru_mapel_id);
                    $('[name="kelas"]').val(result.schedule.kelas.jenjang.jenjang + ' ' + result.schedule.kelas.name);
                    $('[name="jam_mulai"]').val(result.schedule.mulai);
                    $('[name="jam_selesai"]').val(result.schedule.selesai);
                    $('#start-time').html(result.schedule.mulai);
                    $('#kelas-name-tag').html(result.schedule.kelas.jenjang.jenjang + ' ' + result.schedule.kelas.name);
                    $('#end-time').html(result.schedule.selesai);
                    $('#teacher-name').html(result.user_data.name);
                    $('#subject-name').html(result.schedule.guru_mapel.mapel.nama_mapel);
                }
            });

            function setLoading() {
                $('.loading-animation').attr('style', 'display: flex');
            };

            function removeLoading() {
                $('.loading-animation').attr('style', 'display: none');
            };

            $('#jurnal-form').on('submit', function(event) {
                event.preventDefault();

                setLoading();

                $.ajax({
                    url: `{{ url('teacher/send_jurnal') }}`,
                    type: 'post',
                    data: $('#jurnal-form').serialize(),
                    success: function(result) {
                        console.log(result);
                        if(result.success == true) {
                            window.location.href = `{{ url('teacher') }}`;
                        };

                        let dumpErr = '';
                        for (let key in result.notification) {
                            if (result.notification.hasOwnProperty(key)) {
                                dumpErr += `
                                    <div class="alert-box success">
                                        <div class="alert-icon">
                                            <i class="fal fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="alert-content">${result.notification[key]}</div>
                                    </div>
                                `;
                            };
                        };
                        $('#alert-container').html(dumpErr);

                        removeLoading();
                    }
                });
            });
        });
    </script>
</body>
</html>