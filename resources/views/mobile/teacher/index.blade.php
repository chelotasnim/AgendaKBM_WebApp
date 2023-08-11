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
                                        <p>Kamis</p>
                                        <p>10 Agt 2023</p>
                                    </div>
                                    <div class="box-container">
                                         <div class="schedule-box">
                                            <div class="schedule-header">
                                                <div class="schedule-name">Jam Ke 0</div>
                                                <div class="schedule-status badge on">Berlangsung</div>
                                            </div>
                                            <div class="schedule-detail">
                                                <h3>Pemrograman Web dan Perangkat Bergerak</h3>
                                                <p>~ XII RPL 1</p>
                                            </div>
                                            <div class="schedule-range">
                                                <div class="schedule-time">07:00</div>
                                                <span>/</span>
                                                <div class="schedule-time">09:00</div>
                                            </div>
                                        </div>
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