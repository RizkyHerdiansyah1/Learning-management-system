@extends('layouts.app')

@section('title', 'Buat Kelas Baru - Journey Learn')

@section('content')
    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary mb-3">
        ← Kembali ke Dashboard
    </a>

    <div class="card">
        <div class="card-body p-4">
            <h3 class="mb-4">📚 Buat Kelas Baru</h3>

            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror" name="nama_kelas"
                        value="{{ old('nama_kelas') }}" placeholder="Contoh: Pemrograman Web Dasar" required>
                    @error('nama_kelas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="4"
                        placeholder="Jelaskan tentang kelas ini..." required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select class="form-select @error('kategori') is-invalid @enderror" name="kategori" required>
                        <option value="">Pilih Kategori...</option>
                        <option value="Web Development" {{ old('kategori') == 'Web Development' ? 'selected' : '' }}>Web
                            Development</option>
                        <option value="Mobile Development" {{ old('kategori') == 'Mobile Development' ? 'selected' : '' }}>
                            Mobile Development</option>
                        <option value="Data Science" {{ old('kategori') == 'Data Science' ? 'selected' : '' }}>Data Science
                        </option>
                        <option value="Machine Learning" {{ old('kategori') == 'Machine Learning' ? 'selected' : '' }}>Machine
                            Learning</option>
                        <option value="UI/UX Design" {{ old('kategori') == 'UI/UX Design' ? 'selected' : '' }}>UI/UX Design
                        </option>
                        <option value="Database" {{ old('kategori') == 'Database' ? 'selected' : '' }}>Database</option>
                        <option value="Networking" {{ old('kategori') == 'Networking' ? 'selected' : '' }}>Networking</option>
                        <option value="Other" {{ old('kategori') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                        🚀 Buat Kelas
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection