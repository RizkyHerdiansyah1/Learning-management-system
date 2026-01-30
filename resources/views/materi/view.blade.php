@extends('layouts.app')

@section('title', $materi->judul . ' - Journey Learn')

@section('content')
    <!-- Navigation -->
    <a href="{{ route('kelas.show', $kelas) }}" class="btn btn-outline-primary mb-3">
        ← Kembali ke {{ $kelas->nama_kelas }}
    </a>

    <!-- Materi Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="badge bg-primary mb-2">{{ ucfirst($materi->tipe) }}</span>
                    <h2 class="mb-2">{{ $materi->judul }}</h2>
                    <small class="text-muted">⏱️ {{ $materi->durasi_menit }} menit</small>
                </div>
                @if($progress && $progress->isCompleted())
                    <span class="badge bg-success fs-6">✓ Selesai</span>
                @endif
            </div>
        </div>
    </div>

    <!-- VIDEO CONTENT -->
    @if($materi->isVideo())
        <div class="card mb-4">
            <div class="card-body p-0">
                @php
                    // Convert YouTube URL to embed format
                    $embedUrl = $materi->konten;

                    // Handle youtube.com/watch?v=VIDEO_ID
                    if (str_contains($embedUrl, 'youtube.com/watch')) {
                        parse_str(parse_url($embedUrl, PHP_URL_QUERY), $params);
                        $embedUrl = 'https://www.youtube.com/embed/' . ($params['v'] ?? '');
                    } elseif (str_contains($embedUrl, 'youtu.be/')) {
                        $videoId = basename(parse_url($embedUrl, PHP_URL_PATH));
                        $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                    }
                @endphp

                {{-- Debug: Hapus setelah video berfungsi --}}
                <div class="alert alert-warning m-3">
                    <small>
                        <strong>Debug Info:</strong><br>
                        Original URL: {{ $materi->konten }}<br>
                        Embed URL: {{ $embedUrl }}
                    </small>
                </div>

                <div class="ratio ratio-16x9">
                    <iframe src="{{ $embedUrl }}" allowfullscreen frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                </div>
            </div>
        </div>

        @if(!$progress || !$progress->isCompleted())
            <form action="{{ route('materi.complete', $materi) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-lg w-100"
                    onclick="return confirm('Yakin sudah selesai menonton video ini?')">
                    ✓ Tandai Selesai & Lanjut ke Materi Berikutnya
                </button>
            </form>
        @endif
    @endif

    <!-- TEXT CONTENT -->
    @if($materi->isText())
        <div class="card mb-4">
            <div class="card-body">
                <div class="content-text">
                    {!! nl2br(e($materi->konten)) !!}
                </div>
            </div>
        </div>

        @if(!$progress || !$progress->isCompleted())
            <form action="{{ route('materi.complete', $materi) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-lg w-100"
                    onclick="return confirm('Yakin sudah selesai membaca materi ini?')">
                    ✓ Tandai Selesai & Lanjut ke Materi Berikutnya
                </button>
            </form>
        @endif
    @endif

    <!-- QUIZ CONTENT -->
    @if($materi->isQuiz())
        <div class="alert alert-info mb-4">
            <strong>📝 Petunjuk:</strong> Pilih jawaban yang paling tepat untuk setiap pertanyaan.
            Setelah selesai, klik tombol Submit untuk melihat hasil.
            <hr class="my-2">
            <small>
                🔄 <strong>Percobaan:</strong> {{ $attemptCount }} dari 3 (Sisa: {{ 3 - $attemptCount }} kali)
            </small>
        </div>

        @if($attemptCount >= 3)
            <div class="alert alert-warning">
                ⚠️ Anda sudah mencapai batas maksimal 3x percobaan.
                <a href="{{ route('materi.hasil-quiz', $materi) }}" class="alert-link">Lihat hasil terakhir</a>
            </div>
        @else
            <form action="{{ route('materi.submit-quiz', $materi) }}" method="POST">
                @csrf
                @foreach($kuisList as $index => $soal)
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">
                                {{ $index + 1 }}. {{ $soal->pertanyaan }}
                                <span class="badge bg-warning text-dark">{{ $soal->poin }} poin</span>
                            </h5>

                            <label class="quiz-option">
                                <input type="radio" name="jawaban_{{ $soal->id }}" value="A" required>
                                A. {{ $soal->pilihan_a }}
                            </label>
                            <label class="quiz-option">
                                <input type="radio" name="jawaban_{{ $soal->id }}" value="B" required>
                                B. {{ $soal->pilihan_b }}
                            </label>
                            <label class="quiz-option">
                                <input type="radio" name="jawaban_{{ $soal->id }}" value="C" required>
                                C. {{ $soal->pilihan_c }}
                            </label>
                            <label class="quiz-option">
                                <input type="radio" name="jawaban_{{ $soal->id }}" value="D" required>
                                D. {{ $soal->pilihan_d }}
                            </label>
                        </div>
                    </div>
                @endforeach

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    📤 Submit Jawaban
                </button>
            </form>
        @endif
    @endif

    <!-- Navigation -->
    <div class="d-flex justify-content-between mt-4">
        @if($prevMateri)
            <a href="{{ route('materi.view', $prevMateri) }}" class="btn btn-outline-secondary">
                ← Sebelumnya
            </a>
        @else
            <div></div>
        @endif

        @if($nextMateri)
            <a href="{{ route('materi.view', $nextMateri) }}" class="btn btn-outline-primary">
                Selanjutnya →
            </a>
        @else
            <a href="{{ route('kelas.show', $kelas) }}" class="btn btn-success">
                🏁 Kembali ke Daftar Materi
            </a>
        @endif
    </div>
@endsection