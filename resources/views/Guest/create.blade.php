@extends('layouts.app')

@section('title', 'Tambah Tamu')

@section('contents')
<div class="container">
    <h1>Tambah Tamu</h1>
    <form id="guestForm" method="POST" action="{{ route('guest.store') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="form-group">
            <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" 
                   placeholder="Nama Pengunjung" value="{{ old('nama') }}" required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input type="text" class="form-control @error('instansi') is-invalid @enderror" name="instansi" 
                   placeholder="Instansi Pengunjung" value="{{ old('instansi') }}" required>
            @error('instansi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input type="text" class="form-control @error('alamat') is-invalid @enderror" name="alamat" 
                   placeholder="Alamat Pengunjung" value="{{ old('alamat') }}" required>
            @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input type="text" class="form-control @error('tujuan') is-invalid @enderror" name="tujuan" 
                   placeholder="Tujuan Pengunjung" value="{{ old('tujuan') }}" required>
            @error('tujuan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input type="text" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp" 
                   placeholder="No.HP Pengunjung" value="{{ old('no_hp') }}" required>
            @error('no_hp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Input hidden untuk foto -->
        <input type="hidden" name="foto" id="foto_input" value="{{ old('foto') }}" required>
        @error('foto')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-group">
            <label for="foto">Ambil Foto Pengunjung:</label>
            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#cameraModal">
                Ambil Foto
            </button>
        </div>

        <button type="submit" name="bsimpan" class="btn btn-primary btn-block">Simpan</button>
    </form>
</div>

<!-- Modal untuk ambil foto -->
<div class="modal fade" id="cameraModal" tabindex="-1" role="dialog" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cameraModalLabel">Ambil Foto Pengunjung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <video id="video" width="100%" height="auto" autoplay></video>
                <canvas id="canvas" style="display: none;"></canvas>
                <img id="foto" style="display: none;" src="{{ old('foto') }}" alt="Foto Pengunjung" width="100%">

                <button type="button" class="btn btn-success btn-block" id="capture">Ambil Foto</button>
                <button type="button" class="btn btn-warning btn-block" id="retake" style="display: none;">Pencet Ulang</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Menampilkan video
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const foto = document.getElementById('foto');
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

    // Ambil foto
    captureButton.addEventListener('click', function () {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataUrl = canvas.toDataURL('image/png');
        foto.src = dataUrl;
        foto.style.display = 'block';
        captureButton.style.display = 'none';
        retakeButton.style.display = 'block';
        fotoInput.value = dataUrl; // Simpan foto ke input tersembunyi
    });

    // Pencet ulang
    retakeButton.addEventListener('click', function () {
        foto.style.display = 'none';
        captureButton.style.display = 'block';
        retakeButton.style.display = 'none';
        fotoInput.value = ''; // Clear the photo input
    });

    // Menampilkan foto sebelumnya jika ada
    document.addEventListener('DOMContentLoaded', function () {
        if (fotoInput.value) {
            foto.src = fotoInput.value;
            foto.style.display = 'block';
            captureButton.style.display = 'none';
            retakeButton.style.display = 'block';
        }
    });

    // SweetAlert Notification
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000
    });
    @endif
</script>
@endsection

@section('footer')
<footer class="text-center mt-4">
    <p>&copy; 2024. Guestbook App by YourName.</p>
</footer>
@endsection
