@extends('dashboard.template.master')

@section('dashboard')

<div id="kelas-page" class="container-xxl flex-grow-1 container-p-y px-4">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Basis Data / Referensi / Kelas {{ $jadwal->kelas->jenjang->jenjang . ' ' . $jadwal->kelas->name }} /</span> Kurangi Jam</h4>
    <div id="toast-container" class="toast-top-right">
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-teal">
                @csrf
                <div class="card-header">
                  <h3 class="card-title">Kurangi Jam KBM Kelas {{ $jadwal->kelas->jenjang->jenjang . ' ' . $jadwal->kelas->name }}</h3>
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
                                        <div class="mb-3" data-schedule-id="{{ $detail->id }}">
                                            <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                                <div class="col-md-12">
                                                    <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">{{ $detail->jam_ke }}</span></label>
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
                                    <div class="row">
                                        <div class="col-lg-12 d-flex align-stretch" style="gap: 5px">
                                            <button type="button" class="d-flex remove-btn btn bg-danger align-items-center" style="gap: 8px">
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
                                        <div class="mb-3" data-schedule-id="{{ $detail->id }}">
                                            <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                                <div class="col-md-12">
                                                    <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">{{ $detail->jam_ke }}</span></label>
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
                                    <div class="row">
                                        <div class="col-lg-12 d-flex align-stretch" style="gap: 5px">
                                            <button type="button" class="d-flex remove-btn btn bg-danger align-items-center" style="gap: 8px">
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
                                        <div class="mb-3" data-schedule-id="{{ $detail->id }}">
                                            <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                                <div class="col-md-12">
                                                    <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">{{ $detail->jam_ke }}</span></label>
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
                                    <div class="row">
                                        <div class="col-lg-12 d-flex align-stretch" style="gap: 5px">
                                            <button type="button" class="d-flex remove-btn btn bg-danger align-items-center" style="gap: 8px">
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
                                        <div class="mb-3" data-schedule-id="{{ $detail->id }}">
                                            <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                                <div class="col-md-12">
                                                    <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">{{ $detail->jam_ke }}</span></label>
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
                                    <div class="row">
                                        <div class="col-lg-12 d-flex align-stretch" style="gap: 5px">
                                            <button type="button" class="d-flex remove-btn btn bg-danger align-items-center" style="gap: 8px">
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
                                        <div class="mb-3" data-schedule-id="{{ $detail->id }}">
                                            <div class="row" style="border-bottom: 1px solid rgb(225, 225, 225)">
                                                <div class="col-md-12">
                                                    <label class="text-secondary font-weight-normal">JAM KE - <span class="time-count">{{ $detail->jam_ke }}</span></label>
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
                                    <div class="row">
                                        <div class="col-lg-12 d-flex align-stretch" style="gap: 5px">
                                            <button type="button" class="d-flex remove-btn btn bg-danger align-items-center" style="gap: 8px">
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
                            <form id="delete-form" method="post" class="d-flex p-3 justify-content-end align-items-stretch" style="gap: 12px">
                                @csrf
                                <a href="{{ url('dashboard/jadwal/' . $jadwal->kelas_id) }}" class="btn text-secondary">Kembali</a>
                                <button type="submit" class="btn bg-danger">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@include('dashboard.jadwal.data-delete')
    
@endsection