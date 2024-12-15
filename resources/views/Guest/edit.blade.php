@extends('layouts.app')

@section('title', 'Edit Tamu')

@section('content')
    <div class="container">
        <h1>Edit Tamu</h1>
        <form action="{{ route('guest.update', $guest->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nama">Nama Pengunjung</label>
                <input type="text" name="nama" class="form-control" id="nama" value="{{ $guest->nama }}" required>
            </div>

            <div class="form-group">
                <label for="tujuan">Tujuan Pengunjung</label>
                <input type="text" name="tujuan" class="form-control" id="tujuan" value="{{ $guest->tujuan }}" required>
            </div>

            <div class="form-group">
                <label for="instansi">Instansi Pengunjung</label>
                <input type="text" name="instansi" class="form-control" id="instansi" value="{{ $guest->instansi }}" required>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat Pengunjung</label>
                <input type="text" name="alamat" class="form-control" id="alamat" value="{{ $guest->alamat }}" required>
            </div>

            <div class="form-group">
                <label for="no_hp">No HP Pengunjung</label>
                <input type="text" name="no_hp" class="form-control" id="no_hp" value="{{ $guest->no_hp }}" required>
            </div>

            <div class="form-group">
                <label for="foto">Foto Pengunjung</label>
                <input type="file" name="foto" class="form-control" id="foto" accept="image/*">
                @if ($guest->foto)
                    <img src="{{ $guest->foto }}" alt="Foto Tamu" width="100" class="mt-2">
                @endif
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
@endsection
