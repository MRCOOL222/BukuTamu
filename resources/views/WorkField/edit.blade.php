@extends('layouts.app')

@section('contents')
<div class="container">
    <h1>Edit Bidang</h1>
    <form action="{{ route('workfield.update', $workField) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nama Bidang</label>
            <input type="text" name="name" class="form-control" value="{{ $workField->name }}" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
