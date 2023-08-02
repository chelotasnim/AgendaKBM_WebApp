@extends('dashboard.template.master')

@section('dashboard')

<div id="kelas-page" class="container-xxl flex-grow-1 container-p-y px-4">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Basis Data / Referensi / Kelas {{ $jadwal->kelas->jenjang->jenjang . ' ' . $jadwal->kelas->name }} /</span> Ubah Jadwal</h4>
    <div id="toast-container" class="toast-top-right">
    </div>
    <div class="row">
        <div class="col-xl-12">
            <form method="post" class="card card-teal" id="change-form">
                @csrf
                <div class="card-header">
                  <h3 class="card-title">Ubah Jadwal KBM Kelas {{ $jadwal->kelas->jenjang->jenjang . ' ' . $jadwal->kelas->name }}</h3>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 px-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="d-none" name="id_jadwal" value="{{ $jadwal->id }}">
                                    <label for="inputName" class="text-secondary font-weight-normal">Nama Jadwal <span style="font-size: 20px" class="text-danger">*</span></label>
                                    <input type="text" id="inputName" name="nama_jadwal" value="{{ $jadwal->nama_jadwal }}" class="form-control" autocomplete="off" placeholder="Contoh : Jadwal Normal XII RPL 1 2023/2024">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-top: 6px">
                                    <label for="inputStatus" class="text-secondary font-weight-normal">Status Jadwal</label>
                                    <select class="form-control select2bs4 select2-hidden-accessible" value="{{ $jadwal->status }}" name="status" id="inputStatus" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="inputDesc" class="text-secondary font-weight-normal">Deskripsi Jadwal</label>
                                <textarea id="inputDesc" name="deskripsi_jadwal" class="form-control" placeholder="Masukkan Sedikit Deskripsi Tentang Jadwal..." style="height: 86px;">{{ $jadwal->deskripsi_jadwal }}</textarea>
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
                                    @foreach ($jadwal->details as $detail)
                                        @if ($detail->hari == 'senin')
                                        <input type="text" class="d-none" name="id_on_senin[]" value="{{ $detail->id }}">
                                        <input type="text" class="d-none total-senin" value="{{ $detail->jam_ke }}">
                                        <div class="mb-3" id="senin-container-{{ $detail->jam_ke }}">
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
                                                        @if (isset($guru[0]))
                                        <input type="text" id="val-guru-senin-{{ $detail->jam_ke }}" class="d-none" value="{{ $detail->guru_id }}">
                                                            <select id="guru-senin-{{ $detail->jam_ke }}" class="form-control select2bs4 select2-hidden-accessible" name="guru_senin[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                        <input type="text" id="val-mapel-senin-{{ $detail->jam_ke }}" class="d-none" value="{{ $detail->mapel_id }}">
                                                            <select id="mapel-senin-{{ $detail->jam_ke }}" class="form-control select2bs4 select2-hidden-accessible" name="mapel_senin[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                                        <input type="text" name="start_senin[]" value="{{ $detail->jam_mulai }}" class="form-control as-time" placeholder="Pilih Jam">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" name="end_senin[]" value="{{ $detail->jam_selesai }}" class="form-control as-time" placeholder="Pilih Jam">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="tab-pane fade" id="selasa" role="tabpanel" aria-labelledby="selasa-tab">
                                    @foreach ($jadwal->details as $detail)
                                        @if ($detail->hari == 'selasa')
                                        <input type="text" class="d-none" name="id_on_selasa[]" value="{{ $detail->id }}">
                                        <input type="text" class="d-none total-selasa" value="{{ $detail->jam_ke }}">
                                        <div class="mb-3" id="selasa-container-{{ $detail->jam_ke }}">
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
                                                        @if (isset($guru[0]))
                                                        <input type="text" id="val-guru-selasa-{{ $detail->jam_ke }}" class="d-none" value="{{ $detail->guru_id }}">
                                                            <select id="guru-selasa-{{ $detail->jam_ke }}" class="form-control select2bs4 select2-hidden-accessible" name="guru_selasa[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                        <input type="text" id="val-mapel-selasa-{{ $detail->jam_ke }}" class="d-none" value="{{ $detail->mapel_id }}">
                                                            <select id="mapel-selasa-{{ $detail->jam_ke }}" class="form-control select2bs4 select2-hidden-accessible" name="mapel_selasa[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                                        <input type="text" name="start_selasa[]" value="{{ $detail->jam_mulai }}" class="form-control as-time" placeholder="Pilih Jam">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" name="end_selasa[]" value="{{ $detail->jam_selesai }}" class="form-control as-time" placeholder="Pilih Jam">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="tab-pane fade" id="rabu" role="tabpanel" aria-labelledby="rabu-tab">
                                    @foreach ($jadwal->details as $detail)
                                        @if ($detail->hari == 'rabu')
                                        <input type="text" class="d-none" name="id_on_rabu[]" value="{{ $detail->id }}">
                                        <input type="text" class="d-none total-rabu" value="{{ $detail->jam_ke }}">
                                        <div class="mb-3" id="rabu-container-{{ $detail->jam_ke }}">
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
                                                        @if (isset($guru[0]))
                                                            <input type="text" id="val-guru-rabu-{{ $detail->jam_ke }}" class="d-none" value="{{ $detail->guru_id }}">
                                                            <select id="guru-rabu-{{ $detail->jam_ke }}" class="form-control select2bs4 select2-hidden-accessible" name="guru_rabu[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                        <input type="text" id="val-mapel-rabu-{{ $detail->jam_ke }}" class="d-none" value="{{ $detail->mapel_id }}">
                                                            <select id="mapel-rabu-{{ $detail->jam_ke }}" class="form-control select2bs4 select2-hidden-accessible" name="mapel_rabu[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                                        <input type="text" name="start_rabu[]" value="{{ $detail->jam_mulai }}" class="form-control as-time" placeholder="Pilih Jam">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" name="end_rabu[]" value="{{ $detail->jam_selesai }}" class="form-control as-time" placeholder="Pilih Jam">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="tab-pane fade" id="kamis" role="tabpanel" aria-labelledby="kamis-tab">
                                    @foreach ($jadwal->details as $detail)
                                        @if ($detail->hari == 'kamis')
                                        <input type="text" class="d-none" name="id_on_kamis[]" value="{{ $detail->id }}">
                                        <input type="text" class="d-none total-kamis" value="{{ $detail->jam_ke }}">
                                        <div class="mb-3" id="kamis-container-{{ $detail->jam_ke }}">
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
                                                        @if (isset($guru[0]))
                                                        <input type="text" id="val-guru-kamis-{{ $detail->jam_ke }}" class="d-none" value="{{ $detail->guru_id }}">
                                                            <select id="guru-kamis-{{ $detail->jam_ke }}" class="form-control select2bs4 select2-hidden-accessible" name="guru_kamis[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                        <input type="text" id="val-mapel-kamis-{{ $detail->jam_ke }}" class="d-none" value="{{ $detail->mapel_id }}">
                                                            <select id="mapel-kamis-{{ $detail->jam_ke }}" class="form-control select2bs4 select2-hidden-accessible" name="mapel_kamis[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                                        <input type="text" name="start_kamis[]" value="{{ $detail->jam_mulai }}" class="form-control as-time" placeholder="Pilih Jam">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" name="end_kamis[]" value="{{ $detail->jam_selesai }}" class="form-control as-time" placeholder="Pilih Jam">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="tab-pane fade" id="jumat" role="tabpanel" aria-labelledby="jumat-tab">
                                    @foreach ($jadwal->details as $detail)
                                        @if ($detail->hari == 'jumat')
                                        <input type="text" class="d-none" name="id_on_jumat[]" value="{{ $detail->id }}">
                                        <input type="text" class="d-none total-jumat" value="{{ $detail->jam_ke }}">
                                        <div class="mb-3" id="jumat-container-{{ $detail->jam_ke }}">
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
                                                        @if (isset($guru[0]))
                                                        <input type="text" id="val-guru-jumat-{{ $detail->jam_ke }}" class="d-none" value="{{ $detail->guru_id }}">
                                                            <select id="guru-jumat-{{ $detail->jam_ke }}" class="form-control select2bs4 select2-hidden-accessible" name="guru_jumat[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                        <input type="text" id="val-mapel-jumat-{{ $detail->jam_ke }}" class="d-none" value="{{ $detail->mapel_id }}">
                                                            <select id="mapel-jumat-{{ $detail->jam_ke }}" class="form-control select2bs4 select2-hidden-accessible" name="mapel_jumat[]" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                                                        <input type="text" name="start_jumat[]" value="{{ $detail->jam_mulai }}" class="form-control as-time" placeholder="Pilih Jam">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                                                        <input type="text" name="end_jumat[]" value="{{ $detail->jam_selesai }}" class="form-control as-time" placeholder="Pilih Jam">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
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

@include('dashboard.jadwal.data-edit')
    
@endsection