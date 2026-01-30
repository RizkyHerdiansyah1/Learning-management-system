@extends('layouts.app')

@section('title', 'Edit Materi - Journey Learn')

@section('content')
    <a href="{{ route('kelas.kelola-materi', $materi->kelas) }}" class="btn btn-outline-primary mb-3">
        ← Kembali ke Kelola Materi
    </a>

    <div class="card">
        <div class="card-body p-4">
            <h3 class="mb-2">✏️ Edit Materi</h3>
            <p class="text-muted mb-4">Kelas: <strong>{{ $materi->kelas->nama_kelas }}</strong></p>

            <form action="{{ route('materi.update', $materi) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="form-label">Judul Materi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                           name="judul" value="{{ old('judul', $materi->judul) }}" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Tipe Materi <span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body text-center py-4">
                                    <input type="radio" name="tipe" value="video" id="tipe_video" 
                                           {{ $materi->tipe == 'video' ? 'checked' : '' }} required
                                           onchange="selectTipe('video')">
                                    <div style="font-size: 48px;">🎥</div>
                                    <h6>Video</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body text-center py-4">
                                    <input type="radio" name="tipe" value="text" id="tipe_text"
                                           {{ $materi->tipe == 'text' ? 'checked' : '' }}
                                           onchange="selectTipe('text')">
                                    <div style="font-size: 48px;">📄</div>
                                    <h6>Text</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body text-center py-4">
                                    <input type="radio" name="tipe" value="quiz" id="tipe_quiz"
                                           {{ $materi->tipe == 'quiz' ? 'checked' : '' }}
                                           onchange="selectTipe('quiz')">
                                    <div style="font-size: 48px;">📝</div>
                                    <h6>Quiz</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video Content -->
                <div class="mb-4 content-field" id="content_video" 
                     style="{{ $materi->tipe != 'video' ? 'display:none;' : '' }}">
                    <label class="form-label">URL YouTube</label>
                    <input type="text" class="form-control" name="konten_video" id="video_url"
                           value="{{ $materi->tipe == 'video' ? $materi->konten : '' }}"
                           placeholder="https://www.youtube.com/watch?v=VIDEO_ID atau https://youtu.be/VIDEO_ID"
                           onkeyup="previewVideo()">
                    <small class="text-muted">📌 Bisa pakai format: watch?v= atau youtu.be/ atau embed/</small>
                    
                    <!-- Video Preview -->
                    <div id="video_preview" class="mt-3" style="{{ $materi->tipe == 'video' ? '' : 'display: none;' }}">
                        <label class="form-label text-success">✅ Preview:</label>
                        <div class="ratio ratio-16x9">
                            <iframe id="preview_iframe" frameborder="0" allowfullscreen
                                    src="{{ $materi->tipe == 'video' ? $materi->konten : '' }}"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>
                    </div>
                </div>

                <!-- Text Content -->
                <div class="mb-4 content-field" id="content_text" 
                     style="{{ $materi->tipe != 'text' ? 'display:none;' : '' }}">
                    <label class="form-label">Konten Materi</label>
                    <textarea class="form-control" name="konten_text" id="text_content" rows="10">{{ $materi->tipe == 'text' ? $materi->konten : '' }}</textarea>
                </div>

                <!-- Quiz Info -->
                <div class="mb-4 content-field" id="content_quiz" 
                     style="{{ $materi->tipe != 'quiz' ? 'display:none;' : '' }}">
                    <div class="alert alert-info">
                        📝 Untuk mengedit soal quiz, gunakan menu "Kelola Soal" setelah menyimpan.
                    </div>
                    @if($materi->tipe == 'quiz')
                        <a href="{{ route('materi.tambah-soal', $materi) }}" class="btn btn-info">
                            📝 Kelola Soal Quiz
                        </a>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="form-label">Estimasi Durasi (menit) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="durasi_menit" 
                           value="{{ old('durasi_menit', $materi->durasi_menit) }}" min="1" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                        💾 Update Materi
                    </button>
                    <a href="{{ route('kelas.kelola-materi', $materi->kelas) }}" class="btn btn-outline-secondary btn-lg">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
function selectTipe(tipe) {
    document.querySelectorAll('.content-field').forEach(el => {
        el.style.display = 'none';
    });
    document.getElementById('content_' + tipe).style.display = 'block';
}

function previewVideo() {
    const url = document.getElementById('video_url').value.trim();
    const previewDiv = document.getElementById('video_preview');
    const iframe = document.getElementById('preview_iframe');
    
    if (!url) {
        previewDiv.style.display = 'none';
        return;
    }
    
    let embedUrl = url;
    
    // Convert youtube.com/watch?v= to embed
    if (url.includes('youtube.com/watch')) {
        const urlParams = new URLSearchParams(new URL(url).search);
        const videoId = urlParams.get('v');
        if (videoId) {
            embedUrl = 'https://www.youtube.com/embed/' + videoId;
        }
    }
    // Convert youtu.be/ to embed
    else if (url.includes('youtu.be/')) {
        const videoId = url.split('youtu.be/')[1].split('?')[0];
        embedUrl = 'https://www.youtube.com/embed/' + videoId;
    }
    
    // Show preview if valid embed URL
    if (embedUrl.includes('youtube.com/embed/')) {
        iframe.src = embedUrl;
        previewDiv.style.display = 'block';
    } else {
        previewDiv.style.display = 'none';
    }
}

// FIX: Disable hidden konten fields before submit
document.querySelector('form').addEventListener('submit', function(e) {
    // Disable all hidden konten inputs
    document.querySelectorAll('.content-field').forEach(field => {
        if (field.style.display === 'none') {
            const input = field.querySelector('[name="konten"]');
            if (input) input.disabled = true;
        }
    });
});

// Trigger preview on page load for edit form
document.addEventListener('DOMContentLoaded', function() {
    const videoUrl = document.getElementById('video_url');
    if (videoUrl && videoUrl.value) {
        previewVideo();
    }
});
</script>
@endsection
