@extends('layouts.app')

@section('title', 'Data Tamu')

@section('contents')
    <div class="container">
        <h1>List Data Tamu</h1>
        <a href="{{ route('guest.create') }}" class="btn btn-primary">Tambah Tamu</a>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Tujuan</th>
                    <th>No.HP</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($guests as $index => $rs)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $rs->nama }}</td>
                        <td>{{ $rs->alamat }}</td>
                        <td>{{ $rs->tujuan }}</td>
                        <td>{{ $rs->no_hp }}</td>
                        <td>
                            <!-- Menampilkan foto dari folder storage/uploads -->
                            <img src="{{ url($rs->foto) }}" alt="{{ $rs->foto }}" width="100" />
                            {{-- {{dd(url($rs->foto))}} --}}
                        </td>
                        <td>
                            <a href="{{ route('guest.edit', $rs->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('guest.destroy', $rs->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
