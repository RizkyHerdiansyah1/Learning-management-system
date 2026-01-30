@extends('layouts.app')

@section('title', 'Tambah Soal Quiz - Journey Learn')

@section('content')
    <a href="{{ route('kelas.kelola-materi', $materi->kelas) }}" class="btn btn-outline-primary mb-3">
        ← Kembali ke Kelola Materi
    </a>

    <div class="card mb-4">
        <div class="card-body">
            <h3 class="mb-2">📝 Kelola Soal Quiz</h3>
            <p class="text-muted">Materi: <strong>{{ $materi->judul }}</strong></p>
        </div>
    </div>

    <!-- Form Tambah Soal -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3">➕ Tambah Soal Baru</h5>

            <form action="{{ route('materi.store-soal', $materi) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('pertanyaan') is-invalid @enderror" name="pertanyaan" rows="2"
                        required>{{ old('pertanyaan') }}</textarea>
                    @error('pertanyaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pilihan A <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pilihan_a" value="{{ old('pilihan_a') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pilihan B <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pilihan_b" value="{{ old('pilihan_b') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pilihan C <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pilihan_c" value="{{ old('pilihan_c') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pilihan D <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pilihan_d" value="{{ old('pilihan_d') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jawaban Benar <span class="text-danger">*</span></label>
                        <select class="form-select" name="jawaban_benar" required>
                            <option value="">Pilih jawaban benar...</option>
                            <option value="A" {{ old('jawaban_benar') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('jawaban_benar') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="C" {{ old('jawaban_benar') == 'C' ? 'selected' : '' }}>C</option>
                            <option value="D" {{ old('jawaban_benar') == 'D' ? 'selected' : '' }}>D</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Poin <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="poin" value="{{ old('poin', 10) }}" min="1"
                            required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    ➕ Tambah Soal
                </button>
            </form>
        </div>
    </div>

    <!-- Daftar Soal -->
    <h5 class="mb-3">📋 Daftar Soal ({{ $soalList->count() }} soal)</h5>
    @forelse($soalList as $index => $soal)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-2">{{ $index + 1 }}. {{ $soal->pertanyaan }}</h6>
                        <div class="row small text-muted">
                            <div class="col-md-6">
                                <p class="mb-1">A. {{ $soal->pilihan_a }}</p>
                                <p class="mb-1">B. {{ $soal->pilihan_b }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1">C. {{ $soal->pilihan_c }}</p>
                                <p class="mb-1">D. {{ $soal->pilihan_d }}</p>
                            </div>
                        </div>
                        <span class="badge bg-success">Jawaban: {{ $soal->jawaban_benar }}</span>
                        <span class="badge bg-warning text-dark">{{ $soal->poin }} poin</span>
                    </div>
                    <form action="{{ route('soal.destroy', $soal) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">🗑️</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            Belum ada soal. Tambahkan soal pertama di atas!
        </div>
    @endforelse

    @if($soalList->count() > 0)
        <a href="{{ route('kelas.kelola-materi', $materi->kelas) }}" class="btn btn-success btn-lg w-100 mt-3">
            ✓ Selesai - Kembali ke Kelola Materi
        </a>
    @endif
@endsection