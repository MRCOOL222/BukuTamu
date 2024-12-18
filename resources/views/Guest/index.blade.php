@extends('layouts.app')

@section('title', 'Data Tamu')

@section('contents')
    <div class="container">
        <h1>List Data Tamu</h1>
        <a href="{{ route('guest.create') }}" class="btn btn-primary">Tambah Tamu</a>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th> <!-- Tambahkan kolom tanggal -->
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Tujuan</th>
                    <th>Instansi</th>
                    <th>No.HP</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($guests as $index => $rs)
                    <tr>
                        <td>{{ ($guests->currentPage() - 1) * $guests->perPage() + $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($rs->tanggal)->format('Y-m-d') }}</td> <!-- Format tanggal -->
                        <td>{{ $rs->nama }}</td>
                        <td>{{ $rs->alamat }}</td>
                        <td>{{ $rs->tujuan }}</td>
                        <td>{{ $rs->instansi }}</td>
                        <td>{{ $rs->no_hp }}</td>
                        <td>
                            <!-- Menampilkan foto dari folder storage/uploads -->
                            <img src="{{ url($rs->foto) }}" alt="{{ $rs->foto }}" width="100" />
                        </td>
                        <td>
                            <a href="{{ route('guest.edit', $rs->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="openDeleteModal('{{ $rs->nama }}', {{ $rs->id }})">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $guests->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Untuk menghapus data tamu, ketikkan <strong id="guest-name"></strong> di kotak di bawah ini:</p>
                    <input type="text" id="confirmation-input" class="form-control" placeholder="Ketikkan nama tamu">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete-btn" disabled>Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan script SweetAlert -->
    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    <script>
        let guestIdToDelete = null;
        
        function openDeleteModal(guestName, guestId) {
            // Tampilkan modal konfirmasi
            document.getElementById('guest-name').innerText = guestName;
            guestIdToDelete = guestId;
            $('#deleteModal').modal('show');
        }

        // Fungsi untuk memeriksa apakah input cocok dengan nama tamu
        document.getElementById('confirmation-input').addEventListener('input', function() {
            const inputValue = this.value;
            const guestName = document.getElementById('guest-name').innerText;

            // Aktifkan tombol hapus jika input cocok dengan nama tamu
            document.getElementById('confirm-delete-btn').disabled = inputValue !== guestName;
        });

        // Fungsi untuk mengonfirmasi dan menghapus tamu
        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            if (guestIdToDelete !== null) {
                // Kirimkan form penghapusan
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('guest.destroy', '') }}/' + guestIdToDelete;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(method);

                document.body.appendChild(form);
                form.submit();
            }
        });
    </script>
@endsection
