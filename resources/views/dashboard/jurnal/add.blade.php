@extends('dashboard.template.master')

@section('dashboard')

<div id="siswa-page" class="container-xxl flex-grow-1 container-p-y px-4">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Fitur Pengguna / Guru /</span> Isi Jurnal</h4>
    <div id="toast-container" class="toast-top-right">
    </div>
    <div class="row">
      <div class="col-xl">
        <form method="post" class="card card-teal" id="add-form">
          @csrf
          <div class="card-header">
            <h3 class="card-title">Isi Jurnal Mengajar</h3>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputGuru" class="text-secondary font-weight-normal">Guru Pengajar <span style="font-size: 20px" class="text-danger">*</span></label>
                      @if (isset($guru[0]))
                        <select class="form-control select2bs4 select2-hidden-accessible" autocomplete="off" name="guru_id" id="inputGuru" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                      <label for="inputMapel" class="text-secondary font-weight-normal">Mata Pelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                      @if (isset($mapel[0]))
                        <select class="form-control select2bs4 select2-hidden-accessible" autocomplete="off" name="mapel_id" id="inputMapel" style="width: 100%;" tabindex="-1" aria-hidden="true">
                          <option selected hidden disabled>Pilih Mata Pelajaran</option>
                          @foreach ($mapel as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_mapel }}</option>
                          @endforeach
                        </select>
                      @else
                      <input type="text" class="form-control" placeholder="Belum Ada Data Mapel" disabled>
                        <small><a href="{{ url('dashboard/mapel') }}" class="text-primary">Belum Ada Mapel, Tambahkan Disini.</a></small>
                      @endif
                    </div>
                  </div>
              </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputKelas" class="text-secondary font-weight-normal">Kelas <span style="font-size: 20px" class="text-danger">*</span></label>
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
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputTanggal" class="text-secondary font-weight-normal">Tanggal KBM <span style="font-size: 20px" class="text-danger">*</span></label>
                  <input type="date" id="inputTanggal" name="tanggal" class="form-control" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="text-secondary font-weight-normal">Jam Mulai <span style="font-size: 20px" class="text-danger">*</span></label>
                        <input type="text" name="jam_mulai" class="form-control as-time" placeholder="Pilih Jam">
                    </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <label class="text-secondary font-weight-normal">Jam Selesai <span style="font-size: 20px" class="text-danger">*</span></label>
                      <input type="text" name="jam_selesai" class="form-control as-time" placeholder="Pilih Jam">
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="text-secondary font-weight-normal">Total Siswa <span style="font-size: 20px" class="text-danger">*</span></label>
                      <input type="number" name="total_siswa" class="form-control" placeholder="--;--">
                  </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <label class="text-secondary font-weight-normal">Total Siswa Tidak Hadir <span style="font-size: 20px" class="text-danger">*</span></label>
                    <input type="number" name="tidak_hadir" class="form-control" placeholder="--;--">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="text-secondary font-weight-normal">Materi Pembelajaran <span style="font-size: 20px" class="text-danger">*</span></label>
                <textarea name="materi" placeholder="Isi sedikit tentang materi pembelajaran pada jam ini" class="form-control"></textarea>
              </div>
            </div>
          </div>
            <div class="row mt-4 justify-content-end" style="gap: 8px; padding-right: 14px;">
              <button type="submit" class="col-sm-3 btn bg-teal">Isi Jurnal</button>
            </div>
          </div>
        </form>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@include('dashboard.jurnal.data')

@endsection