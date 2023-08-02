@extends('dashboard.template.master')

@section('dashboard')

<div id="kelas-page" class="container-xxl flex-grow-1 container-p-y px-4">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Basis Data / Referensi / Kelas {{ $jadwal->kelas->jenjang->jenjang . ' ' . $jadwal->kelas->name }} /</span> Tambah Jam</h4>
    <div id="toast-container" class="toast-top-right">
    </div>
    <div class="row">
        <div class="col-xl-12">
            <form method="post" class="card card-teal" id="column-form">
                @csrf
                <div class="card-header">
                  <h3 class="card-title">Tambah Jam KBM Kelas {{ $jadwal->kelas->jenjang->jenjang . ' ' . $jadwal->kelas->name }}</h3>
                </div>
                <input type="text" class="d-none" name="main_jadwal" value="{{ $jadwal->nama_jadwal }}">
                <div class="card-body p-0">
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
                                    @foreach ($jadwal->details as $detail)
                                        @if ($detail->hari == 'senin')
                                        <div class="mb-3" id="senin-container-{{ $detail->jam_ke }}">
                                            <input type="text" class="d-none" name="id_on_senin[]" value="{{ $detail->id }}">
                                            <input type="text" class="d-none total-senin" value="{{ $detail->jam_ke }}">
                                            <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                                <div class="col-md-12">
                                                    <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">{{ $detail->jam_ke }}</span></label>
                                                    <input type="text" class="d-none jam_ke_senin" name="jam_ke_senin[]" value="{{ $detail->jame_ke }}">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-top: 6px">
                                                        <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->guru->name }}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-top: 6px">
                                                        <label class="text-secondary font-weight-normal">Mata Pelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->mapel->nama_mapel }}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Mulai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->jam_mulai }}" class="form-control as-time" placeholder="Pilih Jam" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->jam_selesai }}" class="form-control as-time" placeholder="Pilih Jam" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                    <div class="mb-3" id="senin-container-n">
                                        <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                            <div class="col-md-12">
                                                <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">n</span></label>
                                                <input type="text" class="d-none jam_new_senin" name="jam_new_senin[]">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($guru[0]))
                                                        <select id="guru-senin-n" class="form-control select2bs4 select2-hidden-accessible" name="guru_senin[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                                        <select id="mapel-senin-n" class="form-control select2bs4 select2-hidden-accessible" name="mapel_senin[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                    @foreach ($jadwal->details as $detail)
                                        @if ($detail->hari == 'selasa')
                                        <div class="mb-3" id="selasa-container-{{ $detail->jam_ke }}">
                                            <input type="text" class="d-none" name="id_on_selasa[]" value="{{ $detail->id }}">
                                            <input type="text" class="d-none total-selasa" value="{{ $detail->jam_ke }}">
                                            <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                                <div class="col-md-12">
                                                    <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">{{ $detail->jam_ke }}</span></label>
                                                    <input type="text" class="d-none jam_ke_selasa" name="jam_ke_selasa[]" value="{{ $detail->jame_ke }}">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-top: 6px">
                                                        <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->guru->name }}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-top: 6px">
                                                        <label class="text-secondary font-weight-normal">Mata Pelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->mapel->nama_mapel }}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Mulai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->jam_mulai }}" class="form-control as-time" placeholder="Pilih Jam" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->jam_selesai }}" class="form-control as-time" placeholder="Pilih Jam" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                    <div class="mb-3" id="selasa-container-n">
                                        <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                            <div class="col-md-12">
                                                <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">n</span></label>
                                                <input type="text" class="d-none jam_new_selasa" name="jam_new_selasa[]">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($guru[0]))
                                                        <select id="guru-selasa-n" class="form-control select2bs4 select2-hidden-accessible" name="guru_selasa[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                                        <select id="mapel-selasa-n" class="form-control select2bs4 select2-hidden-accessible" name="mapel_selasa[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                    @foreach ($jadwal->details as $detail)
                                        @if ($detail->hari == 'rabu')
                                        <div class="mb-3" id="rabu-container-{{ $detail->jam_ke }}">
                                            <input type="text" class="d-none" name="id_on_rabu[]" value="{{ $detail->id }}">
                                            <input type="text" class="d-none total-rabu" value="{{ $detail->jam_ke }}">
                                            <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                                <div class="col-md-12">
                                                    <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">{{ $detail->jam_ke }}</span></label>
                                                    <input type="text" class="d-none jam_ke_rabu" name="jam_ke_rabu[]" value="{{ $detail->jame_ke }}">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-top: 6px">
                                                        <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->guru->name }}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-top: 6px">
                                                        <label class="text-secondary font-weight-normal">Mata Pelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->mapel->nama_mapel }}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Mulai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->jam_mulai }}" class="form-control as-time" placeholder="Pilih Jam" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->jam_selesai }}" class="form-control as-time" placeholder="Pilih Jam" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                    <div class="mb-3" id="rabu-container-n">
                                        <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                            <div class="col-md-12">
                                                <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">n</span></label>
                                                <input type="text" class="d-none jam_new_rabu" name="jam_new_rabu[]">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($guru[0]))
                                                        <select id="guru-rabu-n" class="form-control select2bs4 select2-hidden-accessible" name="guru_rabu[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                                        <select id="mapel-rabu-n" class="form-control select2bs4 select2-hidden-accessible" name="mapel_rabu[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                    @foreach ($jadwal->details as $detail)
                                        @if ($detail->hari == 'kamis')
                                        <div class="mb-3" id="kamis-container-{{ $detail->jam_ke }}">
                                            <input type="text" class="d-none" name="id_on_kamis[]" value="{{ $detail->id }}">
                                            <input type="text" class="d-none total-kamis" value="{{ $detail->jam_ke }}">
                                            <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                                <div class="col-md-12">
                                                    <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">{{ $detail->jam_ke }}</span></label>
                                                    <input type="text" class="d-none jam_ke_kamis" name="jam_ke_kamis[]" value="{{ $detail->jame_ke }}">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-top: 6px">
                                                        <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->guru->name }}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-top: 6px">
                                                        <label class="text-secondary font-weight-normal">Mata Pelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->mapel->nama_mapel }}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Mulai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->jam_mulai }}" class="form-control as-time" placeholder="Pilih Jam" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->jam_selesai }}" class="form-control as-time" placeholder="Pilih Jam" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                    <div class="mb-3" id="kamis-container-n">
                                        <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                            <div class="col-md-12">
                                                <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">n</span></label>
                                                <input type="text" class="d-none jam_new_kamis" name="jam_new_kamis[]">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($guru[0]))
                                                        <select id="guru-kamis-n" class="form-control select2bs4 select2-hidden-accessible" name="guru_kamis[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                                        <select id="mapel-kamis-n" class="form-control select2bs4 select2-hidden-accessible" name="mapel_kamis[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                    @foreach ($jadwal->details as $detail)
                                        @if ($detail->hari == 'jumat')
                                        <div class="mb-3" id="jumat-container-{{ $detail->jam_ke }}">
                                            <input type="text" class="d-none" name="id_on_jumat[]" value="{{ $detail->id }}">
                                            <input type="text" class="d-none total-jumat" value="{{ $detail->jam_ke }}">
                                            <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                                <div class="col-md-12">
                                                    <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">{{ $detail->jam_ke }}</span></label>
                                                    <input type="text" class="d-none jam_ke_jumat" name="jam_ke_jumat[]" value="{{ $detail->jame_ke }}">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-top: 6px">
                                                        <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->guru->name }}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-top: 6px">
                                                        <label class="text-secondary font-weight-normal">Mata Pelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->mapel->nama_mapel }}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Mulai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->jam_mulai }}" class="form-control as-time" placeholder="Pilih Jam" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $detail->jam_selesai }}" class="form-control as-time" placeholder="Pilih Jam" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                    <div class="mb-3" id="jumat-container-n">
                                        <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                            <div class="col-md-12">
                                                <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">n</span></label>
                                                <input type="text" class="d-none jam_new_jumat" name="jam_new_jumat[]">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group" style="margin-top: 6px">
                                                    <label class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                                                    @if (isset($guru[0]))
                                                        <select id="guru-jumat-n" class="form-control select2bs4 select2-hidden-accessible" name="guru_jumat[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                                        <select id="mapel-jumat-n" class="form-control select2bs4 select2-hidden-accessible" name="mapel_jumat[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                <a href="{{ url('dashboard/jadwal/' . $jadwal->kelas_id) }}" class="btn text-teal">Kembali</a>
                                <button type="submit" class="btn bg-teal">Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@include('dashboard.jadwal.data-column')
    
@endsection