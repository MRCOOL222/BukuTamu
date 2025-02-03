@extends('layouts.app')

@section('title', 'Tambah Tamu')

@section('contents')
<div class="container">
    <h1>Tambah Tamu</h1>
    <form id="guestForm" method="POST" action="{{ route('guest.store') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <!-- Nama -->
        <div class="form-group">
            <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" 
                   placeholder="Nama Pengunjung" value="{{ old('nama') }}" required>
            <small id="namaError" style="color: red; display: none;">Nama hanya boleh memakai huruf a-z, coba periksa lagi!</small>
            @error('nama')
                <div class="invalid-feedback">Nama pengunjung wajib diisi.</div>
            @enderror
        </div>

        <!-- Instansi -->
        <div class="form-group">
            <input type="text" class="form-control @error('instansi') is-invalid @enderror" name="instansi" 
                   placeholder="Instansi Pengunjung" value="{{ old('instansi') }}" required>
            @error('instansi')
                <div class="invalid-feedback">Instansi pengunjung wajib diisi.</div>
            @enderror
        </div>

        <!-- Alamat -->
        <div class="form-group">
            <input type="text" class="form-control @error('alamat') is-invalid @enderror" name="alamat" 
                   placeholder="Alamat Pengunjung" value="{{ old('alamat') }}" required>
            @error('alamat')
                <div class="invalid-feedback">Alamat pengunjung wajib diisi.</div>
            @enderror
        </div>

        <!-- Tujuan -->
        <div class="form-group">
            <input type="text" class="form-control @error('tujuan') is-invalid @enderror" name="tujuan" 
                   placeholder="Tujuan Pengunjung" value="{{ old('tujuan') }}" required>
            @error('tujuan')
                <div class="invalid-feedback">Tujuan pengunjung wajib diisi.</div>
            @enderror
        </div>

        <!-- No HP -->
        <div class="form-group">
            <input type="text" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp" 
                   placeholder="No.HP Pengunjung" value="{{ old('no_hp') }}" required>
            @error('no_hp')
                <div class="invalid-feedback">Nomor HP pengunjung wajib diisi.</div>
            @enderror
        </div>

        <!-- Jenis Kelamin (Dropdown) -->
        <div class="form-group">
            <select class="form-control @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" required>
                <option value="" disabled selected>Jenis Kelamin Pengunjung</option>
                <option value="laki-laki" {{ old('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="perempuan" {{ old('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('jenis_kelamin')
                <div class="invalid-feedback">Jenis kelamin wajib dipilih.</div>
            @enderror
        </div>

        <!-- Foto -->
        <input type="hidden" name="foto" id="foto_input" value="{{ old('foto') }}" required>
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
                <div class="row">
                    <div class="col-md-6">
                        <video id="video" width="100%" height="auto" autoplay></video>
                    </div>
                    <div class="col-md-6">
                        <img id="photoResult" src="" alt="Hasil Foto" style="width: 100%; height: auto; display: none;" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" id="takePhotoBtn" class="btn btn-primary">Ambil Foto</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk foto dan validasi -->
<script>
    // Mengakses kamera dan menampilkan video
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(stream) {
            document.getElementById('video').srcObject = stream;
        })
        .catch(function(error) {
            console.log("Error accessing camera: ", error);
        });

    // Mengambil foto dan menyimpannya di input hidden serta menampilkannya
    document.getElementById('takePhotoBtn').addEventListener('click', function() {
        const video = document.getElementById('video');
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        
        // Menangkap gambar dari video
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Mengonversi gambar ke format base64 dan mengisinya ke input hidden
        const fotoData = canvas.toDataURL('image/png');
        document.getElementById('foto_input').value = fotoData;

        // Menampilkan foto yang diambil di sebelah kanan
        const photoResult = document.getElementById('photoResult');
        photoResult.src = fotoData;
        photoResult.style.display = 'block';  // Pastikan gambar tampil
    });
</script>


@endsection
