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
                    url: `{{ url('teacher/today') }}`,
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

                        $.each(result.schedules, function(index, schedule) {
                            let class_for_color = '';
                            let btn = '';
                            if(schedule.keterangan == 'Telah Berakhir') {
                                class_for_color = 'grey';
                            } else if(schedule.keterangan == 'Akan Dimulai') {
                                class_for_color = 'red';
                            };

                            if(schedule.next_access === true) {
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
                                        <h3>${schedule.guru_mapel.mapel.nama_mapel}</h3>
                                        <p>~ ${schedule.kelas.jenjang.jenjang + ' ' + schedule.kelas.name}</p>
                                        <br>
                                        ${btn}
                                    </div>
                                    <div class="schedule-range">
                                        <div class="schedule-time">${schedule.mulai}</div>
                                        <span>/</span>
                                        <div class="schedule-time">${schedule.selesai}</div>
                                    </div>
                                </div>
                            `;
                        });

                        if(result.found == false) {
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

            function setSchedule(selected_day) {
                $.ajax({
                    url: `{{ url('teacher/${selected_day}') }}`,
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

                        $.each(result.schedules, function(index, schedule) {
                            boxes += `
                                <div class="schedule-box">
                                    <div class="schedule-header">
                                        <div class="schedule-name">Jam Ke ${schedule.jam_ke}</div>
                                    </div>
                                    <div class="schedule-detail">
                                        <h3>${schedule.guru_mapel.mapel.nama_mapel}</h3>
                                        <p>~ ${schedule.kelas.jenjang.jenjang + ' ' + schedule.kelas.name}</p>
                                        <br>
                                    </div>
                                    <div class="schedule-range">
                                        <div class="schedule-time">${schedule.mulai}</div>
                                        <span>/</span>
                                        <div class="schedule-time">${schedule.selesai}</div>
                                    </div>
                                </div>
                            `;
                        });

                        count_jam = 0;
                        if(result.found == false) {
                            boxes += `
                                <div class="schedule-box free-day">
                                    <i class="fal fa-mug-hot"></i>
                                    <p>Tidak Ada Jadwal Mengajar</p>
                                </div>
                            `;
                        } else {
                            count_jam = result.schedules.length;
                        };
                        
                        $('#user-name-here').text('Hi! ' + name);
                        $('#section-wrapper').html(`
                            <div id="home-wrapper" class="section">
                                <div class="floating-select as-select">
                                    <div class="arrow previous">
                                        <i class="fas fa-chevron-left"></i>
                                    </div>
                                    <div class="select-val day-name">${result.now_date.day_name}</div>
                                    <div class="arrow next">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                                <div class="schedule-list">
                                    <div class="list-heading">
                                        <p>Jadwal</p>
                                        <p>${count_jam} Jam</p>
                                    </div>
                                    <div class="box-container">
                                        ${boxes}
                                    </div>
                                </div>
                            </div>
                        `);

                        $('.arrow.previous').on('click', function() {
                            setSchedule(`${result.yesterday}`);
                        });

                        $('.arrow.next').on('click', function() {
                            setSchedule(`${result.tomorrow}`);
                        });
                    }
                });
            };
            $('#schedule').on('click', function() {
                setSchedule('today');
            });
        });

        function setProfile() {
                $.ajax({
                    url: `{{ url('teacher/today') }}`,
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
                                    <div class="button-group">
                                        <a href="{{ url('teacher_logout') }}" class="btn on">Keluar</a>
                                        <button id="edit-profile" class="btn">Ubah Data</button>
                                    </div>
                                </div>
                            </div>
                        `);

                        $('#edit-profile').on('click', function() {
                            editProfile();
                        });
                    }
                });
            };
            $('#profile').on('click', setProfile);

            function editProfile() {
                $.ajax({
                    url: `{{ url('teacher/today') }}`,
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
                                    <div class="title">Ubah Profil Akun Anda</div>
                                </div>
                                <div class="list-heading">
                                    <p>Status Akun :</p>
                                    <p>Akun Guru</p>
                                </div>
                                <form id="profile-edit" method="post" class="profile-container">
                                    @csrf
                                    <div class="profile-box">
                                        <div class="icon">
                                            <i class="fal fa-user-tag"></i>
                                        </div>
                                        <input type="text" name="name" autocomplete="off" value="${result.main_data.name}" placeholder="Nama Anda">
                                    </div>
                                    <div class="profile-box">
                                        <div class="icon">
                                            <i class="fal fa-tag"></i>
                                        </div>
                                        <input type="text" name="username" autocomplete="off" value="${result.main_data.username}" placeholder="Nama Akun Anda">
                                    </div>
                                    <div class="profile-box">
                                        <div class="icon">
                                            <i class="fal fa-at"></i>
                                        </div>
                                        <input type="email" name="email" autocomplete="off" value="${result.main_data.email}" placeholder="Akun Email Anda">
                                    </div>
                                    <div class="profile-box">
                                        <div class="icon">
                                            <i class="fal fa-user-lock"></i>
                                        </div>
                                        <input type="password" name="password" autocomplete="off" placeholder="Ubah Password Anda">
                                    </div>
                                    <div class="button-group">
                                        <button type="button" onclick="send_update()" class="btn on">Simpan</button>
                                        <button type="button" id="show-profile" class="btn">Batalkan</button>
                                    </div>
                                </form>
                            </div>
                        `);

                        $('#show-profile').on('click', function() {
                            setProfile();
                        });
                    }
                });
            };

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

            function send_update() {
                $('#profile-edit').on('submit', function(event) {
                    event.preventDefault();

                    setLoading();

                    $.ajax({
                        url: `{{ url('teacher/update') }}`,
                        data: $('#profile-edit').serialize(),
                        type: 'post',

                        success: function(result) {
                            let dumpErr = '';
                            for (let key in result.notification) {
                                if (result.notification.hasOwnProperty(key)) {
                                    let color = '';
                                    if(result.success === true) {
                                        color = ' success';
                                    };

                                    dumpErr += `
                                        <div class="alert-box${color}">
                                            <div class="alert-icon">
                                                <i class="fal fa-exclamation-triangle"></i>
                                            </div>
                                            <div class="alert-content">${result.notification[key]}</div>
                                        </div>
                                    `;
                                };
                            };
                            $('#alert-container').html(dumpErr);

                            if(result.success === true) {
                                setProfile();
                            };

                            removeLoading();
                        }
                    });
                });

                $('#profile-edit').trigger('submit');
            };
    </script>
</body>
</html>