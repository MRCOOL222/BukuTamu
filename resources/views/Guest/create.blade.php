@extends('layouts.app')

@section('title', 'Tambah Tamu')

@section('contents')
    <div class="container">
        <h1>Tambah Tamu</h1>
        <form method="POST" action="{{ route('guest.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" name="nama" placeholder="Nama Pengunjung" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="instansi" placeholder="Instansi Pengunjung" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="alamat" placeholder="Alamat Pengunjung" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="tujuan" placeholder="Tujuan Pengunjung" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="no_hp" placeholder="No.HP Pengunjung" required>
            </div>

            <!-- Input hidden untuk foto -->
            <input type="hidden" name="foto" id="foto_input" />

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
                    <img id="foto" style="display: none;" src="" alt="Foto Pengunjung" width="100%">

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
    </script>
@endsection
