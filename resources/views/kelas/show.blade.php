@extends('layouts.app')

@section('title', $kelas->nama_kelas . ' - Journey Learn')

@section('content')
    <!-- Header -->
    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary mb-3">
        ← Kembali ke Dashboard
    </a>

    <div class="card mb-4">
        <div class="card-body p-4"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px;">
            <h1 class="mb-3">{{ $kelas->nama_kelas }}</h1>
            <p class="mb-4">{{ $kelas->deskripsi }}</p>
            <div class="d-flex gap-4">
                <span>👨‍🏫 {{ $kelas->dosen->nama }}</span>
                <span>📚 {{ $totalMateri }} Materi</span>
                <span>🏷️ {{ $kelas->kategori }}</span>
            </div>
        </div>
    </div>

    <!-- Progress -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Progress Belajar</h5>
                <span class="badge bg-primary">{{ $completedMateri }}/{{ $totalMateri }} Materi</span>
            </div>
            <div class="progress" style="height: 10px; border-radius: 5px;">
                <div class="progress-bar" role="progressbar"
                    style="width: {{ $progressPercentage }}%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                </div>
            </div>
            <small class="text-muted">{{ $progressPercentage }}% selesai</small>
        </div>
    </div>

    <!-- Materi List -->
    <h4 class="mb-3">Daftar Materi</h4>
    @foreach($materiList as $materi)
        @php
            $iconType = $materi->tipe;
            $icon = $iconType === 'video' ? '🎥' : ($iconType === 'quiz' ? '📝' : '📄');
            $isLocked = !$materi->can_access;
            $isCompleted = $materi->progress_status === 'completed';
        @endphp
        <div class="materi-item {{ $isLocked ? 'opacity-50' : '' }}">
            <div class="materi-icon {{ $iconType }}">
                {{ $icon }}
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge bg-secondary me-2">#{{ $materi->urutan }}</span>
                        <strong>{{ $materi->judul }}</strong>
                    </div>
                    @if($isCompleted)
                        <span class="badge bg-success">✓ Selesai</span>
                    @elseif($isLocked)
                        <span class="badge bg-secondary">🔒 Terkunci</span>
                    @else
                        <span class="badge bg-primary">Tersedia</span>
                    @endif
                </div>
                <small class="text-muted">
                    {{ ucfirst($materi->tipe) }} • {{ $materi->durasi_menit }} menit
                </small>
            </div>
            <div>
                @if(!$isLocked)
                    <a href="{{ route('materi.view', $materi) }}" class="btn btn-sm btn-primary">
                        {{ $isCompleted ? 'Review' : 'Mulai' }}
                    </a>
                @else
                    <button class="btn btn-sm btn-secondary" disabled>🔒</button>
                @endif
            </div>
        </div>
    @endforeach
@endsection