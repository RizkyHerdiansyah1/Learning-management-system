@extends('layouts.app')

@section('title', 'Kelola Materi - ' . $kelas->nama_kelas)

@section('content')
    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary mb-3">
        ← Kembali ke Dashboard
    </a>

    <!-- Header -->
    <div class="card mb-4">
        <div class="card-body p-4"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px;">
            <h2 class="mb-3">{{ $kelas->nama_kelas }}</h2>
            <p class="mb-4">{{ $kelas->deskripsi }}</p>
            <div class="d-flex gap-4">
                <span>👥 Mahasiswa: {{ $totalMahasiswa }}</span>
                <span>📚 Total Materi: {{ $materiList->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Daftar Materi</h4>
        <a href="{{ route('materi.create', $kelas) }}" class="btn btn-primary">
            + Tambah Materi Baru
        </a>
    </div>

    <!-- Materi List -->
    @forelse($materiList as $materi)
        @php
            $icon = $materi->tipe === 'video' ? '🎥' : ($materi->tipe === 'quiz' ? '📝' : '📄');
        @endphp
        <div class="materi-item">
            <div class="materi-icon {{ $materi->tipe }}">
                {{ $icon }}
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h6 class="mb-0">
                        <span class="badge bg-secondary">#{{ $materi->urutan }}</span>
                        {{ $materi->judul }}
                    </h6>
                    <span class="badge bg-primary">{{ ucfirst($materi->tipe) }}</span>
                </div>
                <small class="text-muted">
                    Durasi: {{ $materi->durasi_menit }} menit
                </small>
            </div>
            <div class="d-flex gap-2">
                @if($materi->isQuiz())
                    <a href="{{ route('materi.tambah-soal', $materi) }}" class="btn btn-sm btn-info">
                        📝 Soal
                    </a>
                @endif
                <a href="{{ route('materi.edit', $materi) }}" class="btn btn-sm btn-warning">
                    ✏️ Edit
                </a>
                <form action="{{ route('materi.destroy', $materi) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                </form>
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            Belum ada materi. <a href="{{ route('materi.create', $kelas) }}" class="alert-link">Tambah materi pertama!</a>
        </div>
    @endforelse

    <!-- Tips Card -->
    <div class="card mt-4">
        <div class="card-body">
            <h6 class="card-title">💡 Tips Mengelola Materi:</h6>
            <ul class="mb-0 small">
                <li>Susun materi secara berurutan dari mudah ke sulit</li>
                <li>Gunakan video untuk penjelasan visual</li>
                <li>Tambahkan quiz untuk mengetes pemahaman</li>
                <li>Materi akan terkunci secara otomatis (sequential learning)</li>
            </ul>
        </div>
    </div>
@endsection