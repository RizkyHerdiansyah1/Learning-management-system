@extends('layouts.app')

@section('title', 'Tambah Materi - Journey Learn')

@section('content')
    <a href="{{ route('kelas.kelola-materi', $kelas) }}" class="btn btn-outline-primary mb-3">
        ← Kembali ke Kelola Materi
    </a>

    <div class="card">
        <div class="card-body p-4">
            <h3 class="mb-2">➕ Tambah Materi Baru</h3>
            <p class="text-muted mb-4">Kelas: <strong>{{ $kelas->nama_kelas }}</strong></p>

            <form action="{{ route('materi.store', $kelas) }}" method="POST" id="materiForm">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label">Judul Materi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                           name="judul" value="{{ old('judul') }}" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Tipe Materi <span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card h-100 tipe-option" onclick="selectTipe('video')">
                                <div class="card-body text-center py-4">
                                    <input type="radio" name="tipe" value="video" id="tipe_video" 
                                           {{ old('tipe') == 'video' ? 'checked' : '' }} required>
                                    <div style="font-size: 48px;">🎥</div>
                                    <h6>Video</h6>
                                    <small class="text-muted">YouTube Embed</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 tipe-option" onclick="selectTipe('text')">
                                <div class="card-body text-center py-4">
                                    <input type="radio" name="tipe" value="text" id="tipe_text"
                                           {{ old('tipe') == 'text' ? 'checked' : '' }}>
                                    <div style="font-size: 48px;">📄</div>
                                    <h6>Text</h6>
                                    <small class="text-muted">Rich Text</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 tipe-option" onclick="selectTipe('quiz')">
                                <div class="card-body text-center py-4">
                                    <input type="radio" name="tipe" value="quiz" id="tipe_quiz"
                                           {{ old('tipe') == 'quiz' ? 'checked' : '' }}>
                                    <div style="font-size: 48px;">📝</div>
                                    <h6>Quiz</h6>
                                    <small class="text-muted">Multiple Choice</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video Content -->
                <div class="mb-4 content-field" id="content_video">
                    <label class="form-label">URL YouTube <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="konten_video" id="video_url"
                           placeholder="https://www.youtube.com/watch?v=VIDEO_ID atau https://youtu.be/VIDEO_ID"
                           onkeyup="previewVideo()">
                    <small class="text-muted">📌 Bisa pakai format: watch?v= atau youtu.be/ atau embed/</small>
                    
                    <!-- Video Preview -->
                    <div id="video_preview" class="mt-3" style="display: none;">
                        <label class="form-label text-success">✅ Preview:</label>
                        <div class="ratio ratio-16x9">
                            <iframe id="preview_iframe" frameborder="0" allowfullscreen
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>
                    </div>
                </div>

                <!-- Text Content -->
                <div class="mb-4 content-field" id="content_text" style="display: none;">
                    <label class="form-label">Konten Materi <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="konten_text" id="text_content" rows="10"
                              placeholder="Tulis konten materi di sini..."></textarea>
                </div>

                <!-- Quiz Info -->
                <div class="mb-4 content-field" id="content_quiz" style="display: none;">
                    <div class="alert alert-info">
                        📝 Setelah membuat materi quiz, Anda akan diarahkan untuk menambahkan soal-soal.
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Estimasi Durasi (menit) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('durasi_menit') is-invalid @enderror" 
                           name="durasi_menit" value="{{ old('durasi_menit', 10) }}" min="1" required>
                    @error('durasi_menit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                        💾 Simpan Materi
                    </button>
                    <a href="{{ route('kelas.kelola-materi', $kelas) }}" class="btn btn-outline-secondary btn-lg">
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
    document.getElementById('tipe_' + tipe).checked = true;
    
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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const checked = document.querySelector('input[name="tipe"]:checked');
    if (checked) {
        selectTipe(checked.value);
    }
});
</script>
@endsection
