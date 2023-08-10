<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Aplikasi Agenda KBM | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('/plugins/fontawesome-5-pro-master/css/all.css') }}">
  <!-- Ionicons -->
  <!-- Tempusdominus Bootstrap 4 -->
  <!-- iCheck -->
  <!-- JQVMap -->
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <link rel="shortcut icon" href="{{ asset('/assets/app-images/favicon.png') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ asset('/assets/custom-css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <script src="{{ asset('/plugins/jquery/jquery.min.js') }}"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

  <!-- Preloader -->
  {{-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('assets/app-images/agenda_logo.png') }}" alt="AdminLTELogo" height="75" width="155">
  </div> --}}

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-grey navbar-light layout-footer-fixed">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="{{ asset('dist/img/user1-128x128.jpg') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="{{ asset('dist/img/user8-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="{{ asset('dist/img/user3-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-teal elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link d-flex justify-content-center">
      <img src="{{ asset('/assets/app-images/agenda_logo.png') }}" alt="Agenda Logo" draggable="false" style="width: 150px; height: 50px; object-fit: cover"/>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel pb-3 mb-3 d-flex" style="margin-top: 40px">
        <div class="image">
          <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
        <a href="{{ url('logout') }}" style="font-size: 12px;height: max-content; padding: 0px 5px" class="ml-auto mr-2 mt-2 btn btn-sm btn-secondary text-light">Keluar</a>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item @if($page === 'dashboard' || $page === 'daily-recapt'){{ 'menu-open' }} @endif">
            <a href="#" class="nav-link @if($page === 'dashboard' || $page === 'daily-recapt'){{ 'active' }} @endif">
              <i class="nav-icon fal fa-analytics"></i>
              <p>
                Beranda
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.html" class="nav-link @if($page === 'dashboard'){{ 'active' }} @endif">
                  @if($page === 'dashboard')
                  <i class="text-teal fal fa-grip-lines nav-icon"></i>
                @else
                  <i class="fal fa-grip-lines nav-icon"></i>
                @endif
                  <p>Statistik Jurnal</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index2.html" class="nav-link @if($page === 'daily-recapt'){{ 'active' }} @endif">
                  @if($page === 'daily-recapt')
                    <i class="text-teal fal fa-grip-lines nav-icon"></i>
                  @else
                    <i class="fal fa-grip-lines nav-icon"></i>
                  @endif
                  <p>Rekap Jurnal Harian</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-header">BASIS DATA</li>
          <li class="nav-item @if($page === 'jenjang_kelas' || $page === 'mapel' || $page === 'kelas'){{ 'menu-open' }} @endif">
            <a href="#" class="nav-link @if($page === 'jenjang_kelas' || $page === 'mapel' || $page === 'kelas'){{ 'active' }} @endif">
              <i class="nav-icon fal fa-layer-group"></i>
              <p>
                Referensi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('dashboard/mapel') }}" class="nav-link @if($page === 'mapel'){{ 'active' }} @endif">
                  @if($page === 'mapel')
                    <i class="text-teal fal fa-grip-lines nav-icon"></i>
                  @else
                    <i class="fal fa-grip-lines nav-icon"></i>
                  @endif
                  <p>Mata Pelajaran</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('dashboard/kelas') }}" class="nav-link @if($page === 'kelas'){{ 'active' }} @endif">
                  @if($page === 'kelas')
                    <i class="text-teal fal fa-grip-lines nav-icon"></i>
                  @else
                    <i class="fal fa-grip-lines nav-icon"></i>
                  @endif
                  <p>Kelas</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item @if($page === 'guru' || $page === 'siswa' || $page === 'admin'){{ 'menu-open' }} @endif">
            <a href="#" class="nav-link @if($page === 'guru' || $page === 'siswa' || $page === 'admin'){{ 'active' }} @endif">
              <i class="nav-icon fal fa-users-cog"></i>
              <p>
                Master
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('dashboard/admin') }}" class="nav-link @if($page === 'admin'){{ 'active' }} @endif">
                  @if($page === 'admin')
                    <i class="text-teal fal fa-grip-lines nav-icon"></i>
                  @else
                    <i class="fal fa-grip-lines nav-icon"></i>
                  @endif
                  <p>Akun Admin</p>
                </a>
                <a href="{{ url('dashboard/guru') }}" class="nav-link @if($page === 'guru'){{ 'active' }} @endif">
                  @if($page === 'guru')
                    <i class="text-teal fal fa-grip-lines nav-icon"></i>
                  @else
                    <i class="fal fa-grip-lines nav-icon"></i>
                  @endif
                  <p>Akun Guru</p>
                </a>
                <a href="{{ url('dashboard/siswa') }}" class="nav-link @if($page === 'siswa'){{ 'active' }} @endif">
                  @if($page === 'siswa')
                    <i class="text-teal fal fa-grip-lines nav-icon"></i>
                  @else
                    <i class="fal fa-grip-lines nav-icon"></i>
                  @endif
                  <p>Akun Siswa</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item @if($page === 'rekap' || $page === 'laporan'){{ 'menu-open' }} @endif">
            <a href="#" class="nav-link @if($page === 'rekap' || $page === 'laporan'){{ 'active' }} @endif">
              <i class="nav-icon fal fa-server"></i>
              <p>
                Transaksional
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('dashboard/rekap') }}" class="nav-link @if($page === 'rekap'){{ 'active' }} @endif">
                  @if($page === 'rekap')
                    <i class="text-teal fal fa-grip-lines nav-icon"></i>
                  @else
                    <i class="fal fa-grip-lines nav-icon"></i>
                  @endif
                  <p>Jurnal Harian</p>
                </a>
                <a href="{{ url('dashboard/laporan') }}" class="nav-link @if($page === 'laporan'){{ 'active' }} @endif">
                  @if($page === 'laporan')
                    <i class="text-teal fal fa-grip-lines nav-icon"></i>
                  @else
                    <i class="fal fa-grip-lines nav-icon"></i>
                  @endif
                  <p>Jurnal Lengkap</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-header">FITUR PENGGUNA</li>
          <li class="nav-item @if($page === 'jurnal_guru'){{ 'menu-open' }} @endif">
            <a href="#" class="nav-link @if($page === 'jurnal_guru'){{ 'active' }} @endif">
              <i class="nav-icon fal fa-chalkboard-teacher"></i>
              <p>
                Guru
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('dashboard/jurnal_guru') }}" class="nav-link @if($page === 'jurnal_guru'){{ 'active' }} @endif">
                  @if($page === 'jurnal_guru')
                    <i class="text-teal fal fa-grip-lines nav-icon"></i>
                  @else
                    <i class="fal fa-grip-lines nav-icon"></i>
                  @endif
                  <p>Jurnal Harian</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item @if($page === 'jadwal_siswa'){{ 'menu-open' }} @endif">
            <a href="#" class="nav-link @if($page === 'jadwal_siswa'){{ 'active' }} @endif">
              <i class="nav-icon fal fa-user-graduate"></i>
              <p>
                Siswa
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('dashboard/jadwal_siswa') }}" class="nav-link @if($page === 'jadwal_siswa'){{ 'active' }} @endif">
                  @if($page === 'jadwal_siswa')
                    <i class="text-teal fal fa-grip-lines nav-icon"></i>
                  @else
                    <i class="fal fa-grip-lines nav-icon"></i>
                  @endif
                  <p>Jadwal Pelajaran</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-header">LAINNYA</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-circle text-danger"></i>
              <p class="text">Aksi Darurat</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-circle text-warning"></i>
              <p>Info Penggunaan</p>
            </a>
          </li>
          <li class="nav-item mb-3">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-circle text-info"></i>
              <p>Dukungan Lanjutan</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <div class="content-wrapper">