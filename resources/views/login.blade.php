<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ asset('/plugins/fontawesome-5-pro-master/css/all.css') }}">
    <link rel="shortcut icon" href="{{ asset('/assets/app-images/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('auth/css/style.css') }}">
    <title>Aplikasi Agenda KBM | Masuk</title>
</head>
<body>
    <div class="page auth-page">
        @if (session()->has('credential_failed'))
            <div id="alert-container">
                <div class="alert-box">
                    <div class="alert-icon">
                        <i class="fal fa-exclamation-triangle"></i>
                    </div>
                    <div class="alert-content">{{ session('credential_failed') }}</div>
                </div>
            </div>
            @else 
                <div class="welcome-cover">
                    <img class="left-wave" src="{{ asset('auth/images/wave.png') }}" draggable="false"/>
                    <div class="logo">
                        <img src="{{ asset('/assets/app-images/agenda_logo_white.png') }}" draggable="false"/>
                    </div>
                </div>
        @endif
        <form action="{{ url('/') }}" method="post" class="auth-form">
            @csrf
            <div class="form-header">
                <img src="{{ asset('/assets/app-images/small_logo.png') }}" draggable="false"/>
            </div>
            <div class="form-body">
                <div class="form-title">
                    <p>Agenda KBM</p>
                    <span>Masuk untuk memulai sesi</span>
                </div>
                <div class="input-group">
                    <input type="text" name="email" autocomplete="off">
                    <label for="email">Email</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password">
                    <label for="password">Password</label>
                </div>
                <button class="btn on">Masuk</button>
            </div>
            <div class="form-footer">
                <p>Belum Punya Akun? <a href="{{ url('regist') }}" class="regist-link">Daftar disini.</a></p>
            </div>
        </form>
    </div>
    <script src="{{ asset('auth/js/auth.js') }}"></script>
</body>
</html>