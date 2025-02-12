@extends('layouts.app')

@section('contents')
<div class="container">
    <h1>Tambah Bidang</h1>
    <form action="{{ route('workfield.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nama Bidang</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
