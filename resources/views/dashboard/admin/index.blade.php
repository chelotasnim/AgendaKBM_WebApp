@extends('dashboard.template.master')

@section('dashboard')

<div id="admin-page" class="container-xxl flex-grow-1 container-p-y px-4">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Basis Data / Referensi /</span> Admin</h4>
    <div id="toast-container" class="toast-top-right">
    </div>
    <div class="row">
      <input type="number" class="d-none" id="this-user" value="{{ Auth::user()->id }}">
        <div class="col-xl-4">
            <form method="post" class="card card-teal" id="add-form">
              @csrf
              <div class="card-header">
                <h3 class="card-title">Tambah Admin</h3>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="inputName" class="text-secondary font-weight-normal">Nama Admin <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="text" id="inputName" name="name" class="form-control" autocomplete="off" placeholder="Contoh : Super Admin">
                </div>
                <div class="form-group">
                  <label for="inputEmail" class="text-secondary font-weight-normal">Email Admin <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="text" id="inputEmail" name="email" class="form-control" autocomplete="off" placeholder="Contoh : superadmin@gmail.com">
                </div>
                <div class="form-group">
                  <label for="inputPassword" class="text-secondary font-weight-normal">Password <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="password" id="inputPassword" name="password" class="form-control" autocomplete="off" placeholder="- - -&nbsp;&nbsp;&nbsp;- - -&nbsp;&nbsp;&nbsp;- - -&nbsp;">
                </div>
                <div class="form-group">
                  <label for="inputStatus" class="text-secondary font-weight-normal">Status Admin</label>
                  <select class="form-control select2bs4 select2-hidden-accessible" name="status" id="inputStatus" style="width: 100%;" tabindex="-1" aria-hidden="true">
                    <option selected hidden disabled>Pilih Status Admin</option>
                      <option value="1" selected>Aktif</option>
                      <option value="0">Tidak Aktif</option>
                  </select>
                </div>
                <div class="row mt-4 justify-content-end" style="gap: 8px; padding-right: 14px;">
                  <button type="submit" class="col-sm-3 btn bg-teal">Tambah</button>
                </div>
              </div>
            </form>
        </div>
        <div class="col-xl-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title mt-2">Daftar Admin</h3>
            </div>
            <div class="card-body">
              <table id="admin-table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th>Nama Admin</th>
                    <th>Email</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">
                      <i class="fas fa-ellipsis-v"></i>
                    </th>
                  </tr>
                </thead>
                <tbody id="admin-tbody">

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
                        <h4 class="modal-title">Hapus Admin</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group">
                            <label for="must-be-param" class="mb-0 font-weight-normal">Konfrimasi Email Admin <span style="font-size: 20px" class="text-danger">*</span></label>
                            <br>
                            <small class="text-info">Email : <span id="param-delete"></span></small>
                            <input type="text" name="confirm" class="form-control" autocomplete="off" placeholder="Ketik Ulang Email Admin">
                            <input type="text" name="param" id="must-be-param" class="d-none form-control" readonly>
                            <small class="text-danger d-flex align-items-center mt-2" style="gap: 5px">
                              <i class="fas fa-exclamation-triangle"></i>
                              Akun tidak dapat digunakan secara permanen
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
                        <h4 class="modal-title">Ubah Admin</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group">
                            <div class="form-group">
                              <label for="must-be-param-1" class="text-secondary font-weight-normal">Nama Admin <span style="font-size: 20px" class="text-danger">*</span></label>
                              <input type="text" id="must-be-param-1" name="name" class="form-control" autocomplete="off" placeholder="Contoh : Super Admin">
                            </div>
                            <div class="form-group">
                              <label for="must-be-param-2" class="text-secondary font-weight-normal">Email Admin <span style="font-size: 20px" class="text-danger">*</span></label>
                              <input type="text" id="must-be-param-2" name="email" class="form-control" autocomplete="off" placeholder="Contoh : superadmin@gmail.com">
                            </div>
                            <div class="form-group">
                              <label for="must-be-param-4" class="text-secondary font-weight-normal">Password Baru</label>
                              <input type="password" id="must-be-param-4" name="password" class="form-control" autocomplete="off" placeholder="- - -&nbsp;&nbsp;&nbsp;- - -&nbsp;&nbsp;&nbsp;- - -&nbsp;">
                              <small class="text-info">*Kosongkan Jika Tidak Ingin Merubah Password</small>
                            </div>
                            <div class="form-group">
                              <label for="must-be-param-3" class="text-secondary font-weight-normal">Status Admin</label>
                              <select class="form-control select2bs4 select2-hidden-accessible" name="status" id="must-be-param-3" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option selected hidden disabled>Pilih Status Admin</option>
                                  <option value="1" selected>Aktif</option>
                                  <option value="0">Tidak Aktif</option>
                              </select>
                            </div>
                            <input type="text" id="main-edit-param" name="confirm" class="d-none">
                            <small class="text-danger d-flex align-items-center mt-2" style="gap: 5px">
                              <i class="fas fa-info-circle"></i>
                              Akun tidak dapat digunakan jika status tidak aktif
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

@include('dashboard.admin.data')

@endsection