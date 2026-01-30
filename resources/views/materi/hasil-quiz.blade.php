@extends('layouts.app')

@section('title', 'Hasil Quiz - Journey Learn')

@section('content')
    <a href="{{ route('kelas.show', $materi->kelas) }}" class="btn btn-outline-primary mb-3">
        ← Kembali ke Kelas
    </a>

    <div class="card mb-4">
        <div class="card-body text-center py-5">
            <h2 class="mb-3">📊 Hasil Quiz</h2>
            <h1 class="display-1 mb-3" style="color: {{ $hasil->isPassed() ? '#28a745' : '#dc3545' }}">
                {{ $hasil->percentage }}%
            </h1>
            <p class="lead">
                Skor: {{ $hasil->skor }} / {{ $hasil->total_soal * 10 }}
            </p>
            @if($hasil->isPassed())
                <div class="alert alert-success d-inline-block">
                    🎉 Selamat! Anda lulus quiz ini!
                </div>
            @else
                <div class="alert alert-warning d-inline-block">
                    😔 Belum lulus. Nilai minimum 70%.
                </div>
            @endif
        </div>
    </div>

    <!-- Detail Jawaban -->
    <h4 class="mb-3">📝 Review Jawaban</h4>
    @foreach($hasil->jawaban_detail as $index => $detail)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <strong>{{ $index + 1 }}. {{ $detail['pertanyaan'] }}</strong>
                    @if($detail['is_benar'])
                        <span class="badge bg-success">✓ Benar (+{{ $detail['poin'] }})</span>
                    @else
                        <span class="badge bg-danger">✗ Salah</span>
                    @endif
                </div>
                <div class="small">
                    <p class="mb-1">
                        Jawaban Anda: <strong>{{ $detail['jawaban_user'] ?: '-' }}</strong>
                    </p>
                    @if(!$detail['is_benar'])
                        <p class="mb-0 text-success">
                            Jawaban Benar: <strong>{{ $detail['jawaban_benar'] }}</strong>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    <div class="d-flex gap-2 mt-4">
        <a href="{{ route('kelas.show', $materi->kelas) }}" class="btn btn-primary flex-grow-1">
            Kembali ke Kelas
        </a>
        @php
            $attempts = \App\Models\HasilKuis::where('user_id', auth()->id())
                ->where('materi_id', $materi->id)->count();
        @endphp
        @if($attempts < 3)
            <a href="{{ route('materi.view', $materi) }}" class="btn btn-outline-primary">
                Coba Lagi ({{ 3 - $attempts }} tersisa)
            </a>
        @endif
    </div>
@endsection