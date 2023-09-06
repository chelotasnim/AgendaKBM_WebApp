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
    <div class="modal fade" id="modal-edit" style="display: none; padding-right: 17px;" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form id="edit-form" method="post">
              @csrf
                  <div class="modal-header">
                    <h4 class="modal-title">Ubah Jurnal Mengajar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="must-be-param-1" class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                  @if (isset($guru_mapel[0]))
                    <select class="form-control select2bs4 select2-hidden-accessible" autocomplete="off" name="guru_id" id="must-be-param-1" style="width: 100%;" tabindex="-1" aria-hidden="true">
                      <option selected hidden disabled>Pilih Guru Pengajar</option>
                      @foreach ($guru_mapel as $item)
                        <option value="{{ $item->id }}">{{ $item->mapel->nama_mapel }} | {{ $item->guru->name }}</option>
                      @endforeach
                    </select>
                  @else
                  <input type="text" class="form-control" placeholder="Belum Ada Data Guru" disabled>
                    <small><a href="{{ url('dashboard/guru_mapel') }}" class="text-primary">Belum Ada Guru Pengajar, Tambahkan Disini.</a></small>
                  @endif
                </div>
            </div>
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="must-be-param-2" class="text-secondary font-weight-normal">Kelas <span style="font-size: 20px" class="text-danger">*</span></label>
                      @if (isset($kelas[0]))
                        <select class="form-control select2bs4 select2-hidden-accessible" autocomplete="off" name="kelas" id="must-be-param-2" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="must-be-param-3" class="text-secondary font-weight-normal">Tanggal KBM <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="date" id="must-be-param-3" name="tanggal" class="form-control" autocomplete="off">
                </div>
              </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="must-be-param-4" class="text-secondary font-weight-normal">Jam Mulai <span style="font-size: 20px" class="text-danger">*</span></label>
                        <input id="must-be-param-4" type="text" name="jam_mulai" class="form-control as-time" placeholder="Pilih Jam">
                    </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                      <label for="must-be-param-5" class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                      <input id="must-be-param-5" type="text" name="jam_selesai" class="form-control as-time" placeholder="Pilih Jam">
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      <label for="must-be-param-6" class="text-secondary font-weight-normal">Total Siswa <span style="font-size: 20px" class="text-danger">*</span></label>
                      <input id="must-be-param-6" type="number" name="total_siswa" class="form-control" placeholder="--;--">
                  </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <label for="must-be-param-7" class="text-secondary font-weight-normal">Total Siswa Tidak Hadir <span style="font-size: 20px" class="text-danger">*</span></label>
                    <input id="must-be-param-7" type="number" name="tidak_hadir" class="form-control" placeholder="--;--">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="must-be-param-8" class="text-secondary font-weight-normal">Materi Pembelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                <textarea id="must-be-param-8" name="materi" placeholder="Isi sedikit tentang materi pembelajaran pada jam ini" class="form-control"></textarea>
              </div>
            </div>
          </div>
          <input type="text" class="d-none" id="main-edit-param" name="confirm" readonly>
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

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@include('dashboard.jurnal.data')

@endsection