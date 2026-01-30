@extends('layouts.app')

@section('title', 'Jelajahi Kelas - Journey Learn')

@section('content')
    <div class="mb-4">
        <h2>Jelajahi Kelas</h2>
        <p class="text-muted">Temukan kelas yang sesuai dengan minat Anda</p>
    </div>

    <div class="row">
        @forelse($kelasList as $index => $kelas)
            @php
                $colors = ['', 'green', 'pink', 'blue'];
                $colorClass = $colors[$index % 4];
            @endphp
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="class-card">
                    <div class="class-card-header {{ $colorClass }}" style="position: relative;">
                        @if($kelas->is_enrolled)
                            <span class="badge bg-white text-primary"
                                style="position: absolute; top: 15px; right: 15px; padding: 8px 15px;">
                                ✓ Enrolled
                            </span>
                        @endif
                    </div>
                    <div class="p-4">
                        <span class="badge bg-primary mb-2">{{ $kelas->kategori }}</span>
                        <h5 class="mb-2">{{ $kelas->nama_kelas }}</h5>
                        <p class="text-muted small mb-3">{{ Str::limit($kelas->deskripsi, 100) }}</p>

                        <div class="d-flex gap-3 mb-3 text-muted small">
                            <span>👨‍🏫 {{ $kelas->dosen->nama }}</span>
                        </div>
                        <div class="d-flex gap-3 mb-3 text-muted small">
                            <span>👥 {{ $kelas->enrollments_count }} siswa</span>
                            <span>📚 {{ $kelas->materi_count }} materi</span>
                        </div>

                        @if($kelas->is_enrolled)
                            <a href="{{ route('kelas.show', $kelas) }}" class="btn btn-success w-100">
                                Lanjut Belajar
                            </a>
                        @else
                            <form action="{{ route('kelas.enroll', $kelas) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    Enroll Sekarang
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Belum ada kelas tersedia.</div>
            </div>
        @endforelse
    </div>
@endsection