@extends('layouts.app')

@section('title', 'Profile')

@section('contents')
<div class="container">
    <h1>Profile</h1>
    <form id="profileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}" required>
            <div class="invalid-feedback">Nama wajib diisi.</div>
        </div>

        <!-- Input hidden untuk foto -->
        <input type="hidden" name="foto" id="foto_input" required>
        <div class="form-group">
            <label for="foto">Foto Profil</label>
            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#cameraModal">
                Ambil/Ubah Foto
            </button>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" value="{{ auth()->user()->username }}" required>
            <div class="invalid-feedback">Username wajib diisi.</div>
        </div>

        <div class="form-group">
            <label for="password">Password Baru (Opsional)</label>
            <input type="password" class="form-control" name="password" placeholder="Masukkan password baru jika ingin mengganti">
        </div>

        <button type="submit" name="bsimpan" class="btn btn-success btn-block">Simpan Perubahan</button>
    </form>
</div>

<!-- Modal untuk ambil foto -->
<div class="modal fade" id="cameraModal" tabindex="-1" role="dialog" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cameraModalLabel">Ambil/Ubah Foto Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <video id="video" width="100%" height="auto" autoplay></video>
                <canvas id="canvas" style="display: none;"></canvas>
                <img id="foto_preview" style="display: none;" src="" alt="Foto Profil" width="100%">

                <button type="button" class="btn btn-success btn-block" id="capture">Ambil Foto</button>
                <button type="button" class="btn btn-warning btn-block" id="retake" style="display: none;">Pencet Ulang</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const fotoPreview = document.getElementById('foto_preview');
    const captureButton = document.getElementById('capture');
    const retakeButton = document.getElementById('retake');
    const fotoInput = document.getElementById('foto_input');

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function (stream) {
            video.srcObject = stream;
        })
        .catch(function (error) {
            console.error("Error accessing webcam:", error);
        });

    captureButton.addEventListener('click', function () {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataUrl = canvas.toDataURL('image/png');
        fotoPreview.src = dataUrl;
        fotoPreview.style.display = 'block';
        captureButton.style.display = 'none';
        retakeButton.style.display = 'block';
        fotoInput.value = dataUrl;
    });

    retakeButton.addEventListener('click', function () {
        fotoPreview.style.display = 'none';
        captureButton.style.display = 'block';
        retakeButton.style.display = 'none';
        fotoInput.value = '';
    });

    @if (session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    @endif
</script>
@endsection
