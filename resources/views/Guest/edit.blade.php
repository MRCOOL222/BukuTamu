@extends('layouts.app')

@section('title', 'Edit Tamu')

@section('contents')
<div class="container">
    <h1>Edit Tamu</h1>
    <form id="guestForm" method="POST" action="{{ route('guest.update', $guest->id) }}" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')
        <div class="form-group">
            <input type="text" class="form-control" name="nama" value="{{ $guest->nama }}" placeholder="Nama Pengunjung" required>
            <div class="invalid-feedback">Nama Pengunjung wajib diisi.</div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="instansi" value="{{ $guest->instansi }}" placeholder="Instansi Pengunjung" required>
            <div class="invalid-feedback">Instansi Pengunjung wajib diisi.</div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="alamat" value="{{ $guest->alamat }}" placeholder="Alamat Pengunjung" required>
            <div class="invalid-feedback">Alamat Pengunjung wajib diisi.</div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="tujuan" value="{{ $guest->tujuan }}" placeholder="Tujuan Pengunjung" required>
            <div class="invalid-feedback">Tujuan Pengunjung wajib diisi.</div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="no_hp" value="{{ $guest->no_hp }}" placeholder="No.HP Pengunjung" required>
            <div class="invalid-feedback">No.HP Pengunjung wajib diisi.</div>
        </div>

        <!-- Input hidden untuk foto -->
        <input type="hidden" name="foto" id="foto_input" value="{{ $guest->foto }}" required>
        <div class="invalid-feedback" id="fotoError">Foto Pengunjung wajib diisi.</div>

        <!-- Tampilkan foto lama jika ada -->
        @if ($guest->foto)
            <img src="{{ $guest->foto }}" alt="Foto Lama" width="25%" class="mb-2">
        @endif

        <div class="form-group">
            <label for="foto">Ambil Foto Pengunjung:</label>
            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#cameraModal">
                Ambil Foto Ulang
            </button>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Update</button>
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
                <img id="foto" style="display: none;" src="" alt="Foto Pengunjung" width="100%">

                <button type="button" class="btn btn-success btn-block" id="capture">Ambil Foto Ulang</button>
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
    const fotoError = document.getElementById('fotoError');

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
        fotoError.style.display = 'none'; // Hilangkan pesan error
    });

    // Pencet ulang
    retakeButton.addEventListener('click', function () {
        foto.style.display = 'none';
        captureButton.style.display = 'block';
        retakeButton.style.display = 'none';
        fotoInput.value = ''; // Clear the photo input
    });

    // Validasi form
    const form = document.getElementById('guestForm');
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        // Validasi foto
        if (!fotoInput.value) {
            fotoError.style.display = 'block';
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
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
