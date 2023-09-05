@extends('dashboard.template.master')

@section('dashboard')

<div id="siswa-page" class="container-xxl flex-grow-1 container-p-y px-4">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Basis Data / Referensi /</span> Jadwal KBM</h4>
    <div id="toast-container" class="toast-top-right">
    </div>
    <div class="row">
        <div class="col-xl">
            <div class="card collapsed-card">
                <div class="card-header">
                    <div class="card-title">Reset dan Backup Jadwal Pada Hari</div>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                          <i class="fas fa-plus"></i>
                        </button>
                      </div>
                </div>
                <div class="card-body">
                    <form method="post" id="reset-jadwal">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="hari" id="inputHariReset" class="form-control select2bs4 select2-hidden-accessible" tabindex="-1" aria-hidden="true" autocomplete="off" style="width: 100%;">
                                        <option value="Semua" selected>Semua</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn bg-danger">Reset dan Backup Jadwal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl">
            <div class="card collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Impor Jadwal Terbaru</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                          <i class="fas fa-plus"></i>
                        </button>
                      </div>
                </div>
                <div class="card-body">
                        <form method="post" id="import-jadwal">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="hari" id="inputHariJadwal" class="form-control select2bs4 select2-hidden-accessible" tabindex="-1" aria-hidden="true" autocomplete="off" style="width: 100%;">
                                            <option selected hidden disabled>Pilih Hari</option>
                                            <option value="Senin">Senin</option>
                                            <option value="Selasa">Selasa</option>
                                            <option value="Rabu">Rabu</option>
                                            <option value="Kamis">Kamis</option>
                                            <option value="Jumat">Jumat</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="custom-file">
                                        <input type="file" name="jadwal_excel" class="custom-file-input" accept=".xls, .xlsx" id="jadwalExcel">
                                        <label class="custom-file-label" for="jadwalExcel">Impor Template Excel</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn bg-teal">Impor</button>
                                    <a href="{{ asset('templates/ImporJam.xlsx') }}" class="btn text-teal" download>Unduh Template Excel</a>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tampilkan Jadwal Berdasarkan</h3>
                </div>
                <div class="card-body">
                    <form method="post" id="search-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label id="inputHari" class="text-secondary font-weight-normal">Jadwal Pada Hari <span style="font-size: 20px" class="text-danger"></span></label>
                                    <select name="hari" id="inputHari" class="form-control select2bs4 select2-hidden-accessible" tabindex="-1" aria-hidden="true" autocomplete="off" style="width: 100%;">
                                        <option value="Semua" selected>Semua</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="text-secondary font-weight-normal">Jadwal Pada Kelas <span style="font-size: 20px" class="text-danger">*</span></label>
                                    @if (isset($kelas[0]))
                                        <select class="form-control select2bs4 select2-hidden-accessible" autocomplete="off" name="kelas" id="inputKelas" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <option selected hidden disabled>Pilih Kelas</option>
                                        @foreach ($kelas as $item)
                                            <option value="{{ $item->id }}">{{ $item->jenjang->jenjang . ' ' . $item->name }}</option>
                                        @endforeach
                                        </select>
                                    @else
                                    <input type="text" class="form-control" placeholder="Belum Ada Kelas" disabled>
                                        <small><a href="{{ url('dashboard/kelas') }}" class="text-primary">Belum Ada Kelas, Tambahkan Disini.</a></small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn bg-teal" style="margin-top: 38px">Tampilkan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div id="data-col" class="col-xl">
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@include('dashboard.jadwal.data')

@endsection