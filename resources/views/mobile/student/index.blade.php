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
    <nav>
        <div id="home" class="nav-icon active">
            <i class="fal fa-th-list"></i>
        </div>
        <div id="schedule" class="nav-icon">
            <i class="fal fa-calendar-alt"></i>
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
                    <span class="hours">16</span>
                    <br>
                    <span class="minutes">00</span>
                </div>
            </div>
            <div class="wave-illusion">
                <div class="cover"></div>
            </div>
        </header>
        <div id="section-wrapper">
            {{-- <div id="schedules" class="section">
                <div class="floating-header as-select">
                    <div class="arrow">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                    <div class="select-val">Senin</div>
                    <div class="arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                <div class="schedule-list">
                    <div class="list-heading">
                        <p>XII RPL 1</p>
                        <p>Senin</p>
                        <p>11 Jam</p>
                    </div>
                    <div class="box-container">
                        <div class="schedule-box">
                            <div class="schedule-header">
                                <div class="schedule-name">Jam Ke 0</div>
                                <div class="schedule-status badge on">Berlangsung</div>
                            </div>
                            <div class="schedule-detail">
                                <h3>Pemrograman Web dan Perangkat Bergerak</h3>
                                <p>~ Hendro Setyono</p>
                            </div>
                            <div class="schedule-range">
                                <div class="schedule-time">07:00</div>
                                <span>/</span>
                                <div class="schedule-time">10:10</div>
                            </div>
                        </div>
                        <div class="schedule-box">
                            <div class="schedule-header">
                                <div class="schedule-name">Jam Ke 0</div>
                                <div class="schedule-status badge on">Berlangsung</div>
                            </div>
                            <div class="schedule-detail">
                                <h3>Pemrograman Web dan Perangkat Bergerak</h3>
                                <p>~ Hendro Setyono</p>
                            </div>
                            <div class="schedule-range">
                                <div class="schedule-time">07:00</div>
                                <span>/</span>
                                <div class="schedule-time">10:10</div>
                            </div>
                        </div>
                        <div class="schedule-box">
                            <div class="schedule-header">
                                <div class="schedule-name">Jam Ke 0</div>
                                <div class="schedule-status badge on">Berlangsung</div>
                            </div>
                            <div class="schedule-detail">
                                <h3>Pemrograman Web dan Perangkat Bergerak</h3>
                                <p>~ Hendro Setyono</p>
                            </div>
                            <div class="schedule-range">
                                <div class="schedule-time">07:00</div>
                                <span>/</span>
                                <div class="schedule-time">10:10</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <script src="{{ asset('mobile/js/script.js') }}"></script>
    <script>
        $(document).ready(function() {
            const user_id = "{{ Auth::guard('student')->user()->id }}";
            function setHome() {
                $.ajax({
                    url: `{{ url('api/student/${user_id}') }}`,
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
                        let boxes = '';
                        $.each(result.today_schedule, function(index, schedule) {
                            let class_for_color = '';
                            if(schedule.keterangan == 'Telah Berakhir') {
                                class_for_color = 'grey';
                            } else if(schedule.keterangan == 'Akan Dimulai') {
                                class_for_color = 'red';
                            };
                            boxes += `
                                <div class="schedule-box">
                                    <div class="schedule-header">
                                        <div class="schedule-name">Jam Ke ${schedule.jam_ke}</div>
                                        <div class="schedule-status badge on ${class_for_color}">${schedule.keterangan}</div>
                                    </div>
                                    <div class="schedule-detail">
                                        <h3>${schedule.mapel.nama_mapel}</h3>
                                        <p>~ ${schedule.guru.name}</p>
                                    </div>
                                    <div class="schedule-range">
                                        <div class="schedule-time">${schedule.jam_mulai}</div>
                                        <span>/</span>
                                        <div class="schedule-time">${schedule.jam_selesai}</div>
                                    </div>
                                </div>
                            `; 
                        });

                        $('#section-wrapper').html(`
                            <div id="home" class="section">
                                <div class="floating-header">
                                    <div class="icon">
                                        <i class="fal fa-user-clock"></i>
                                    </div>
                                    <div class="title">Jadwal Kelas Anda Hari Ini</div>
                                </div>
                                <div class="schedule-list">
                                    <div class="list-heading">
                                        <p>${result.main_data.kelas.jenjang.jenjang + ' ' + result.main_data.kelas.name}</p>
                                        <p>${result.now_date.day_name}</p>
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

            function setProfile() {
                $.ajax({
                    url: `{{ url('api/student/${user_id}') }}`,
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
                            <div id="profile" class="section">
                                <div class="floating-header">
                                    <div class="icon">
                                        <i class="fal fa-address-card"></i>
                                    </div>
                                    <div class="title">Profil Akun Anda</div>
                                </div>
                                <div class="list-heading">
                                    <p>Status Akun :</p>
                                    <p>Akun Siswa</p>
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
                                    <a href="{{ url('student_logout') }}" class="btn on">Keluar</a>
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