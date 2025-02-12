@extends('layouts.app')

@section('title', 'Rekap Data Tamu')

@section('contents')
    <div class="container">
        <h1>Rekap Data Tamu</h1>

        <!-- Search bar dan tombol Filter -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Search bar -->
            <form action="{{ route('guest.recap') }}" method="GET" class="form-inline">
                <input type="text" name="search" class="form-control" placeholder="Cari tamu..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary ml-2">Cari</button>
            </form>

            <!-- Tombol Filter & Export -->
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    Filter
                </button>
                <a href="{{ route('guest.export') }}?{{ http_build_query(request()->all()) }}" class="btn btn-success ms-2">
                    <i class="fas fa-print"></i> Export
                </a>
            </div>
        </div>

        <!-- Tabel Data -->
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
                </tr>
            </thead>
            <tbody>
                @forelse ($guests as $index => $rs)
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
                        <td>
                            <img src="{{ url($rs->foto) }}" alt="{{ $rs->foto }}" width="100" />
                        </td>
                        <td id="status-text-{{ $rs->id }}">{{ $rs->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data tamu yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $guests->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- Modal Filter -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('guest.recap') }}" method="GET">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filter Data</h5>
                        <!-- Tombol X untuk menutup modal -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Dari Tanggal</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Sampai Tanggal</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
