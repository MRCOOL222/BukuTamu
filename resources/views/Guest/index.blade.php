@extends('layouts.app')

@section('title', 'Data Tamu')

@section('contents')
    <div class="container">
        <h1>Daftar Tamu</h1>
        <a href="{{ route('guest.create') }}" class="btn btn-primary mb-3">Tambah Tamu</a>
        
        <!-- Menampilkan pesan sukses jika ada -->
        @if(Session::has('success'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif
        
        <!-- Cek apakah ada data tamu -->
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tujuan</th>
                        <th>Instansi</th>
                        <th>Alamat</th>
                        <th>No HP</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($guests as $rs)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">{{ $rs->nama }}</td>
                            <td class="align-middle">{{ $rs->tujuan }}</td>
                            <td class="align-middle">{{ $rs->instansi }}</td>
                            <td class="align-middle">{{ $rs->alamat }}</td>
                            <td class="align-middle">{{ $rs->no_hp }}</td>
                            <td>
                                @if ($guest->foto)
                                    <img src="{{ asset('uploads/' . $guest->foto) }}" alt="Foto Tamu" width="100">
                                @else
                                    No Foto
                                @endif
                            </td>
                            <td>
                                <!-- Update routes untuk aksi -->
                                <a href="{{ route('guest.edit', $guest->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('guest.destroy', $guest->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin?')">
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
