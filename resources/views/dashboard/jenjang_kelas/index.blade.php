@extends('dashboard.template.master')

@section('dashboard')

<div id="jenjang-kelas-page" class="container-xxl flex-grow-1 container-p-y px-4">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Basis Data / Referensi /</span> Jenjang Kelas</h4>
    <div id="toast-container" class="toast-top-right">
    </div>
    <div class="row">
        <div class="col-xl-5">
            <form method="post" class="card card-teal" id="add-form">
              @csrf
              <div class="card-header">
                <h3 class="card-title">Tambah Jenjang Kelas</h3>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="inputName" class="text-secondary font-weight-normal">Jenjang Kelas <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="text" id="inputName" name="jenjang" class="form-control" autocomplete="off" placeholder="Contoh : XII">
                </div>
                <div class="form-group">
                  <label for="inputStatus" class="text-secondary font-weight-normal">Status Jenjang Kelas</label>
                  <select class="form-control select2bs4 select2-hidden-accessible" name="status" id="inputStatus" style="width: 100%;" tabindex="-1" aria-hidden="true">
                    <option selected hidden disabled>Pilih Status Jenjang</option>
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
        <div class="col-xl-7">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Daftar Jenjang Kelas</h3>
            </div>
            <div class="card-body">
              <table id="jenjang-kelas-table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Kode</th>
                    <th>Jenjang Kelas</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">
                      <i class="fa-solid fa-ellipsis-vertical"></i>
                    </th>
                  </tr>
                </thead>
                <tbody id="jenjang-kelas-tbody">

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
                        <h4 class="modal-title">Hapus Jenjang Kelas</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group">
                            <label for="must-be-param" class="mb-0 font-weight-normal">Konfrimasi Penghapusan Jenjang Kelas <span style="font-size: 20px" class="text-danger">*</span></label>
                            <br>
                            <small class="text-info">Jenjang Kelas : <span id="param-delete"></span></small>
                            <input type="text" name="confirm" class="form-control" autocomplete="off" placeholder="Ketik Ulang Jenjang Kelas">
                            <input type="text" name="param" id="must-be-param" class="d-none form-control" readonly>
                            <small class="text-danger d-flex align-items-center mt-2" style="gap: 5px">
                              <i class="fa-solid fa-triangle-exclamation"></i>
                              Semua Kelas dan Jadwal pada jenjang kelas ini akan ikut terhapus
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
                        <h4 class="modal-title">Ubah Jenjang Kelas</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group">
                            <div class="form-group">
                              <label for="must-be-param-1" class="text-secondary font-weight-normal">Jenjang Kelas <span style="font-size: 20px" class="text-danger">*</span></label>
                              <input type="text" id="must-be-param-1" name="jenjang" class="form-control" autocomplete="off" placeholder="Contoh : 2022 / 2023">
                            </div>
                            <div class="form-group">
                              <label for="must-be-param-2" class="text-secondary font-weight-normal">Status Jenjang Kelas</label>
                              <select class="form-control select2bs4 select2-hidden-accessible" name="status" id="must-be-param-2" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option selected hidden disabled>Pilih Status Jenjang</option>
                                  <option value="1" selected>Aktif</option>
                                  <option value="0">Tidak Aktif</option>
                              </select>
                            </div>
                            <input type="text" id="main-edit-param" name="confirm" class="d-none">
                            <small class="text-danger d-flex align-items-center mt-2" style="gap: 5px">
                              <i class="fa-solid fa-circle-info"></i>
                              Kelas dan Jadwal yang terkait tidak akan ditampilkan jika status tidak aktif
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

@include('dashboard.jenjang_kelas.data')

@endsection