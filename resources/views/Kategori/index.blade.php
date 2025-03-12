@extends('app')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $page->title }}</h3>
                    <div class="card-tools">
                        <a class="btn btn-sm btn-primary mt-1" href="{{ route('kategori.create') }}">Tambah</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="" class="col-1 control-label col-form-label">Filter:</label>
                                <div class="col-3">
                                    <select name="kategori_id" id="kategori_id" class="form-control" required>
                                        <option value="">- Semua -</option>
                                        @foreach ($kategori as $item)
                                            <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Kategori Pengguna</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-hover table-sm" id="table_user">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            var dataUser = $('#table_user').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ route('kategori.list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.kategori_id = $('#kategori_id').val();
                        d._token = "{{ csrf_token() }}";
                    },
                    "error": function(xhr, error, thrown) {
                        console.log("Terjadi kesalahan AJAX:", error);
                        console.log("Respons server:", xhr.responseText);
                        alert("Terjadi kesalahan saat memuat data. Silakan periksa konsol browser untuk detail lebih lanjut.");
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "kategori_kode",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "kategori_nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#kategori_id').on('change', function() {
                dataUser.ajax.reload();
            });
        });
    </script>
@endpush
