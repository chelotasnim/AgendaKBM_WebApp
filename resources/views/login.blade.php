<html lang="en"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplikasi Agenda KBM | Masuk</title>
  
    <!-- Google Font: Source Sans Pro -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/dist/css/adminlte.min.css') }}">
  <link rel="shortcut icon" href="{{ asset('/assets/app-images/favicon.png') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ asset('/assets/custom-css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  </head>
  <body class="login-page" style="min-height: 495.6px;">
    <div id="toast-container" class="toast-top-right">
      @error('email')
    <div class="toast toast-error" aria-live="assertive"><div class="toast-message">Harap Mengisi Email</div></div>
    @enderror
    @error('password')
    <div class="toast toast-error" aria-live="assertive"><div class="toast-message">Harap Mengisi Password</div></div>
    @enderror
    @if (session()->has('failed'))
      <div class="toast toast-error" aria-live="assertive"><div class="toast-message">{{ session('failed') }}</div></div>
    @endif
    </div>
  <div class="login-box">
    <div class="login-logo">
      <a href="{{ url('/') }}"><b>Agenda</b>KBM</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Aplikasi Jurnal dan Jadwal KBM</p>
  
        <form action="{{ url('/') }}" method="post">
          @csrf
          <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              {{-- <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div> --}}
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn bg-teal btn-block">Masuk</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
  
        {{-- <div class="social-auth-links text-center mb-3">
          <p>- OR -</p>
          <a href="#" class="btn btn-block btn-primary">
            <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
          </a>
          <a href="#" class="btn btn-block btn-danger">
            <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
          </a>
        </div>
        <!-- /.social-auth-links -->
  
        <p class="mb-1">
          <a href="forgot-password.html">I forgot my password</a>
        </p>
        <p class="mb-0">
          <a href="register.html" class="text-center">Register a new membership</a>
        </p> --}}
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->
  
  <!-- jQuery -->
  <script src="{{ asset('/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('/dist/js/adminlte.min.js') }}"></script>

  <script>
    $(document).ready(function() {
      function removeEl() {
        $('.toast').remove();
      }
      setTimeout(removeEl, 4000);
    });
  </script>
  
  
  </body></html>