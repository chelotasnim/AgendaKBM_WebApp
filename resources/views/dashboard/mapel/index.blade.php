@extends('dashboard.template.master')

@section('dashboard')

<div id="mapel-page" class="container-xxl flex-grow-1 container-p-y px-4">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Basis Data / Referensi /</span> Mata Pelajaran</h4>
    <div id="toast-container" class="toast-top-right">
    </div>
    <div class="row">
        <div class="col-xl-5">
            <form method="post" class="card card-teal" id="add-form">
              @csrf
              <div class="card-header">
                <h3 class="card-title">Tambah Mata Pelajaran</h3>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="inputName" class="text-secondary font-weight-normal">Nama Mata Pelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="text" id="inputName" name="nama_mapel" class="form-control" autocomplete="off" placeholder="Contoh : Matematika">
                </div>
                <div class="form-group">
                  <label for="inputStatus" class="text-secondary font-weight-normal">Status Mata Pelajaran</label>
                  <select class="form-control select2bs4 select2-hidden-accessible" name="status" id="inputStatus" style="width: 100%;" tabindex="-1" aria-hidden="true">
                    <option selected hidden disabled>Pilih Status Mata Pelajaran</option>
                      <option value="1" selected>Aktif</option>
                      <option value="0">Tidak Aktif</option>
                  </select>
                </div>
                <div class="row mt-4 justify-content-end" style="gap: 8px; padding-right: 14px;">
                  <button type="submit" class="col-sm-3 btn bg-teal">Tambah</button>
                  <button type="button" class="col-sm-3 btn border-success border-2 text-teal" data-toggle="modal" data-target="#modal-default">Impor Data</button>
                </div>
              </div>
            </form>
            <div class="modal fade" id="modal-default" style="display: none; padding-right: 17px;" aria-hidden="true" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form id="import-mapel-form" enctype="multipart/form-data" method="post">
                      @csrf
                      <div class="modal-header">
                          <h4 class="modal-title">Impor Data Mata Pelajaran</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                            <div class="custom-file">
                                <input type="file" name="mapel_excel" class="custom-file-input" accept=".xls, .xlsx" id="mapelExcel">
                                <label class="custom-file-label" for="mapelExcel">Impor Template Excel</label>
                              </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <div class="d-flex justify-content-start">
                            <a href="{{ asset('templates/ImporMapel.xlsx') }}" class="btn text-teal" download>Unduh Template Excel</a>
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
        <div class="col-xl-7">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title mt-2">Daftar Mata Pelajaran</h3>
              <div class="card-tools">
                <button id="export-btn" class="btn bg-teal">Ekspor Data</button>
              </div>
            </div>
            <div class="card-body">
              <table id="mapel-table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th>Nama Mata Pelajaran</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">
                      <i class="fas fa-ellipsis-v"></i>
                    </th>
                  </tr>
                </thead>
                <tbody id="mapel-tbody">

                </tbody>
              </table>
            </div>
          </div>
          <div class="modal fade" id="modal-delete" style="display: none; padding-right: 17px;" aria-hidden="true" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <form id="delete-form" method="post">
                  @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Hapus Mata Pelajaran</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group">
                            <label for="must-be-param" class="mb-0 font-weight-normal">Konfrimasi Nama Mata Pelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                            <br>
                            <small class="text-info">Mata Pelajaran : <span id="param-delete"></span></small>
                            <input type="text" name="confirm" class="form-control" autocomplete="off" placeholder="Ketik Ulang Nama Mata Pelajaran">
                            <input type="text" name="param" id="must-be-param" class="d-none form-control" readonly>
                            <small class="text-danger d-flex align-items-center mt-2" style="gap: 5px">
                              <i class="fa-solid fa-triangle-exclamation"></i>
                              Mapel akan hilang dari jadwal jika dihapus, selalu perbarui jadwal!
                            </small>
                          </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                          <button type="button" class="btn border-secondary border-2 text-secondary" data-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-danger">Hapus</button>
                      </div>
                </form>
              </div>
            </div>
          </div>
          <div class="modal fade" id="modal-edit" style="display: none; padding-right: 17px;" aria-hidden="true" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <form id="edit-form" method="post">
                  @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Ubah Mata Pelajaran</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group">
                            <div class="form-group">
                              <label for="must-be-param-1" class="text-secondary font-weight-normal">Nama Mata Pelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                              <input type="text" id="must-be-param-1" name="nama_mapel" class="form-control" autocomplete="off" placeholder="Contoh : Matematika">
                            </div>
                            <div class="form-group">
                              <label for="must-be-param-2" class="text-secondary font-weight-normal">Status Mata Pelajaran</label>
                              <select class="form-control select2bs4 select2-hidden-accessible" name="status" id="must-be-param-2" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option selected hidden disabled>Pilih Status Mata Pelajaran</option>
                                  <option value="1" selected>Aktif</option>
                                  <option value="0">Tidak Aktif</option>
                              </select>
                            </div>
                            <input type="text" id="main-edit-param" name="confirm" class="d-none">
                            <small class="text-danger d-flex align-items-center mt-2" style="gap: 5px">
                              <i class="fa-solid fa-circle-info"></i>
                              Mapel akan hilang dari jadwal jika tidak aktif, selalu perbarui jadwal!
                            </small>
                          </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                          <button type="button" class="btn border-secondary border-2 text-secondary" data-dismiss="modal">Batal</button>
                          <button type="submit" class="btn bg-teal">Ubah</button>
                      </div>
                </form>
              </div>
            </div>
        </div>
        </div>
    </div>
</div>

@include('dashboard.mapel.data')

@endsection