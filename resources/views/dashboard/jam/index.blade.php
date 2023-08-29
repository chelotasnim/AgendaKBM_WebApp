@extends('dashboard.template.master')

@section('dashboard')

<div id="guru-page" class="container-xxl flex-grow-1 container-p-y px-4">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Basis Data / Referensi /</span> Pengaturan Jam</h4>
    <div id="toast-container" class="toast-top-right">
    </div>
    <div class="row">
      <div class="col-xl">
        <form method="post" class="card card-teal collapsed-card" id="add-form">
          @csrf
          <div class="card-header">
            <h3 class="card-title">Tambah Pengaturan Jam</h3>
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
                    <label for="inputDay" class="text-secondary font-weight-normal">Pengaturan Jam Pada Hari <span style="font-size: 20px" class="text-danger">*</span></label>
                    <select class="form-control select2bs4 select2-hidden-accessible" name="hari" id="inputDay" style="width: 100%;" tabindex="-1" aria-hidden="true">
                      <option selected hidden disabled>Pilih Hari</option>
                        <option value="1">Senin</option>
                        <option value="0">Selasa - Jumat</option>
                    </select>
                  </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputStart" class="text-secondary font-weight-normal">Jam Mulai <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="text" name="mulai" class="form-control as-time" placeholder="Pilih Jam">
              </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputTotal" class="text-secondary font-weight-normal">Jumlah Jam <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="number" name="jumlah" class="form-control" placeholder="--;--">
              </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputDuration" class="text-secondary font-weight-normal">Durasi Per Jam (Menit) <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="number" name="durasi" class="form-control" placeholder="--;--">
              </div>
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
              <h3 class="card-title">Daftar Pengaturan Jam</h3>
            </div>
            <div class="card-body">
              <table id="jam-table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th>Hari</th>
                    <th class="text-center">Jam Mulai</th>
                    <th class="text-center">Jumlah Jam</th>
                    <th class="text-center">Durasi</th>
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
          <div class="modal fade" id="modal-edit" style="display: none; padding-right: 17px;" aria-hidden="true" role="dialog">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <form id="edit-form" method="post">
                  @csrf
                      <div class="modal-header">
                        <h4 class="modal-title">Ubah Pengaturan Jam</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                                <label for="must-be-param-1" class="text-secondary font-weight-normal">Pengaturan Jam Pada Hari <span style="font-size: 20px" class="text-danger">*</span></label>
                                <input type="text" id="must-be-param-1" class="form-control" readonly>
                              </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="must-be-param-2" class="text-secondary font-weight-normal">Jam Mulai <span style="font-size: 20px" class="text-danger">*</span></label>
                              <input id="must-be-param-2" type="text" name="mulai" class="form-control as-time" placeholder="Pilih Jam">
                          </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="must-be-param-3" class="text-secondary font-weight-normal">Jumlah Jam <span style="font-size: 20px" class="text-danger">*</span></label>
                              <input id="must-be-param-3" type="number" name="jumlah" class="form-control" placeholder="--;--">
                          </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="must-be-param-4" class="text-secondary font-weight-normal">Durasi Per Jam (Menit) <span style="font-size: 20px" class="text-danger">*</span></label>
                              <input id="must-be-param-4" type="number" name="durasi" class="form-control" placeholder="--;--">
                          </div>
                          </div>
                        </div>
                        <input type="text" id="main-edit-param" name="confirm" class="d-none" readonly>
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

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@include('dashboard.jam.data')

@endsection