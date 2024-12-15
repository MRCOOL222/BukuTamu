@extends('layouts.app')

@section('title', 'Detail Tamu')

@section('content')
    <div class="container">
        <h1>Detail Tamu</h1>
        <table class="table table-bordered">
            <tr>
                <th>Nama</th>
                <td>{{ $guest->nama }}</td>
            </tr>
            <tr>
                <th>Tujuan</th>
                <td>{{ $guest->tujuan }}</td>
            </tr>
            <tr>
                <th>Instansi</th>
                <td>{{ $guest->instansi }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $guest->alamat }}</td>
            </tr>
            <tr>
                <th>No HP</th>
                <td>{{ $guest->no_hp }}</td>
            </tr>
            <tr>
                <th>Foto</th>
                <td><img src="{{ $guest->foto }}" alt="Foto Tamu" width="100"></td>
            </tr>
        </table>
        <a href="{{ route('guest.index') }}" class="btn btn-primary">Kembali</a>
    </div>
@endsection
