@extends('app')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $page->title }}</h3>
                    <div class="card-tools"></div>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form method="POST" action="{{ route('level.store') }}" class="form-horizontal">
                        @csrf
                        <div class="form-group row">
                            <label class="col-1 control-label col-form-label">Kode</label>
                            <div class="col-11">
                                <input type="text" class="form-control" id="level_kode" name="level_kode" value="{{ old('level_kode') }}" required />
                                @error('level_kode')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-1 control-label col-form-label">Nama</label>
                            <div class="col-11">
                                <input type="text" class="form-control" id="level_nama" name="level_nama" value="{{ old('level_nama') }}" required />
                                @error('level_nama')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-1 control-label col-form-label"></label>
                            <div class="col-11">
                                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                <a class="btn btn-sm btn-default ml-1" href="{{ route('level.index') }}">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css')
@endpush

@push('js')
@endpush
