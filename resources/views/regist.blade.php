<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ asset('/plugins/fontawesome-5-pro-master/css/all.css') }}">
    <link rel="shortcut icon" href="{{ asset('/assets/app-images/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('auth/css/style.css') }}">
    <script src="{{ asset('/plugins/jquery/jquery.min.js') }}"></script>
    <title>Aplikasi Agenda KBM | Masuk</title>
</head>
<body>
    <div class="page auth-page">
        <div id="alert-container">
        </div>
        <div class="auth-form">
            <div class="form-header">
                <img src="{{ asset('/assets/app-images/small_logo.png') }}" draggable="false"/>
            </div>
            <div class="form-title">
                <p>Agenda KBM</p>
                <span>Daftarkan Akun Pengguna Baru</span>
            </div>
            <form data-slide="select_role" class="form-body">
                <div class="option-group">
                    <div class="box-option">
                        <div class="value">
                            <div class="icon"><i class="fal fa-user-chart"></i></div>
                            <p class="desc">Akun Guru</p>
                        </div>
                        <input type="radio" value="0" name="role">
                    </div>
                    <div class="box-option">
                        <div class="value">
                            <div class="icon"><i class="fal fa-user-graduate"></i></div>
                            <p class="desc">Akun Siswa</p>
                        </div>
                        <input type="radio" value="1" name="role">
                    </div>
                </div>
            </form>
            <form style="display: none" method="post" id="add-guru" data-slide="input_master" data-entity="0" class="form-body">
                @csrf
                <div class="input-group">
                    <input type="text" name="name" autocomplete="off">
                    <label for="name">Nama Guru</label>
                </div>
                <div class="input-group">
                    <input type="text" name="username" autocomplete="off">
                    <label for="username">Username Akun Guru</label>
                </div>
                <div class="input-group">
                    <input type="text" name="email" autocomplete="off">
                    <label for="email">Email Akun Guru</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" autocomplete="off">
                    <label for="password">Password</label>
                </div>
                <div class="action-group">
                    <button type="button" data-slide="select_role" class="btn grey back-btn">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="submit" class="btn on">Daftarkan Akun</button>
                </div>
            </form>
            <form style="display: none" method="post" id="add-siswa" data-slide="input_master" data-entity="1" class="form-body">
                @csrf
                <div class="input-group">
                    <input id="select-modal" type="text" autocomplete="off" readonly>
                    <input type="text" style="display: none" name="kelas_id" id="id-kelas" readonly>
                    <label>Pilih Kelas Siswa</label>
                </div>
                <div class="input-group">
                    <input type="text" name="name" autocomplete="off">
                    <label for="name">Nama Siswa</label>
                </div>
                <div class="input-group">
                    <input type="text" name="username" autocomplete="off">
                    <label for="username">Username Akun Siswa</label>
                </div>
                <div class="input-group">
                    <input type="text" name="email" autocomplete="off">
                    <label for="email">Email Akun Siswa</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" autocomplete="off">
                    <label for="password">Password</label>
                </div>
                <div class="action-group">
                    <button type="button" data-slide="select_role" class="btn grey back-btn">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="submit" class="btn on">Daftarkan Akun</button>
                </div>
            </form>
            <div class="action-group">
                <button id="next-btn" class="btn on">Berikutnya</button>
            </div>
            <div class="form-footer">
                <p>Sudah Punya Akun? <a href="{{ url('/') }}" class="regist-link">Masuk disini.</a></p>
            </div>
        </div>
        <div class="select-kelas-modal">
            <div class="select-card">
                <div class="select-header">
                    <div class="input-group">
                        <input type="text" class="row-search active" autocomplete="off" autofocus>
                        <label>Cari Nama Kelas</label>
                    </div>
                </div>
                <div class="select-val-container">
                    @if (isset($all_kelas[0]))
                        @foreach ($all_kelas as $kelas)
                            <div data-id="{{ $kelas->id }}" data-nama-kelas="{{ $kelas->jenjang->jenjang . ' ' . $kelas->name }}" class="select-val">{{ $kelas->jenjang->jenjang . ' ' . $kelas->name }}</div>
                        @endforeach
                    @else
                        <div class="disabled center-placeholder">Belum Ada Data Kelas</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('auth/js/auth.js') }}"></script>
    <script>
        $(document).ready(function() {
            function sendData(uri, form) {
                $('.page').append(`
                    <div class="loading-animation">
                        <i class="fas fa-spinner-third"></i>
                    </div>
                `);                

                $.ajax({
                    url: uri,
                    data: $(form).serialize(),
                    type: 'post',
                    success: function(result) {
                        let dumpNotif = '';
                        for (let key in result.notification) {
                            if (result.notification.hasOwnProperty(key)) {
                                if(result.success === true) {
                                    dumpNotif += `
                                    <div class="alert-box success">
                                        <div class="alert-icon">
                                            <i class="fal fa-user-check"></i>
                                        </div>
                                        <div class="alert-content">${result.notification[key]}</div>
                                    </div>
                                `;
                                } else {
                                    dumpNotif += `
                                    <div class="alert-box">
                                        <div class="alert-icon">
                                            <i class="fal fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="alert-content">${result.notification[key]}</div>
                                    </div>
                                `;
                                };
                            };
                        };
                        $('#alert-container').html(dumpNotif);

                        if(result.success === true) {
                            $('.form-body').remove();
                            $('.action-group').remove();
                            $('.form-footer').remove();
                            
                            let content = $('.auth-form').html();
                            content += `
                            <div class="success-body">
                                <i class="fal fa-user-check"></i>
                            </div>
                            <div class="action-group">
                                <a href="{{ url('/') }}" class="btn on">Masuk</a>
                            </div>
                            `;

                            $('.auth-form').html(content);
                        };

                        $('.loading-animation').remove();
                    }
                });
            };

            $('#add-guru').on('submit', function(e) {
                e.preventDefault();
                sendData("{{ url('api/teacher/self_registration') }}", '#add-guru');
            });

            $('#add-siswa').on('submit', function(e) {
                e.preventDefault();
                sendData("{{ url('api/siswa/self_registration') }}", '#add-siswa');
            });
        });
    </script>
</body>
</html>