@extends('layouts.app')

@section('title', 'Selamat Datang di Dashboard Buku Tamu Diskominfo')

@section('contents')
  <div class="row">
    <h3>Dashboard Buku Tamu</h3>
    <canvas id="guestsChart" width="400" height="200"></canvas>
  </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mengambil data tamu yang dikirim dari controller
        const guestsData = @json($guests);

        // Debugging, tampilkan data di konsol browser
        console.log(guestsData);

        // Pastikan data tersedia sebelum membuat chart
        if (guestsData && guestsData.length > 0) {
            // Mendapatkan tanggal dan jumlah tamu
            const labels = guestsData.map(guest => guest.tanggal);
            const data = guestsData.map(guest => guest.total);

            // Menyusun chart
            const ctx = document.getElementById('guestsChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'pie', // Tipe chart, bisa juga 'bar', 'pie', dll.
                data: {
                    labels: labels, // Tanggal sebagai label di sumbu X
                    datasets: [{
                        label: 'Jumlah Tamu',
                        data: data, // Jumlah tamu per tanggal
                        borderColor: 'rgba(75, 192, 192, 1)', // Warna garis
                        borderWidth: 2,
                        fill: false, // Tidak ada area yang diwarnai di bawah garis
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true // Menampilkan sumbu Y dari angka 0
                        }
                    }
                }
            });
        } else {
            console.error('No data available for the chart');
        }
    });
</script>
