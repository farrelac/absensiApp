<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ env('APP_NAME') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css">
        <style>
            .signature-pad {
                border: 1px solid #000;
                border-radius: 4px;
                width: 100%;
                height: 150px;
            }

            .form-control.mb-2 {
                padding: 0.5rem 0.75rem;
                /* Sesuaikan dengan padding input lainnya */
                font-size: 1rem;
                line-height: 1.5;
                border-radius: 0.25rem;
            }
        </style>
    </head>

    <body>
        <div class="container my-5">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="text-center">{{ env('APP_NAME') }}</h4>
                    <table class="table table-borderless">
                        <tr>
                            <td width="150">Nama Kegiatan</td>
                            <td width="20">:</td>
                            <td>{{ $presence->nama_kegiatan }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Kegiatan</td>
                            <td>:</td>
                            <td>{{ date('d F Y', strtotime($presence->tgl_kegiatan)) }}</td>
                        </tr>
                        <tr>
                            <td>Waktu Mulai</td>
                            <td>:</td>
                            <td>{{ date('H:i', strtotime($presence->tgl_kegiatan)) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Form Absensi</h5>
                        </div>
                        <div class="card-body">
                            <form id="form-absen" action="{{ route('absen.save', $presence->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama">
                                    @error('nama')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="jabatan">Jabatan</label>
                                    <input type="text" class="form-control" id="jabatan" name="jabatan">
                                    @error('jabatan')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="asal_instansi">Asal Instansi</label>
                                    <input type="text" class="form-control" id="asal_instansi" name="asal_instansi">
                                    @error('asal_instansi')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="tanda_tangan">Tanda Tangan</label>
                                    <div class="form-control mb-2">
                                        <canvas id="signature-pad" class="signature-pad"></canvas>
                                    </div>
                                    <textarea name="signature" id="signature64" class="d-none"></textarea>
                                    @error('signature')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <button type="button" id="clear" class="btn btn-sm btn-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                        </svg>
                                        Clear
                                    </button>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Daftar Kehadiran</h5>
                        </div>
                        <div class="card-body">
                            {{ $dataTable->table() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('js/signature.min.js') }}"></script>

        <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>

        <script>
            $(function() {
                // Set signature pad width
                let sig = $('#signature-pad').parent().width();
                $('#signature-pad').attr('width', sig);

                // Set canvas color
                let signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
                    backgroundColor: 'rgb(255, 255, 255, 0)',
                    penColor: 'rgb(0, 0, 0)'
                });

                // Set signature to textarea
                $('canvas').on('mouseup touchend', function() {
                    $('#signature64').val(signaturePad.toDataURL());
                });

                // Clear Signature
                $('#clear').on('click', function(e) {
                    e.preventDefault();
                    signaturePad.clear();
                    $('#signature64').val('');
                });

                $('#form-absen').on('submit', function() {
                    $(this).find('button[type="submit"]').attr('disabled', 'disabled');
                });

                // $('form').on('submit', function() {
                //     if (signaturePad.isEmpty()) {
                //         $('#signature64').val('');
                //     } else {
                //         $('#signature64').val(signaturePad.toDataURL());
                //     }
                // });
            });
        </script>

        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    </body>

</html>
