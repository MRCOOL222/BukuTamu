@extends('layouts.app')

@section('title', '')

@section('contents')
<div class="container">
    <div class="row">
        <!-- Card 1: Jumlah Tamu Bulan Ini -->
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card shadow-lg rounded" style="background-color: #f8f9fa; border-left: 5px solid #17a2b8;">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color: #17a2b8; font-weight: bold;">Jumlah Tamu Bulan Ini</h5>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div id="guestChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Berdasarkan Instansi -->
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card shadow-lg rounded" style="background-color: #f8f9fa; border-left: 5px solid #28a745;">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color: #28a745; font-weight: bold;">Berdasarkan Instansi</h5>
                    <div id="instansiChart"></div>
                </div>
            </div>
        </div>

        <!-- Card 3: Berdasarkan Bidang -->
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card shadow-lg rounded" style="background-color: #f8f9fa; border-left: 5px solid #ffc107;">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color: #ffc107; font-weight: bold;">Berdasarkan Bidang</h5>
                    <div id="bidangChart"></div>
                </div>
            </div>
        </div>

        <!-- Card 4: Berdasarkan Jenis Kelamin -->
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card shadow-lg rounded" style="background-color: #f8f9fa; border-left: 5px solid #ff6f61;">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color: #ff6f61; font-weight: bold;">Berdasarkan Jenis Kelamin</h5>
                    <div id="genderChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var currentMonth = "{{ \Carbon\Carbon::now()->translatedFormat('F') }}";

    // Chart 1: Jumlah Tamu Bulan Ini
    var guestChartOptions = {
        chart: { type: 'bar', height: 350 },
        series: [{ name: 'Jumlah Tamu', data: [{{ $currentTotal }}] }],
        xaxis: { categories: [currentMonth] },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return value.toFixed(0);
                }
            }
        }
    };
    var guestChart = new ApexCharts(document.querySelector("#guestChart"), guestChartOptions);
    guestChart.render();

    // Chart 2: Berdasarkan Instansi
    var instansiChartOptions = {
        chart: { type: 'bar', height: 350 },
        series: [
            { name: 'Dinas', data: [{{ $dinasCount }}] },
            { name: 'Non Kedinasan', data: [{{ $nonKedinasanCount }}] }
        ],
        xaxis: { categories: [currentMonth] },
        legend: { show: true, position: 'bottom', horizontalAlign: 'center' },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return value.toFixed(0);
                }
            }
        }
    };
    var instansiChart = new ApexCharts(document.querySelector("#instansiChart"), instansiChartOptions);
    instansiChart.render();

    // Chart 3: Berdasarkan Bidang
    var bidangSeries = {!! json_encode($bidangSeries) !!};
    var bidangChartOptions = {
        chart: { type: 'bar', height: 350 },
        series: bidangSeries,
        colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#3F51B5'],
        xaxis: { categories: [currentMonth] },
        legend: {
            show: true,
            position: 'bottom',
            horizontalAlign: 'center',
            markers: { width: 12, height: 12, strokeWidth: 0 }
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return value.toFixed(0);
                }
            }
        }
    };
    var bidangChart = new ApexCharts(document.querySelector("#bidangChart"), bidangChartOptions);
    bidangChart.render();

    // Chart 4: Berdasarkan Jenis Kelamin
    var genderSeries = {!! json_encode($genderSeries) !!};
    var genderChartOptions = {
        chart: { type: 'bar', height: 350 },
        series: genderSeries,
        colors: ['#1E90FF', '#FF69B4'],
        xaxis: { categories: [currentMonth] },
        legend: {
            show: true,
            position: 'bottom',
            horizontalAlign: 'center',
            markers: { width: 12, height: 12, strokeWidth: 0 }
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return value.toFixed(0);
                }
            }
        }
    };
    var genderChart = new ApexCharts(document.querySelector("#genderChart"), genderChartOptions);
    genderChart.render();
});
</script>
@endsection
