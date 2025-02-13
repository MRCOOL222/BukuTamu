@extends('layouts.app')

@section('title', 'List Data Tamu')

@section('contents')
    <div class="container">

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('guest.create') }}" class="btn btn-primary">Tambah Tamu</a>
        </div>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>JK</th>
                    <th>Tujuan</th>
                    <th>Instansi</th>
                    <th>No.HP</th>
                    <th>Foto</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($guests as $index => $rs)
                    <tr>
                        <td>{{ ($guests->currentPage() - 1) * $guests->perPage() + $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($rs->tanggal)->format('Y-m-d') }}</td>
                        <td>{{ $rs->nama }}</td>
                        <td>{{ $rs->alamat }}</td>
                        <td>{{ ucfirst($rs->jenis_kelamin) }}</td>
                        <td>
                            @if ($rs->workField && $rs->tujuan_pengunjung)
                                ({{ $rs->workField->name }}) {{ $rs->tujuan_pengunjung }}
                            @elseif ($rs->workField)
                                ({{ $rs->workField->name }})
                            @elseif ($rs->tujuan_pengunjung)
                                {{ $rs->tujuan_pengunjung }}
                            @endif
                        </td>
                        <td>
                            @if ($rs->instansi && $rs->nama_instansi)
                                ({{ $rs->instansi }}) {{ $rs->nama_instansi }}
                            @elseif ($rs->instansi)
                                ({{ $rs->instansi }})
                            @elseif ($rs->nama_instansi)
                                {{ $rs->nama_instansi }}
                            @endif
                        </td>
                        <td>{{ $rs->no_hp }}</td>
                        <td><img src="{{ url($rs->foto) }}" alt="{{ $rs->foto }}" width="100" /></td>
                        <td id="status-text-{{ $rs->id }}">{{ $rs->status }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-exchange-alt"></i> <!-- Ikon Ubah Status -->
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item update-status" data-id="{{ $rs->id }}" data-status="Sedang Kunjungan">
                                        Sedang Kunjungan
                                    </button>
                                    <button class="dropdown-item update-status" data-id="{{ $rs->id }}" data-status="Selesai Kunjungan">
                                        Selesai Kunjungan
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm" onclick="openDeleteModal('{{ $rs->nama }}', {{ $rs->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            {{ $guests->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- Delete Modal -->
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
                   <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
                   <button type="button" class="btn btn-danger" id="confirm-delete-btn" disabled>Hapus</button>
               </div>
           </div>
       </div>
    </div>

    <!-- SweetAlert Notifikasi -->
    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Update status via dropdown button
        document.querySelectorAll('.update-status').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const guestId = this.getAttribute('data-id');
                const newStatus = this.getAttribute('data-status');

                fetch(`/guest/${guestId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Perbarui tampilan status di kolom Status
                        document.getElementById(`status-text-${guestId}`).innerText = newStatus;
                        Swal.fire({
                            title: 'Sukses!',
                            text: 'Status berhasil diperbarui!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        // Fungsi Delete Modal
        let guestIdToDelete = null;
        function openDeleteModal(guestName, guestId) {
            document.getElementById('guest-name').innerText = guestName;
            guestIdToDelete = guestId;
            $('#deleteModal').modal('show');
        }
        function closeDeleteModal() {
            $('#deleteModal').modal('hide');
            document.getElementById('confirmation-input').value = '';
            document.getElementById('confirm-delete-btn').disabled = true;
        }
        document.getElementById('confirmation-input').addEventListener('input', function() {
            const inputValue = this.value;
            const guestName = document.getElementById('guest-name').innerText;
            document.getElementById('confirm-delete-btn').disabled = (inputValue !== guestName);
        });
        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            if (guestIdToDelete !== null) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("guest.destroy", "") }}/' + guestIdToDelete;
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
