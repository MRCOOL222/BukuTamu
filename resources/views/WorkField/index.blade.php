@extends('layouts.app')

@section('contents')
<div class="container">
    <h1>Manajemen Bidang</h1>
    <a href="{{ route('workfield.create') }}" class="btn btn-primary mb-3">Tambah Bidang</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Bidang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($workFields as $workField)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $workField->name }}</td>
                    <td>
                        <a href="{{ route('workfield.edit', $workField) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('workfield.destroy', $workField) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
