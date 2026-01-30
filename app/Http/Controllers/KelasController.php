<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Enrollment;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    /**
     * Browse all kelas (for mahasiswa)
     */
    public function browse()
    {
        $user = Auth::user();

        $kelasList = Kelas::with(['dosen'])
            ->withCount(['enrollments', 'materi'])
            ->latest('tanggal_dibuat')
            ->get()
            ->map(function ($kelas) use ($user) {
                $kelas->is_enrolled = Enrollment::where('user_id', $user->id)
                    ->where('kelas_id', $kelas->id)
                    ->exists();
                return $kelas;
            });

        return view('kelas.browse', compact('kelasList'));
    }

    /**
     * Enroll mahasiswa to a kelas
     */
    public function enroll(Request $request, Kelas $kelas)
    {
        $user = Auth::user();

        // Check if already enrolled
        $exists = Enrollment::where('user_id', $user->id)
            ->where('kelas_id', $kelas->id)
            ->exists();

        if ($exists) {
            return back()->with('warning', 'Anda sudah terdaftar di kelas ini!');
        }

        // Create enrollment
        Enrollment::create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
        ]);

        // Unlock first materi
        $firstMateri = $kelas->materi()->orderBy('urutan')->first();
        if ($firstMateri) {
            Progress::create([
                'user_id' => $user->id,
                'materi_id' => $firstMateri->id,
                'status' => 'in_progress',
                'tanggal_mulai' => now(),
            ]);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Berhasil mendaftar kelas! Mulai belajar sekarang.');
    }

    /**
     * Show kelas detail (for mahasiswa)
     */
    public function show(Kelas $kelas)
    {
        $user = Auth::user();

        // Check enrollment
        $isEnrolled = Enrollment::where('user_id', $user->id)
            ->where('kelas_id', $kelas->id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('kelas.browse')
                ->with('warning', 'Anda belum terdaftar di kelas ini!');
        }

        // Get materi with progress
        $materiList = $kelas->materi()->orderBy('urutan')->get();

        foreach ($materiList as $materi) {
            $progress = Progress::where('user_id', $user->id)
                ->where('materi_id', $materi->id)
                ->first();

            $materi->progress_status = $progress ? $progress->status : 'locked';
            $materi->can_access = $this->canAccessMateri($user->id, $materi);
        }

        // Calculate overall progress
        $totalMateri = $materiList->count();
        $completedMateri = $materiList->where('progress_status', 'completed')->count();
        $progressPercentage = $totalMateri > 0
            ? round(($completedMateri / $totalMateri) * 100)
            : 0;

        return view('kelas.show', compact(
            'kelas',
            'materiList',
            'progressPercentage',
            'completedMateri',
            'totalMateri'
        ));
    }

    /**
     * Check if user can access materi (sequential learning)
     */
    private function canAccessMateri($userId, $materi): bool
    {
        // First materi is always accessible
        if ($materi->urutan == 1) {
            return true;
        }

        // Check if all previous materi are completed
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

    // ===== DOSEN METHODS =====

    /**
     * Show form to create new kelas
     */
    public function create()
    {
        return view('kelas.create');
    }

    /**
     * Store new kelas
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string|max:50',
        ]);

        $kelas = Kelas::create([
            'dosen_id' => Auth::id(),
            'nama_kelas' => $validated['nama_kelas'],
            'deskripsi' => $validated['deskripsi'],
            'kategori' => $validated['kategori'],
        ]);

        return redirect()->route('kelas.kelola-materi', $kelas)
            ->with('success', 'Kelas berhasil dibuat! Sekarang tambahkan materi.');
    }

    /**
     * Kelola materi for a kelas
     */
    public function kelolaMateri(Kelas $kelas)
    {
        // Check ownership
        if ($kelas->dosen_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $materiList = $kelas->materi()->orderBy('urutan')->get();
        $totalMahasiswa = $kelas->enrollments()->count();

        return view('kelas.kelola-materi', compact('kelas', 'materiList', 'totalMahasiswa'));
    }

    /**
     * Delete a kelas
     */
    public function destroy(Kelas $kelas)
    {
        // Check ownership
        if ($kelas->dosen_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $kelas->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Kelas berhasil dihapus!');
    }
}
