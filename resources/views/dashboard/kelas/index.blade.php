@extends('dashboard.template.master')

@section('dashboard')

<div id="kelas-page" class="container-xxl flex-grow-1 container-p-y px-4">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Basis Data / Referensi /</span> Kelas</h4>
    <div id="toast-container" class="toast-top-right">
    </div>
    <div class="row">
        <div class="col-xl-5">
            <form method="post" class="card card-teal" id="add-form">
              @csrf
              <div class="card-header">
                <h3 class="card-title">Tambah Kelas</h3>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="inputJenjang" class="text-secondary font-weight-normal">Jenjang Kelas <span style="font-size: 20px" class="text-danger">*</span></label>
                  @if (isset($jenjang[0]))
                    <select class="form-control select2bs4 select2-hidden-accessible" name="jenjang_kelas_id" id="inputJenjang" style="width: 100%;" tabindex="-1" aria-hidden="true">
                      <option selected hidden disabled>Pilih Jenjang Kelas</option>
                      @foreach ($jenjang as $item)
                        <option value="{{ $item->id }}">{{ $item->jenjang }}</option>
                      @endforeach
                    </select>
                  @else
                  <input type="text" class="form-control" placeholder="Belum Ada Data Jenjang Kelas" disabled>
                    <small><a href="{{ url('dashboard/jenjang_kelas') }}" class="text-primary">Belum Ada Jenjang Kelas, Tambahkan Disini.</a></small>
                  @endif
                </div>
                <div class="form-group">
                  <label for="inputName" class="text-secondary font-weight-normal">Nama Kelas (Tanpa Jenjang) <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="text" id="inputName" name="name" class="form-control" autocomplete="off" placeholder="Contoh : RPL 1">
                </div>
                <div class="form-group">
                  <label for="inputStatus" class="text-secondary font-weight-normal">Status Kelas</label>
                  <select class="form-control select2bs4 select2-hidden-accessible" name="status" id="inputStatus" style="width: 100%;" tabindex="-1" aria-hidden="true">
                    <option selected hidden disabled>Pilih Status Kelas</option>
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
                  <form id="import-kelas-form" enctype="multipart/form-data" method="post">
                    @csrf
                      <div class="modal-header">
                          <h4 class="modal-title">Impor Data Kelas</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                            <div class="custom-file">
                                <input type="file" name="kelas_excel" class="custom-file-input" accept=".xls, .xlsx" id="kelasExcel">
                                <label class="custom-file-label" for="kelasExcel">Impor Template Xlsx</label>
                              </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <div class="d-flex justify-content-start">
                            <a href="{{ asset('templates/ImporKelas.xlsx') }}" class="btn text-teal" download>Unduh Template Xlsx</a>
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
        <div class="col-xl-7">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title mt-2">Daftar Kelas</h3>
              <div class="card-tools">
                <button class="btn bg-danger" data-toggle="modal" data-target="#modal-naik-kelas">Naikan Jenjang Kelas</button>
                <button id="export-btn" class="btn bg-teal">Ekspor Data</button>
              </div>
            </div>
            <div class="card-body">
              <table id="kelas-table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th>Nama Kelas</th>
                    <th class="d-none">J</th>
                    <th class="d-none">K</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">
                      <i class="fas fa-ellipsis-v"></i>
                    </th>
                  </tr>
                </thead>
                <tbody id="kelas-tbody">

                </tbody>
              </table>
            </div>
          </div>
          <div class="modal fade" id="modal-naik-kelas" style="display: none; padding-right: 17px;" aria-hidden="true" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <form id="naik-kelas-form" method="post">
                  @csrf
                  <div class="modal-header">
                    <h4 class="modal-title">Naikkan Jenjang Kelas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <label class="font-weight-normal text-danger pl-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        PERHATIAN
                      </label>
                      <ol>
                        <li>Setiap kelas akan naik 1 jenjang</li>
                        <li>Kelas tidak dapat lagi turun jenjang</li>
                        <li>Semua Siswa kelas akan ikut naik jenjang</li>
                        <li>Siswa yang tidak naik kelas bisa diatur ulang kelasnya di data Akun Siswa</li>
                        <li>Yang akan terjadi pada jenjang terakhir (XII)</li>
                          <ul>
                            <li>Akan masuk di jenjang alumni</li>
                            <li>Akun Siswa pada kelas akan nonaktif</li>
                            <li>Jenjang tidak lagi dapat di aktifkan</li>
                            <li>Semua jadwal kelas akan nonaktif</li>
                          </ul>
                        </li>
                      </ol>
                  </div>
                  <div class="modal-footer justify-content-between">
                      <button type="button" class="btn border-secondary border-2 text-secondary" data-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-danger">Naik Kelas</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="modal fade" id="modal-delete" style="display: none; padding-right: 17px;" aria-hidden="true" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <form id="delete-form" method="post">
                  @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Hapus Kelas</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group">
                            <label for="must-be-param" class="mb-0 font-weight-normal">Konfrimasi Penghapusan Kelas <span style="font-size: 20px" class="text-danger">*</span></label>
                            <br>
                            <small class="text-info">Kode Kelas : <span id="param-delete" class="badge bg-info"></span></small>
                            <input type="number" name="confirm" class="form-control" autocomplete="off" placeholder="Ketik Ulang Kode Kelas">
                            <input type="text" name="param" id="must-be-param" class="d-none form-control" readonly>
                            <small class="text-danger d-flex align-items-center mt-2" style="gap: 5px">
                              <i class="fas fa-exclamation-triangle"></i>
                              Semua Jadwal pada kelas ini akan ikut terhapus
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
                        <h4 class="modal-title">Ubah Kelas</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group">
                            <div class="form-group">
                              <label for="must-be-param-1" class="text-secondary font-weight-normal">Jenjang Kelas <span style="font-size: 20px" class="text-danger">*</span></label>
                              @if (isset($jenjang[0]))
                                <select class="form-control select2bs4 select2-hidden-accessible" name="jenjang_kelas_id" id="must-be-param-1" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                  <option selected hidden disabled>Lihat Jenjang Kelas</option>
                                  @foreach ($jenjang as $item)
                                    <option value="{{ $item->id }}">{{ $item->jenjang }}</option>
                                  @endforeach
                                </select>
                              @else
                              <input type="text" class="form-control" placeholder="Belum Ada Data Jenjang Kelas" disabled>
                                <small><a href="{{ url('dashboard/jenjang_kelas') }}" class="text-primary">Belum Ada Jenjang Kelas, Tambahkan Disini.</a></small>
                              @endif
                            </div>
                            <div class="form-group">
                              <label for="must-be-param-2" class="text-secondary font-weight-normal">Nama Kelas <span style="font-size: 20px" class="text-danger">*</span></label>
                              <input type="text" id="must-be-param-2" name="name" class="form-control" autocomplete="off" placeholder="Contoh : Matematika">
                            </div>
                            <div class="form-group">
                              <label for="must-be-param-3" class="text-secondary font-weight-normal">Status Kelas</label>
                              <select class="form-control select2bs4 select2-hidden-accessible" name="status" id="must-be-param-3" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option hidden disabled>Pilih Status Kelas</option>
                                  <option value="1">Aktif</option>
                                  <option value="0">Tidak Aktif</option>
                              </select>
                            </div>
                            <input type="text" id="main-edit-param" name="confirm" class="d-none">
                            <input type="text" id="second-edit-param" name="confirm2" class="d-none">
                            <small class="text-danger d-flex align-items-center mt-2" style="gap: 5px">
                              <i class="fas fa-info-circle"></i>
                              Jadwal terkait tidak akan ditampilkan jika status kelas tidak aktif
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

@include('dashboard.kelas.data')

@endsection