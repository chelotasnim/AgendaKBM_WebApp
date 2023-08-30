@extends('dashboard.template.master')

@section('dashboard')

<div id="guru-page" class="container-xxl flex-grow-1 container-p-y px-4">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Basis Data / Referensi /</span> Guru Mapel</h4>
    <div id="toast-container" class="toast-top-right">
    </div>
    <div class="row">
      <div class="col-xl">
        <form method="post" class="card card-teal collapsed-card" id="add-form">
          @csrf
          <div class="card-header">
            <h3 class="card-title">Tambah Guru Mapel</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
          <div class="card-body" style="display: none">
            <div id="guru-mapel-1" class="row">
              <div class="col-md-6">
                <div class="form-group">
                    <label for="input-guru-1" class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                      @if (isset($guru[0]))
                        <select class="form-control select2bs4 select2-hidden-accessible" autocomplete="off" name="guru_id[]" id="input-guru-1" style="width: 100%;" tabindex="-1" aria-hidden="true">
                          <option selected hidden disabled>Pilih Guru Pengajar</option>
                          @foreach ($guru as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                          @endforeach
                        </select>
                      @else
                        <input type="text" class="form-control" placeholder="Belum Ada Data Guru" disabled>
                        <small><a href="{{ url('dashboard/guru') }}" class="text-primary">Belum Ada Guru, Tambahkan Disini.</a></small>
                      @endif
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <label for="input-mapel-1" class="text-secondary font-weight-normal">Mapel Yang Diajar <span style="font-size: 20px" class="text-danger">*</span></label>
                      @if (isset($mapel[0]))
                        <select class="form-control select2bs4 select2-hidden-accessible" autocomplete="off" name="mapel_id[]" id="input-mapel-1" style="width: 100%;" tabindex="-1" aria-hidden="true">
                          <option selected hidden disabled>Pilih Mata Pelajaran</option>
                          @foreach ($mapel as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_mapel }}</option>
                          @endforeach
                        </select>
                      @else
                        <input type="text" class="form-control" placeholder="Belum Ada Data Guru" disabled>
                        <small><a href="{{ url('dashboard/mapel') }}" class="text-primary">Belum Ada Mapel, Tambahkan Disini.</a></small>
                      @endif
                </div>
              </div>
            </div>
            <div class="row mb-5">
                <div class="col-lg-12 d-flex align-stretch justify-content-end" style="gap: 5px">
                    <button id="clone-btn" type="button" class="clone-btn btn btn-sm bg-teal d-flex align-items-center" style="padding-top: 8px; padding-bottom: 8px">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button id="remove-btn" type="button" style="display: none" class="remove-btn btn btn-sm bg-danger align-items-center">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="row mt-4 justify-content-end" style="gap: 8px; padding-right: 14px;">
              <button type="submit" class="col-sm-3 btn bg-teal">Tambahkan</button>
            </div>
          </div>
        </form>
    </div>
    </div>
    <div class="row">
        <div class="col-xl">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Daftar Guru dan Mapel Yang Diajar</h3>
            </div>
            <div class="card-body">
              <table id="jam-table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">Kode Guru</th>
                    <th>Nama Guru</th>
                    <th class="text-center">Kode Mapel</th>
                    <th>Mata Pelajaran</th>
                    <th class="text-center">
                      <i class="fas fa-ellipsis-v"></i>
                    </th>
                  </tr>
                </thead>
                <tbody id="jam-tbody">
                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@include('dashboard.guru_mapel.data')

@endsection