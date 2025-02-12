@extends('layouts.app')

@section('title', 'Tambah Tamu')

@section('contents')
<div class="container">
    <h1 class="text-center">Tambah Tamu</h1>
    <form id="guestForm" method="POST" action="{{ route('guest.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" 
                           placeholder="Nama Pengunjung" value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="invalid-feedback">Nama pengunjung wajib diisi.</div>
                    @enderror
                </div>

                <div class="form-group">
                    <select class="form-control @error('instansi') is-invalid @enderror" name="instansi" id="instansi" required>
                        <option value="" selected disabled>Pilih Instansi</option>
                        <option value="Dinas" {{ old('instansi') == 'Dinas' ? 'selected' : '' }}>Dinas</option>
                        <option value="Non Kedinasan" {{ old('instansi') == 'Non Kedinasan' ? 'selected' : '' }}>Non Kedinasan</option>
                    </select>
                    @error('instansi')
                        <div class="invalid-feedback">Instansi pengunjung wajib dipilih.</div>
                    @enderror
                </div>
                
                <div class="form-group" id="nama_instansi_group" style="display: none;">
                    <input type="text" class="form-control @error('nama_instansi') is-invalid @enderror" name="nama_instansi" 
                           placeholder="Nama Instansi Pengunjung" value="{{ old('nama_instansi') }}">
                    @error('nama_instansi')
                        <div class="invalid-feedback">Nama instansi wajib diisi jika memilih instansi tertentu.</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" name="alamat" 
                           placeholder="Alamat Pengunjung" value="{{ old('alamat') }}" required>
                    @error('alamat')
                        <div class="invalid-feedback">Alamat pengunjung wajib diisi.</div>
                    @enderror
                </div>
            </div>
            
            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <div class="form-group">
                    <select class="form-control" name="tujuan_bidang" id="work_field_id" required>
                        <option value="" selected disabled>Pilih Bidang Yang Ingin Dituju</option>
                        @foreach($workFields as $field)
                            <option value="{{ $field->id }}">{{ $field->name }}</option>
                        @endforeach
                    </select>
                    @error('tujuan_bidang')
                        <div class="invalid-feedback">Tujuan pengunjung wajib dipilih.</div>
                    @enderror
                </div>
                
                <div class="form-group" id="tujuan_pengunjung_group" style="display: none;">
                    <input type="text" class="form-control" name="tujuan_pengunjung" placeholder="Tujuan Pengunjung">
                </div>
                
                <div class="form-group">
                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp" 
                           placeholder="No.HP Pengunjung" value="{{ old('no_hp') }}" required>
                    @error('no_hp')
                        <div class="invalid-feedback">Nomor HP pengunjung wajib diisi.</div>
                    @enderror
                </div>

                <div class="form-group">
                    <select class="form-control @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" required>
                        <option value="" disabled selected>Jenis Kelamin Pengunjung</option>
                        <option value="l" {{ old('jenis_kelamin') == 'l' ? 'selected' : '' }}>L</option>
                        <option value="p" {{ old('jenis_kelamin') == 'p' ? 'selected' : '' }}>P</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">Jenis kelamin wajib dipilih.</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Foto -->
        <div class="form-group text-center">
            <label>Ambil Foto Pengunjung:</label>
            <br>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cameraModal">
                Ambil Foto
            </button>
            <input type="hidden" name="foto" id="foto_input" value="{{ old('foto') }}" required>
        </div>

        <button type="submit" name="bsimpan" class="btn btn-primary btn-block">Simpan</button>
    </form>
</div>

<!-- Modal Kamera -->
<div class="modal fade" id="cameraModal" tabindex="-1" role="dialog" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ambil Foto Pengunjung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex">
                <video id="video" width="50%" height="auto" autoplay></video>
                <img id="photoResult" width="50%" height="auto" style="display: none; margin-left: 10px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" id="takePhotoBtn" class="btn btn-primary">Ambil Foto</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let instansiSelect = document.getElementById('instansi');
        let namaInstansiGroup = document.getElementById('nama_instansi_group');
        let namaInstansiInput = document.querySelector('[name="nama_instansi"]');

        function toggleNamaInstansi() {
            // Tampilkan field jika pilih Dinas atau Non Kedinasan
            if (instansiSelect.value === 'Dinas' || instansiSelect.value === 'Non Kedinasan') {
                namaInstansiGroup.style.display = 'block';
                namaInstansiInput.setAttribute('required', 'required');
            } else {
                namaInstansiGroup.style.display = 'none';
                namaInstansiInput.removeAttribute('required');
                namaInstansiInput.value = '';
            }
        }

        instansiSelect.addEventListener('change', toggleNamaInstansi);
        toggleNamaInstansi();

        document.getElementById('work_field_id').addEventListener('change', function() {
            document.getElementById('tujuan_pengunjung_group').style.display = 'block';
        });

        // Mengakses kamera pengguna
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                document.getElementById('video').srcObject = stream;
            })
            .catch(function(error) {
                console.log("Error accessing camera: ", error);
            });

        document.getElementById('takePhotoBtn').addEventListener('click', function() {
            const video = document.getElementById('video');
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/png');
            document.getElementById('foto_input').value = imageData;
            document.getElementById('photoResult').src = imageData;
            document.getElementById('photoResult').style.display = 'block';
        });
    });
</script>
@endsection
