@extends('layouts.app')

@section('title', 'Dashboard Dosen - Journey Learn')

@section('content')
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>Dashboard Dosen 👨‍🏫</h2>
                    <p class="text-muted">Kelola kelas dan materi pembelajaran Anda</p>
                </div>
                <a href="{{ route('kelas.create') }}" class="btn btn-primary">
                    + Buat Kelas Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <h3 class="mb-1">{{ $totalKelas }}</h3>
                <small>Total Kelas</small>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <h3 class="mb-1">{{ $totalMahasiswa }}</h3>
                <small>Total Mahasiswa</small>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h3 class="mb-1">{{ $totalMateri }}</h3>
                <small>Total Materi</small>
            </div>
        </div>
    </div>

    <!-- My Classes -->
    <h4 class="mb-3">Kelas Saya</h4>
    <div class="row">
        @forelse($kelasList as $index => $kelas)
            @php
                $colors = ['', 'green', 'pink', 'blue'];
                $colorClass = $colors[$index % 4];
            @endphp
            <div class="col-md-6 mb-4">
                <div class="class-card">
                    <div class="class-card-header {{ $colorClass }}"></div>
                    <div class="p-4">
                        <span class="badge bg-primary mb-2">{{ $kelas->kategori }}</span>
                        <h5 class="mb-2">{{ $kelas->nama_kelas }}</h5>
                        <p class="text-muted small mb-3">{{ Str::limit($kelas->deskripsi, 80) }}</p>

                        <div class="d-flex gap-3 mb-3 text-muted small">
                            <span>👥 {{ $kelas->enrollments_count }} mahasiswa</span>
                            <span>📚 {{ $kelas->materi_count }} materi</span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('kelas.kelola-materi', $kelas) }}" class="btn btn-primary flex-grow-1">
                                Kelola Materi
                            </a>
                            <form action="{{ route('kelas.destroy', $kelas) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">🗑️</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Anda belum membuat kelas apapun.
                    <a href="{{ route('kelas.create') }}" class="alert-link">Buat kelas pertama!</a>
                </div>
            </div>
        @endforelse
    </div>
@endsection