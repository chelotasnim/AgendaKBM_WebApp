@extends('dashboard.template.master')

@section('dashboard')

<div id="siswa-page" class="container-xxl flex-grow-1 container-p-y px-4">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Basis Data / Transaksional /</span> Jurnal Mengajar</h4>
    <div id="toast-container" class="toast-top-right">
    </div>
    <div class="row">
        <div class="col-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tampilkan Jurnal Berdasarkan</h3>
                </div>
                <div class="card-body">
                    <form method="post" id="search-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="text-secondary font-weight-normal">Rentang Tanggal <span style="font-size: 20px" class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="far fa-clock"></i></span>
                                        </div>
                                        <input type="text" class="form-control float-right" name="dari_sampai" id="reservation">
                                      </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="text-secondary font-weight-normal">Kelas <span style="font-size: 20px" class="text-danger">*</span></label>
                                    @if (isset($kelas[0]))
                                        <select class="form-control select2bs4 select2-hidden-accessible" autocomplete="off" name="kelas" id="inputKelas" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <option selected hidden disabled>Pilih Kelas</option>
                                        @foreach ($kelas as $item)
                                            <option value="{{ $item->jenjang->jenjang . ' ' . $item->name }}">{{ $item->jenjang->jenjang . ' ' . $item->name }}</option>
                                        @endforeach
                                        </select>
                                    @else
                                    <input type="text" class="form-control" placeholder="Belum Ada Kelas" disabled>
                                        <small><a href="{{ url('dashboard/kelas') }}" class="text-primary">Belum Ada Kelas, Tambahkan Disini.</a></small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn bg-teal" style="margin-top: 32px">Tampilkan</button>
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

@include('dashboard.jurnal.data')

@endsection