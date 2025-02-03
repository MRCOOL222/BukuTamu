@extends('layouts.app')

@section('title', 'Selamat Datang di Dashboard Buku Tamu Diskominfo')

@section('contents')
  <div class="row">
    <h3>Dashboard Buku Tamu</h3>
    
    <!-- Kotak untuk jumlah tamu saat ini -->
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card shadow-lg rounded" style="background-color: #f8f9fa; border-left: 5px solid #17a2b8;">
        <div class="card-body text-center">
          <h5 class="card-title" style="color: #17a2b8; font-weight: bold;">Jumlah Tamu Saat Ini</h5>
          <p class="card-text" style="font-size: 3rem; color: #007bff; font-weight: bold;">
            {{ number_format($guests->sum('total')) }}
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection
