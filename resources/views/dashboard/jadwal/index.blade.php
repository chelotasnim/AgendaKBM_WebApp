@extends('dashboard.template.master')

@section('dashboard')

<div id="kelas-page" class="container-xxl flex-grow-1 container-p-y px-4">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Basis Data / Referensi / Kelas {{ $kelas->jenjang->jenjang . ' ' . $kelas->name }} /</span> Jadwal</h4>
    <div id="toast-container" class="toast-top-right">
    </div>
    <div class="row">
        <div class="col-xl-12">
            <form method="post" class="card card-teal collapsed-card" id="add-form">
                @csrf
                <div class="card-header">
                  <h3 class="card-title">Tambah Jadwal KBM Kelas {{ $kelas->jenjang->jenjang . ' ' . $kelas->name }}</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 px-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" id="inputMainId" name="kelas_id" class="d-none" value="{{ $kelas->id }}" readonly>
                                    <label for="inputName" class="text-secondary font-weight-normal">Nama Jadwal <span style="font-size: 20px" class="text-danger">*</span></label>
                                    <input type="text" id="inputName" name="nama_jadwal" class="form-control" autocomplete="off" placeholder="Contoh : Jadwal Normal Nama_Kelas Tahun_Ajaran">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-top: 6px">
                                    <label for="inputStatus" class="text-secondary font-weight-normal">Status Jadwal</label>
                                    <select class="form-control select2bs4 select2-hidden-accessible" name="status" id="inputStatus" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                      <option selected hidden disabled>Pilih Status Jadwal</option>
                                        <option value="1" selected>Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="inputDesc" class="text-secondary font-weight-normal">Deskripsi Jadwal</label>
                                <textarea id="inputDesc" name="deskripsi_jadwal" class="form-control" placeholder="Masukkan Sedikit Deskripsi Tentang Jadwal..." style="height: 86px;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="schedule-card mt-4 card card-teal mb-0 card-outline card-outline-tabs" style="box-shadow: none">
                        <div class="card-header p-0 border-bottom-0">
                          <ul class="nav nav-tabs" id="schedule-tab" role="tablist" style="border-top: 1px solid rgb(225, 225, 225)">
                            <li class="pt-2 px-3 bg-teal"><h3 class="card-title">Hari KBM : </h3></li>
                            <li class="nav-item">
                              <a class="nav-link active" id="senin-tab" data-toggle="pill" href="#senin" role="tab" aria-controls="senin" aria-selected="true">Senin</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="selasa-tab" data-toggle="pill" href="#selasa" role="tab" aria-controls="selasa" aria-selected="true">Selasa</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="rabu-tab" data-toggle="pill" href="#rabu" role="tab" aria-controls="rabu" aria-selected="true">Rabu</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="kamis-tab" data-toggle="pill" href="#kamis" role="tab" aria-controls="kamis" aria-selected="true">Kamis</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="jumat-tab" data-toggle="pill" href="#jumat" role="tab" aria-controls="jumat" aria-selected="true">Jumat</a>
                            </li>
                          </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content pt-3" id="schedule-tabContent">
                                <div class="tab-pane fade active show" id="senin" role="tabpanel" aria-labelledby="senin-tab">
                                    <div class="mb-3" id="senin-container-0">
                                        <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                            <div class="col-md-12">
                                                <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">0</span></label>
                                                <input type="text" class="d-none jam_ke_senin" name="jam_ke_senin[]" value="0">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($guru[0]))
                                                        <select id="guru-senin-0" class="form-control select2bs4 select2-hidden-accessible" name="guru_senin[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                            <option selected hidden disabled>Pilih Guru Pengajar</option>
                                                            @foreach ($guru as $person)
                                                                <option value="{{ $person->id }}">{{ $person->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control" placeholder="Belum Ada Data Guru" disabled>
                                                          <small><a href="{{ url('dashboard/guru') }}" class="text-primary">Belum Ada Data Guru, Tambahkan Disini.</a></small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Mata Pelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($mapel[0]))
                                                        <select id="mapel-senin-0" class="form-control select2bs4 select2-hidden-accessible" name="mapel_senin[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                            <option selected hidden disabled>Pilih Mata Pelajaran</option>
                                                            @foreach ($mapel as $subject)
                                                                <option value="{{ $subject->id }}">{{ $subject->nama_mapel }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control" placeholder="Belum Ada Data Mata Pelajaran" disabled>
                                                        <small><a href="{{ url('dashboard/mapel') }}" class="text-primary">Belum Ada Data Mata Pelajaran, Tambahkan Disini.</a></small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="text-secondary font-weight-normal">Jam Mulai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    <input type="text" name="start_senin[]" class="form-control as-time" placeholder="Pilih Jam">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    <input type="text" name="end_senin[]" class="form-control as-time" placeholder="Pilih Jam">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 d-flex align-stretch" style="gap: 5px">
                                            <button type="button" data-day="senin" class="clone-btn btn bg-teal d-flex align-items-center" style="gap: 8px">
                                                <i class="fas fa-plus"></i>
                                                <span>
                                                    Tambahkan Jam KBM
                                                </span>
                                            </button>
                                            <button id="remove-in-senin" type="button" data-day="senin" style="display: none" class="remove-btn btn bg-danger align-items-center">
                                                <i class="fas fa-minus"></i>
                                                <span>
                                                    Kurangi Jam KBM
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="selasa" role="tabpanel" aria-labelledby="selasa-tab">
                                    <div class="mb-3" id="selasa-container-0">
                                        <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                            <div class="col-md-12">
                                                <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">0</span></label>
                                                <input type="text" class="d-none jam_ke_selasa" name="jam_ke_selasa[]" value="0">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($guru[0]))
                                                        <select id="guru-selasa-0" class="form-control select2bs4 select2-hidden-accessible" name="guru_selasa[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                            <option selected hidden disabled>Pilih Guru Pengajar</option>
                                                            @foreach ($guru as $person)
                                                                <option value="{{ $person->id }}">{{ $person->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control" placeholder="Belum Ada Data Guru" disabled>
                                                          <small><a href="{{ url('dashboard/guru') }}" class="text-primary">Belum Ada Data Guru, Tambahkan Disini.</a></small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Mata Pelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($mapel[0]))
                                                        <select id="mapel-selasa-0" class="form-control select2bs4 select2-hidden-accessible" name="mapel_selasa[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                            <option selected hidden disabled>Pilih Mata Pelajaran</option>
                                                            @foreach ($mapel as $subject)
                                                                <option value="{{ $subject->id }}">{{ $subject->nama_mapel }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control" placeholder="Belum Ada Data Mata Pelajaran" disabled>
                                                        <small><a href="{{ url('dashboard/mapel') }}" class="text-primary">Belum Ada Data Mata Pelajaran, Tambahkan Disini.</a></small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="text-secondary font-weight-normal">Jam Mulai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    <input type="text" name="start_selasa[]" class="form-control as-time" placeholder="Pilih Jam">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    <input type="text" name="end_selasa[]" class="form-control as-time" placeholder="Pilih Jam">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 d-flex align-stretch" style="gap: 5px">
                                            <button type="button" data-day="selasa" class="clone-btn btn bg-teal d-flex align-items-center" style="gap: 8px">
                                                <i class="fas fa-plus"></i>
                                                <span>
                                                    Tambahkan Jam KBM
                                                </span>
                                            </button>
                                            <button id="remove-in-selasa" type="button" data-day="selasa" style="display: none" class="remove-btn btn bg-danger align-items-center">
                                                <i class="fas fa-minus"></i>
                                                <span>
                                                    Kurangi Jam KBM
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="rabu" role="tabpanel" aria-labelledby="rabu-tab">
                                    <div class="mb-3" id="rabu-container-0">
                                        <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                            <div class="col-md-12">
                                                <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">0</span></label>
                                                <input type="text" class="d-none jam_ke_rabu" name="jam_ke_rabu[]" value="0">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($guru[0]))
                                                        <select id="guru-rabu-0" class="form-control select2bs4 select2-hidden-accessible" name="guru_rabu[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                            <option selected hidden disabled>Pilih Guru Pengajar</option>
                                                            @foreach ($guru as $person)
                                                                <option value="{{ $person->id }}">{{ $person->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control" placeholder="Belum Ada Data Guru" disabled>
                                                          <small><a href="{{ url('dashboard/guru') }}" class="text-primary">Belum Ada Data Guru, Tambahkan Disini.</a></small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Mata Pelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($mapel[0]))
                                                        <select id="mapel-rabu-0" class="form-control select2bs4 select2-hidden-accessible" name="mapel_rabu[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                            <option selected hidden disabled>Pilih Mata Pelajaran</option>
                                                            @foreach ($mapel as $subject)
                                                                <option value="{{ $subject->id }}">{{ $subject->nama_mapel }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control" placeholder="Belum Ada Data Mata Pelajaran" disabled>
                                                        <small><a href="{{ url('dashboard/mapel') }}" class="text-primary">Belum Ada Data Mata Pelajaran, Tambahkan Disini.</a></small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="text-secondary font-weight-normal">Jam Mulai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    <input type="text" name="start_rabu[]" class="form-control as-time" placeholder="Pilih Jam">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    <input type="text" name="end_rabu[]" class="form-control as-time" placeholder="Pilih Jam">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 d-flex align-stretch" style="gap: 5px">
                                            <button type="button" data-day="rabu" class="clone-btn btn bg-teal d-flex align-items-center" style="gap: 8px">
                                                <i class="fas fa-plus"></i>
                                                <span>
                                                    Tambahkan Jam KBM
                                                </span>
                                            </button>
                                            <button id="remove-in-rabu" type="button" data-day="rabu" style="display: none" class="remove-btn btn bg-danger align-items-center">
                                                <i class="fas fa-minus"></i>
                                                <span>
                                                    Kurangi Jam KBM
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="kamis" role="tabpanel" aria-labelledby="kamis-tab">
                                    <div class="mb-3" id="kamis-container-0">
                                        <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                            <div class="col-md-12">
                                                <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">0</span></label>
                                                <input type="text" class="d-none jam_ke_kamis" name="jam_ke_kamis[]" value="0">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($guru[0]))
                                                        <select id="guru-kamis-0" class="form-control select2bs4 select2-hidden-accessible" name="guru_kamis[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                            <option selected hidden disabled>Pilih Guru Pengajar</option>
                                                            @foreach ($guru as $person)
                                                                <option value="{{ $person->id }}">{{ $person->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control" placeholder="Belum Ada Data Guru" disabled>
                                                          <small><a href="{{ url('dashboard/guru') }}" class="text-primary">Belum Ada Data Guru, Tambahkan Disini.</a></small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Mata Pelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($mapel[0]))
                                                        <select id="mapel-kamis-0" class="form-control select2bs4 select2-hidden-accessible" name="mapel_kamis[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                            <option selected hidden disabled>Pilih Mata Pelajaran</option>
                                                            @foreach ($mapel as $subject)
                                                                <option value="{{ $subject->id }}">{{ $subject->nama_mapel }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control" placeholder="Belum Ada Data Mata Pelajaran" disabled>
                                                        <small><a href="{{ url('dashboard/mapel') }}" class="text-primary">Belum Ada Data Mata Pelajaran, Tambahkan Disini.</a></small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="text-secondary font-weight-normal">Jam Mulai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    <input type="text" name="start_kamis[]" class="form-control as-time" placeholder="Pilih Jam">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    <input type="text" name="end_kamis[]" class="form-control as-time" placeholder="Pilih Jam">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 d-flex align-stretch" style="gap: 5px">
                                            <button type="button" data-day="kamis" class="clone-btn btn bg-teal d-flex align-items-center" style="gap: 8px">
                                                <i class="fas fa-plus"></i>
                                                <span>
                                                    Tambahkan Jam KBM
                                                </span>
                                            </button>
                                            <button id="remove-in-kamis" type="button" data-day="kamis" style="display: none" class="remove-btn btn bg-danger align-items-center">
                                                <i class="fas fa-minus"></i>
                                                <span>
                                                    Kurangi Jam KBM
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="jumat" role="tabpanel" aria-labelledby="jumat-tab">
                                    <div class="mb-3" id="jumat-container-0">
                                        <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                            <div class="col-md-12">
                                                <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">0</span></label>
                                                <input type="text" class="d-none jam_ke_jumat" name="jam_ke_jumat[]" value="0">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($guru[0]))
                                                        <select id="guru-jumat-0" class="form-control select2bs4 select2-hidden-accessible" name="guru_jumat[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                            <option selected hidden disabled>Pilih Guru Pengajar</option>
                                                            @foreach ($guru as $person)
                                                                <option value="{{ $person->id }}">{{ $person->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control" placeholder="Belum Ada Data Guru" disabled>
                                                          <small><a href="{{ url('dashboard/guru') }}" class="text-primary">Belum Ada Data Guru, Tambahkan Disini.</a></small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Mata Pelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($mapel[0]))
                                                        <select id="mapel-jumat-0" class="form-control select2bs4 select2-hidden-accessible" name="mapel_jumat[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                            <option selected hidden disabled>Pilih Mata Pelajaran</option>
                                                            @foreach ($mapel as $subject)
                                                                <option value="{{ $subject->id }}">{{ $subject->nama_mapel }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control" placeholder="Belum Ada Data Mata Pelajaran" disabled>
                                                        <small><a href="{{ url('dashboard/mapel') }}" class="text-primary">Belum Ada Data Mata Pelajaran, Tambahkan Disini.</a></small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="text-secondary font-weight-normal">Jam Mulai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    <input type="text" name="start_jumat[]" class="form-control as-time" placeholder="Pilih Jam">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    <input type="text" name="end_jumat[]" class="form-control as-time" placeholder="Pilih Jam">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 d-flex align-stretch" style="gap: 5px">
                                            <button type="button" data-day="jumat" class="clone-btn btn bg-teal d-flex align-items-center" style="gap: 8px">
                                                <i class="fas fa-plus"></i>
                                                <span>
                                                    Tambahkan Jam KBM
                                                </span>
                                            </button>
                                            <button id="remove-in-jumat" type="button" data-day="jumat" style="display: none" class="remove-btn btn bg-danger align-items-center">
                                                <i class="fas fa-minus"></i>
                                                <span>
                                                    Kurangi Jam KBM
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex p-3 justify-content-end align-items-stretch" style="gap: 12px">
                                <a href="{{ url('dashboard/kelas') }}" class="btn text-teal">Kembali Ke Kelas</a>
                                <button type="submit" class="btn bg-teal">Kirim Jadwal</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Jadwal KBM Kelas {{ $kelas->jenjang->jenjang . ' ' . $kelas->name }}</h3>
                </div>
                <div class="card-body">
                    <table id="jadwal-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Jadwal</th>
                                <th>Deskripsi Jadwal</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">
                                    <i class="fas fa-ellipsis-v"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-import" style="display: none; padding-right: 17px;" aria-hidden="true" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <form id="import-jam-form" enctype="multipart/form-data" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Impor Jam KBM</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <div class="custom-file">
                          <input type="file" name="jam_excel" class="custom-file-input" accept=".xls, .xlsx" id="jamExcel">
                          <label class="custom-file-label" for="jamExcel">Impor Template Excel</label>
                        </div>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <div class="d-flex justify-content-start">
                      <a href="{{ asset('templates/ImporJam.xlsx') }}" class="btn text-teal" download>Unduh Template Excel</a>
                    </div>
                    <div class="d-flex justify-content-end" style="gap: 5px">
                      <button type="button" class="btn border-success border-2 text-teal" data-dismiss="modal">Tutup</button>
                      <button id="impor-btn" type="submit" class="btn bg-teal">Impor</button>
                    </div>
                  </div>
            </form>
          </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@include('dashboard.jadwal.data')
    
@endsection