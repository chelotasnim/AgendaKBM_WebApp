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
    <div class="entire">
        <div class="frame">
            <div class="welcome-cover">
                <img class="left-wave" src="{{ asset('auth/images/wave.png') }}" draggable="false"/>
                <div class="logo">
                    <img src="{{ asset('/assets/app-images/agenda_logo_white.png') }}" draggable="false"/>
                </div>
            </div>
            <nav>
                <div id="home" class="nav-icon active">
                    <i class="fal fa-calendar-alt"></i>
                </div>
                <div id="schedule" class="nav-icon">
                    <i class="fal fa-history"></i>
                </div>
                <div id="profile" class="nav-icon">
                    <i class="fal fa-address-card"></i>
                </div>
            </nav>
            <div class="page">
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
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('mobile/js/script.js') }}"></script>
    <script>
        $(document).ready(function() {
            const user_id = "{{ Auth::guard('teacher')->user()->id }}";
            function setHome() {
                $.ajax({
                    url: `{{ url('api/teacher/${user_id}') }}`,
                    type: 'get',
                    success: function(result) {
                        let split_name = result.main_data.name.split(' ');
                        let name = '';

                        if(split_name.length > 1) {
                            name = split_name[0] + ' ' + split_name[1];
                        } else {
                            name = split_name[0];
                        };

                        let boxes = '';

                        $.each(result.main_data.jam_mengajar, function(index, schedule) {
                            let class_for_color = '';
                            let btn = '';
                            if(schedule.keterangan == 'Telah Berakhir') {
                                class_for_color = 'grey';
                            } else if(schedule.keterangan == 'Akan Dimulai') {
                                class_for_color = 'red';
                            };

                            if(schedule.next_access != undefined) {
                                btn = `<a href="${schedule.next_access}" class="small-btn btn on">Isi Jurnal</a>`;
                            } else {
                                btn = '<a class="small-btn btn badge on grey" style="pointer-events: none">Isi Jurnal</a>';
                            };

                            boxes += `
                                <div class="schedule-box">
                                    <div class="schedule-header">
                                        <div class="schedule-name">Jam Ke ${schedule.jam_ke}</div>
                                        <div class="schedule-status badge on ${class_for_color}">${schedule.keterangan}</div>
                                    </div>
                                    <div class="schedule-detail">
                                        <h3>${schedule.mapel.nama_mapel}</h3>
                                        <p>~ ${schedule.jadwal.kelas.jenjang.jenjang + ' ' + schedule.jadwal.kelas.name}</p>
                                        <br>
                                        ${btn}
                                    </div>
                                    <div class="schedule-range">
                                        <div class="schedule-time">${schedule.jam_mulai}</div>
                                        <span>/</span>
                                        <div class="schedule-time">${schedule.jam_selesai}</div>
                                    </div>
                                </div>
                            `;
                        });

                        if(boxes == '') {
                            boxes += `
                                <div class="schedule-box free-day">
                                    <i class="fal fa-mug-hot"></i>
                                    <p>Tidak Ada Jadwal Mengajar</p>
                                </div>
                            `;
                        };
                        
                        $('#user-name-here').text('Hi! ' + name);
                        $('#section-wrapper').html(`
                            <div id="home-wrapper" class="section">
                                <div class="floating-header">
                                    <div class="icon">
                                        <i class="fal fa-user-clock"></i>
                                    </div>
                                    <div class="title">Jadwal Mengajar Hari Ini</div>
                                </div>
                                <div class="schedule-list">
                                    <div class="list-heading">
                                        <p>Jadwal</p>
                                        <p class="day-name">${result.now_date.day_name}</p>
                                        <p>${result.now_date.date}</p>
                                    </div>
                                    <div class="box-container">
                                        ${boxes}
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                });
            };
            setHome();
            $('#home').on('click', setHome);

            function setHistory() {
                $.ajax({
                    url: `{{ url('api/get_all_jurnal/${user_id}') }}`,
                    type: 'get',
                    success: function(result) {
                        let split_name = result.main_data.name.split(' ');
                        let name = '';

                        if(split_name.length > 1) {
                            name = split_name[0] + ' ' + split_name[1];
                        } else {
                            name = split_name[0];
                        };

                        let history_row = '';
                        $.each(result.main_data.jurnal, function(index, jurnal) {
                            history_row += `
                                <div class="profile-box">
                                    <div class="icon" style="font-size: 12px">
                                        ${jurnal.kelas}
                                    </div>
                                    <div class="content">${jurnal.mapel.nama_mapel}</div>
                                </div>
                            `;
                        });
                        
                        $('#user-name-here').text('Hi! ' + name);
                        $('#section-wrapper').html(`
                            <div id="profile-wrapper" class="section">
                                <div class="floating-header">
                                    <div class="icon" style="padding-left: 24px">
                                        <i class="fal fa-history"></i>
                                    </div>
                                    <div class="title">Riwayat Mengajar Bulan Ini</div>
                                </div>
                                <div class="list-heading">
                                    <p>Riwayat Mengajar</p>
                                    <p>${result.month}</p>
                                </div>
                                <div class="profile-container">
                                    ${history_row}
                                </div>
                            </div>
                        `);
                    }
                });
            };
            $('#schedule').on('click', setHistory);
            
            function setProfile() {
                $.ajax({
                    url: `{{ url('api/teacher/${user_id}') }}`,
                    type: 'get',
                    success: function(result) {
                        let split_name = result.main_data.name.split(' ');
                        let name = '';

                        if(split_name.length > 1) {
                            name = split_name[0] + ' ' + split_name[1];
                        } else {
                            name = split_name[0];
                        };
                        
                        $('#user-name-here').text('Hi! ' + name);
                        $('#section-wrapper').html(`
                            <div id="profile-wrapper" class="section">
                                <div class="floating-header">
                                    <div class="icon">
                                        <i class="fal fa-address-card"></i>
                                    </div>
                                    <div class="title">Profil Akun Anda</div>
                                </div>
                                <div class="list-heading">
                                    <p>Status Akun :</p>
                                    <p>Akun Guru</p>
                                </div>
                                <div class="profile-container">
                                    <div class="profile-box">
                                        <div class="icon">
                                            <i class="fal fa-user-tag"></i>
                                        </div>
                                        <div class="content">${result.main_data.name}</div>
                                    </div>
                                    <div class="profile-box">
                                        <div class="icon">
                                            <i class="fal fa-tag"></i>
                                        </div>
                                        <div class="content">${result.main_data.username}</div>
                                    </div>
                                    <div class="profile-box">
                                        <div class="icon">
                                            <i class="fal fa-at"></i>
                                        </div>
                                        <div class="content">${result.main_data.email}</div>
                                    </div>
                                </div>
                                <div class="button-group">
                                    <a href="{{ url('teacher_logout') }}" class="btn on">Keluar</a>
                                </div>
                            </div>
                        `);
                    }
                });
            };
            $('#profile').on('click', setProfile);
        });
    </script>
</body>
</html>