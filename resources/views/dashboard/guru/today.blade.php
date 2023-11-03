@extends('dashboard.template.master')

@section('dashboard')

<div id="guru-today-page" class="container-xxl flex-grow-1 container-p-y px-4">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Basis Data / Transaksi /</span> Guru Hari Ini</h4>
  <div class="row">
    <div class="col-xl">
        <div class="card">
            <div class="card-header">
              <h3 class="card-title mt-2">Daftar Guru Yang Mengajar Hari Ini</h3>
            </div>
            <div class="card-body">
              <table id="guru-today-table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th>Nama Guru</th>
                  </tr>
                </thead>
                <tbody id="guru-today-tbody">
                </tbody>
              </table>
            </div>
          </div>
    </div>
  </div>
</div>

@include('dashboard.guru.data')

@endsection