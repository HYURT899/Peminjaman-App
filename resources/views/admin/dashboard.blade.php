@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard Management Asset</h1>
@stop

@section('content')
    <div class="row">
        {{-- Card Total Assets --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $totalAssets }}</h3>
                    <p>Total Assets</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
                <a href="{{ route('admin.assets.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Bisa tambahin card lain di sini, misalnya jumlah kategori --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $kategori->count() }}</h3>
                    <p>Total Kategori</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tags"></i>
                </div>
                <a href="{{ route('categories.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalPeminjams }}</h3>
                    <p>Total Peminjam</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-alt"></i>
                </div>
                <a href="{{ route('admin.peminjam.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalUsers }}</h3>
                    <p>Total User</p>
                </div>
                <div class="icon">
                    <i class="fa fa-address-card"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Grafik & tabel --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Grafik Asset per Kategori</h3>
            </div>
            <div class="card-body">
                <canvas id="kategoriChart" style="width:100%; height:60vh; max-height:345px;"></canvas>
            </div>
            </div>
        </div>

        <div class="col-md-6">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Jumlah Asset per Kategori</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 385px; overflow-y: auto;">
                <table class="table table-bordered mb-0">
                    <thead class="sticky-top" style="background-color: #ffff;">
                        <tr>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kategori as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('kategoriChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($kategori->pluck('name')),
                datasets: [{
                    data: @json($kategori->pluck('total')),
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8'],
                }]
            }
        });
    </script>
@stop
