@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa - Journey Learn')

@section('content')
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2>Selamat Datang, {{ auth()->user()->nama }}! 👋</h2>
            <p class="text-muted">Lanjutkan perjalanan belajar Anda</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <h3 class="mb-1">{{ $totalEnrolled }}</h3>
                <small>Kelas Terdaftar</small>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <h3 class="mb-1">{{ $completedKelas }}</h3>
                <small>Kelas Selesai</small>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h3 class="mb-1">{{ number_format($avgProgress, 0) }}%</h3>
                <small>Rata-rata Progress</small>
            </div>
        </div>
    </div>

    <!-- Enrolled Classes -->
    <h4 class="mb-3">Kelas Saya</h4>
    <div class="row">
        @forelse($enrolledKelas as $index => $kelas)
            @php
                $colors = ['', 'green', 'pink', 'blue'];
                $colorClass = $colors[$index % 4];
            @endphp
            <div class="col-md-6 mb-4">
                <div class="class-card">
                    <div class="class-card-header {{ $colorClass }}"></div>
                    <div class="p-4">
                        <h5 class="mb-2">{{ $kelas->nama_kelas }}</h5>
                        <p class="text-muted small mb-3">{{ Str::limit($kelas->deskripsi, 80) }}</p>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <small class="text-muted">📖 {{ $kelas->total_materi }} materi</small>
                            </div>
                            <div class="progress-circle" style="--progress: {{ $kelas->progress_percentage }}">
                                <span class="progress-text">{{ $kelas->progress_percentage }}%</span>
                            </div>
                        </div>

                        <a href="{{ route('kelas.show', $kelas) }}" class="btn btn-primary w-100">
                            Lanjut Belajar
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Anda belum mendaftar kelas apapun.
                    <a href="{{ route('kelas.browse') }}" class="alert-link">Jelajahi kelas sekarang!</a>
                </div>
            </div>
        @endforelse
    </div>
@endsection