@extends('dashboard.template.master')

@section('dashboard')

<div id="guru-page" class="container-xxl flex-grow-1 container-p-y px-4">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Basis Data / Master /</span> Akun Guru</h4>
    <div id="toast-container" class="toast-top-right">
    </div>
    <div class="row">
      <div class="col-xl">
        <form method="post" class="card card-teal collapsed-card" id="add-form">
          @csrf
          <div class="card-header">
            <h3 class="card-title">Tambah Guru</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
          <div class="card-body" style="display: none">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputName" class="text-secondary font-weight-normal">Nama Guru <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="text" id="inputName" name="name" class="form-control" autocomplete="off" placeholder="Contoh : Ahmad John Doe">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputUsername" class="text-secondary font-weight-normal">Username Akun Guru <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="text" id="inputUsername" name="username" class="form-control" autocomplete="off" placeholder="Contoh : mjohndoe123">
              </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputEmail" class="text-secondary font-weight-normal">Email Akun Guru <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="text" id="inputEmail" name="email" class="form-control" autocomplete="off" placeholder="Contoh : john123@gmail.com">
              </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputPass" class="text-secondary font-weight-normal">Password Akun Guru <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="password" id="inputPass" name="password" class="form-control" autocomplete="off" placeholder="Masukkan Password Yang Kuat">
              </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="inputStatus" class="text-secondary font-weight-normal">Status Akun Guru</label>
                  <select class="form-control select2bs4 select2-hidden-accessible" name="status" id="inputStatus" style="width: 100%;" tabindex="-1" aria-hidden="true">
                    <option selected hidden disabled>Pilih Status Akun</option>
                      <option value="1" selected>Aktif</option>
                      <option value="0">Tidak Aktif</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row mt-4 justify-content-end" style="gap: 8px; padding-right: 14px;">
              <button type="submit" class="col-sm-3 btn bg-teal">Daftarkan</button>
              <button type="button" class="col-sm-3 btn border-success border-2 text-teal" data-toggle="modal" data-target="#modal-default">Impor Data</button>
            </div>
          </div>
        </form>
        <div class="modal fade" id="modal-default" style="display: none; padding-right: 17px;" aria-hidden="true" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <form id="import-guru-form" enctype="multipart/form-data" method="post">
                @csrf
                  <div class="modal-header">
                      <h4 class="modal-title">Impor Data Akun Guru</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <div class="custom-file">
                            <input type="file" name="guru_excel" class="custom-file-input" accept=".xls, .xlsx" id="guruExcel">
                            <label class="custom-file-label" for="guruExcel">Impor Template Xlsx</label>
                          </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <div class="d-flex justify-content-start">
                        <a href="{{ asset('templates/ImporAkunGuru.xlsx') }}" class="btn text-teal" download>Unduh Template Xlsx</a>
                      </div>
                      <div class="d-flex justify-content-end" style="gap: 5px">
                        <button type="button" class="btn border-success border-2 text-teal" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn bg-teal">Impor</button>
                      </div>
                    </div>
              </form>
            </div>
          </div>
      </div>
    </div>
    </div>
    <div class="row">
        <div class="col-xl">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title mt-2">Daftar Akun Guru</h3>
              <div class="card-tools">
                <button id="export-btn" class="btn bg-teal">Ekspor Data</button>
              </div>
            </div>
            <div class="card-body">
              <table id="guru-table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Kode</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">
                      <i class="fas fa-ellipsis-v"></i>
                    </th>
                  </tr>
                </thead>
                <tbody id="guru-tbody">
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
                        <h4 class="modal-title">Hapus Akun Guru</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group">
                            <label for="must-be-param" class="mb-0 font-weight-normal">Konfirmasi Penghapusan Guru <span style="font-size: 20px;" class="text-danger">*</span></label>
                            <br>
                            <small class="text-info">Username : <span id="param-delete"></span></small>
                            <input type="text" name="confirm" class="form-control" autocomplete="off" placeholder="Ketik Ulang Username Guru">
                            <input type="text" name="param" id="must-be-param" class="d-none form-control" readonly>
                            <small class="text-danger d-flex align-items-center mt-2" style="gap: 5px">
                              <i class="fas fa-exclamation-triangle"></i>
                                Guru akan hilang dari jadwal jika dihapus, selalu perbarui jadwal!
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
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <form id="edit-form" method="post">
                  @csrf
                      <div class="modal-header">
                        <h4 class="modal-title">Ubah Akun Guru</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="must-be-param-1" class="text-secondary font-weight-normal">Nama Guru <span style="font-size: 20px" class="text-danger">*</span></label>
                              <input type="text" id="must-be-param-1" name="name" class="form-control" autocomplete="off" placeholder="Contoh : Ahmad John Doe">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="must-be-param-2" class="text-secondary font-weight-normal">Username Akun Guru <span style="font-size: 20px" class="text-danger">*</span></label>
                              <input type="text" id="must-be-param-2" name="username" class="form-control" autocomplete="off" placeholder="Contoh : mjohndoe123">
                          </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="must-be-param-3" class="text-secondary font-weight-normal">Username Akun Guru <span style="font-size: 20px" class="text-danger">*</span></label>
                              <input type="text" id="must-be-param-3" name="email" class="form-control" autocomplete="off" placeholder="Contoh : john123@gmail.com">
                          </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group" style="margin-top: 6px">
                              <label for="inputPassEdit" class="text-secondary font-weight-normal">Password Baru</label>
                              <input type="password" id="inputPassEdit" name="password" class="form-control" autocomplete="off" placeholder="Masukkan Password Yang Kuat">
                              <small class="text-info">*Kosongkan Jika Tidak Ingin Merubah Password</small>
                          </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="must-be-param-4" class="text-secondary font-weight-normal">Status Akun Guru</label>
                              <select class="form-control select2bs4 select2-hidden-accessible" name="status" id="must-be-param-4" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option hidden disabled>Pilih Status Akun</option>
                                  <option value="1">Aktif</option>
                                  <option value="0">Tidak Aktif</option>
                              </select>
                              <input type="text" id="main-edit-param" name="confirm" class="d-none">
                              <input type="text" id="second-edit-param" name="confirm2" class="d-none">
                              <small class="text-danger d-flex align-items-center mt-2" style="gap: 5px">
                                <i class="fas fa-info-circle"></i>
                                Guru akan hilang dari jadwal jika tidak aktif, selalu perbarui jadwal!
                              </small>
                            </div>
                          </div>
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

@include('dashboard.guru.data')

@endsection