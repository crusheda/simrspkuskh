@extends('layouts.index')

@section('content')
    <h3>Master Jenis Antrian</h3>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Tambah Jenis Antrian</h5>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-2 mb-4">
                @csrf
                <div class="col-md-4">
                    <input name="nama" class="form-control" placeholder="Nama Jenis" required>
                </div>
                <div class="col-md-3">
                    <input name="kode" class="form-control" placeholder="Kode" required>
                </div>
                <div class="col-md-2">
                    <input name="prefix" class="form-control" placeholder="Prefix" required>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100">Tambah</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Jenis Antrian</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Nama</th><th>Kode</th><th>Prefix</th><th>Status</th><th>Aksi</th>
                </tr>
                @foreach($data as $d)
                    <tr>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->kode }}</td>
                        <td>{{ $d->prefix }}</td>
                        <td>{{ $d->aktif ? 'Aktif' : 'Nonaktif' }}</td>
                        <td>
                            <form method="POST" action="{{ route('api.display.antrian.farmasi.jenis.toggle', $d->id) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-warning">Toggle</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
