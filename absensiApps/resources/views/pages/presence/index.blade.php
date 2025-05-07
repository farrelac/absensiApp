@extends('layouts.main')

@section('content')
    <div class="container my-4">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4>
                            Daftar Kegiatan
                        </h4>
                    </div>
                    <div class="col text-end">
                        <a href="{{ route('presence.create') }}" class="btn btn-primary">
                            Tambah Data
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Kegiatan</th>
                            <th>Tanggal Kegiatan</th>
                            <th>Waktu Mulai</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($presences->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data!</td>
                            </tr>
                        @endIF

                        @foreach ($presences as $presence)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $presence->nama_kegiatan }}</td>
                                <td>{{ date('d-m-y', strtotime($presence->tgl_kegiatan)) }}</td>
                                <td>{{ date('H:i', strtotime($presence->tgl_kegiatan)) }}</td>
                                <td>
                                    <a href="{{ route('presence.edit', $presence->id) }}" class="btn btn-warning">
                                        Edit
                                    </a>
                                    <form action="{{ route('presence.destroy', $presence->id) }}" method="post"
                                        class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
