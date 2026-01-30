<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Kuis;
use App\Models\Progress;
use App\Models\HasilKuis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    /**
     * Show form to create new materi
     */
    public function create(Kelas $kelas)
    {
        // Check ownership
        if ($kelas->dosen_id !== Auth::id()) {
            abort(403);
        }

        // Get next urutan
        $nextUrutan = $kelas->materi()->max('urutan') + 1;

        return view('materi.create', compact('kelas', 'nextUrutan'));
    }

    /**
     * Store new materi
     */
    public function store(Request $request, Kelas $kelas)
    {
        // Check ownership
        if ($kelas->dosen_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:video,text,quiz',
            'durasi_menit' => 'required|integer|min:1',
            'konten_video' => 'nullable|string',
            'konten_text' => 'nullable|string',
        ]);

        // Merge konten based on tipe
        $konten = '';
        if ($validated['tipe'] === 'video') {
            $konten = $validated['konten_video'] ?? '';
        } elseif ($validated['tipe'] === 'text') {
            $konten = $validated['konten_text'] ?? '';
        }

        // Get next urutan
        $nextUrutan = $kelas->materi()->max('urutan') + 1;

        $materi = Materi::create([
            'kelas_id' => $kelas->id,
            'judul' => $validated['judul'],
            'tipe' => $validated['tipe'],
            'konten' => $konten,
            'urutan' => $nextUrutan,
            'durasi_menit' => $validated['durasi_menit'],
        ]);

        if ($validated['tipe'] === 'quiz') {
            return redirect()->route('materi.tambah-soal', $materi)
                ->with('success', 'Materi quiz berhasil dibuat! Sekarang tambahkan soal.');
        }

        return redirect()->route('kelas.kelola-materi', $kelas)
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    /**
     * Show form to edit materi
     */
    public function edit(Materi $materi)
    {
        // Check ownership
        if ($materi->kelas->dosen_id !== Auth::id()) {
            abort(403);
        }

        return view('materi.edit', compact('materi'));
    }

    /**
     * Update materi
     */
    public function update(Request $request, Materi $materi)
    {
        // Check ownership
        if ($materi->kelas->dosen_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:video,text,quiz',
            'durasi_menit' => 'required|integer|min:1',
            'konten_video' => 'nullable|string',
            'konten_text' => 'nullable|string',
        ]);

        // Merge konten based on tipe
        $konten = '';
        if ($validated['tipe'] === 'video') {
            $konten = $validated['konten_video'] ?? '';
        } elseif ($validated['tipe'] === 'text') {
            $konten = $validated['konten_text'] ?? '';
        }

        $materi->update([
            'judul' => $validated['judul'],
            'tipe' => $validated['tipe'],
            'konten' => $konten,
            'durasi_menit' => $validated['durasi_menit'],
        ]);

        return redirect()->route('kelas.kelola-materi', $materi->kelas)
            ->with('success', 'Materi berhasil diupdate!');
    }

    /**
     * Delete materi
     */
    public function destroy(Materi $materi)
    {
        // Check ownership
        if ($materi->kelas->dosen_id !== Auth::id()) {
            abort(403);
        }

        $kelas = $materi->kelas;
        $materi->delete();

        // Re-order remaining materi
        $remaining = Materi::where('kelas_id', $kelas->id)
            ->orderBy('urutan')
            ->get();

        $urutan = 1;
        foreach ($remaining as $m) {
            $m->update(['urutan' => $urutan]);
            $urutan++;
        }

        return redirect()->route('kelas.kelola-materi', $kelas)
            ->with('success', 'Materi berhasil dihapus!');
    }

    /**
     * View materi (for mahasiswa)
     */
    public function view(Materi $materi)
    {
        $user = Auth::user();
        $kelas = $materi->kelas;

        // Check enrollment
        $isEnrolled = $user->enrollments()
            ->where('kelas_id', $kelas->id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('kelas.browse')
                ->with('warning', 'Anda belum terdaftar di kelas ini!');
        }

        // Check access (sequential learning)
        if (!$this->canAccessMateri($user->id, $materi)) {
            return redirect()->route('kelas.show', $kelas)
                ->with('warning', 'Selesaikan materi sebelumnya terlebih dahulu!');
        }

        // Get quiz questions if quiz
        $kuisList = [];
        $quizResult = null;
        $attemptCount = 0;

        if ($materi->isQuiz()) {
            $kuisList = $materi->kuis()->get();
            $attemptCount = HasilKuis::where('user_id', $user->id)
                ->where('materi_id', $materi->id)
                ->count();
        }

        // Get current progress
        $progress = Progress::where('user_id', $user->id)
            ->where('materi_id', $materi->id)
            ->first();

        // Get navigation
        $prevMateri = $materi->previousMateri();
        $nextMateri = $materi->nextMateri();

        return view('materi.view', compact(
            'materi',
            'kelas',
            'kuisList',
            'progress',
            'attemptCount',
            'prevMateri',
            'nextMateri'
        ));
    }

    /**
     * Mark materi as complete
     */
    public function markComplete(Materi $materi)
    {
        $user = Auth::user();

        // Update progress
        $progress = Progress::firstOrCreate(
            ['user_id' => $user->id, 'materi_id' => $materi->id],
            ['status' => 'in_progress', 'tanggal_mulai' => now()]
        );

        $progress->update([
            'status' => 'completed',
            'tanggal_selesai' => now(),
        ]);

        // Unlock next materi
        $nextMateri = $materi->nextMateri();
        if ($nextMateri) {
            Progress::firstOrCreate(
                ['user_id' => $user->id, 'materi_id' => $nextMateri->id],
                ['status' => 'in_progress', 'tanggal_mulai' => now()]
            );
        }

        return redirect()->route('kelas.show', $materi->kelas)
            ->with('success', 'Materi berhasil diselesaikan!');
    }

    /**
     * Submit quiz
     */
    public function submitQuiz(Request $request, Materi $materi)
    {
        $user = Auth::user();

        // Check attempt limit
        $attemptCount = HasilKuis::where('user_id', $user->id)
            ->where('materi_id', $materi->id)
            ->count();

        if ($attemptCount >= 3) {
            return back()->with('warning', 'Anda sudah mencapai batas maksimal 3x percobaan!');
        }

        // Get quiz questions
        $kuisList = $materi->kuis()->get();

        $skor = 0;
        $jawabanDetail = [];

        foreach ($kuisList as $kuis) {
            $jawaban = $request->input('jawaban_' . $kuis->id, '');
            $isCorrect = $kuis->isCorrect($jawaban);

            if ($isCorrect) {
                $skor += $kuis->poin;
            }

            $jawabanDetail[] = [
                'pertanyaan' => $kuis->pertanyaan,
                'jawaban_user' => $jawaban,
                'jawaban_benar' => $kuis->jawaban_benar,
                'is_benar' => $isCorrect,
                'poin' => $isCorrect ? $kuis->poin : 0,
            ];
        }

        // Save result
        HasilKuis::create([
            'user_id' => $user->id,
            'materi_id' => $materi->id,
            'skor' => $skor,
            'total_soal' => $kuisList->count(),
            'jawaban_detail' => $jawabanDetail,
        ]);

        // Update progress
        $progress = Progress::firstOrCreate(
            ['user_id' => $user->id, 'materi_id' => $materi->id],
            ['status' => 'in_progress', 'tanggal_mulai' => now()]
        );

        $progress->update([
            'status' => 'completed',
            'tanggal_selesai' => now(),
        ]);

        // Unlock next materi
        $nextMateri = $materi->nextMateri();
        if ($nextMateri) {
            Progress::firstOrCreate(
                ['user_id' => $user->id, 'materi_id' => $nextMateri->id],
                ['status' => 'in_progress', 'tanggal_mulai' => now()]
            );
        }

        return redirect()->route('materi.hasil-quiz', $materi)
            ->with('success', 'Quiz berhasil disubmit!');
    }

    /**
     * Show quiz result
     */
    public function hasilQuiz(Materi $materi)
    {
        $user = Auth::user();

        $hasil = HasilKuis::where('user_id', $user->id)
            ->where('materi_id', $materi->id)
            ->latest()
            ->first();

        if (!$hasil) {
            return redirect()->route('materi.view', $materi)
                ->with('warning', 'Anda belum mengerjakan quiz ini!');
        }

        return view('materi.hasil-quiz', compact('materi', 'hasil'));
    }

    /**
     * Show form to add quiz questions
     */
    public function tambahSoal(Materi $materi)
    {
        // Check ownership
        if ($materi->kelas->dosen_id !== Auth::id()) {
            abort(403);
        }

        $soalList = $materi->kuis()->get();

        return view('materi.tambah-soal', compact('materi', 'soalList'));
    }

    /**
     * Store new quiz question
     */
    public function storeSoal(Request $request, Materi $materi)
    {
        // Check ownership
        if ($materi->kelas->dosen_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'pertanyaan' => 'required|string',
            'pilihan_a' => 'required|string|max:255',
            'pilihan_b' => 'required|string|max:255',
            'pilihan_c' => 'required|string|max:255',
            'pilihan_d' => 'required|string|max:255',
            'jawaban_benar' => 'required|in:A,B,C,D',
            'poin' => 'required|integer|min:1',
        ]);

        Kuis::create([
            'materi_id' => $materi->id,
            'pertanyaan' => $validated['pertanyaan'],
            'pilihan_a' => $validated['pilihan_a'],
            'pilihan_b' => $validated['pilihan_b'],
            'pilihan_c' => $validated['pilihan_c'],
            'pilihan_d' => $validated['pilihan_d'],
            'jawaban_benar' => $validated['jawaban_benar'],
            'poin' => $validated['poin'],
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan!');
    }

    /**
     * Delete quiz question
     */
    public function destroySoal(Kuis $kuis)
    {
        // Check ownership
        if ($kuis->materi->kelas->dosen_id !== Auth::id()) {
            abort(403);
        }

        $materi = $kuis->materi;
        $kuis->delete();

        return redirect()->route('materi.tambah-soal', $materi)
            ->with('success', 'Soal berhasil dihapus!');
    }

    /**
     * Check if user can access materi (sequential learning)
     */
    private function canAccessMateri($userId, $materi): bool
    {
        if ($materi->urutan == 1) {
            return true;
        }

        $totalPrevious = $materi->urutan - 1;
        $completedPrevious = Progress::where('user_id', $userId)
            ->whereHas('materi', function ($query) use ($materi) {
                $query->where('kelas_id', $materi->kelas_id)
                    ->where('urutan', '<', $materi->urutan);
            })
            ->where('status', 'completed')
            ->count();

        return $completedPrevious == $totalPrevious;
    }
}
