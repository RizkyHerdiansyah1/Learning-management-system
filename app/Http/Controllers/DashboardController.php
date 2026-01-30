<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Enrollment;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isDosen()) {
            return $this->dosenDashboard();
        }

        return $this->mahasiswaDashboard();
    }

    /**
     * Dashboard for Dosen
     */
    private function dosenDashboard()
    {
        $user = Auth::user();

        // Get kelas created by dosen
        $kelasList = Kelas::where('dosen_id', $user->id)
            ->withCount(['enrollments', 'materi'])
            ->latest('tanggal_dibuat')
            ->get();

        // Statistics
        $totalKelas = $kelasList->count();
        $totalMahasiswa = $kelasList->sum('enrollments_count');
        $totalMateri = $kelasList->sum('materi_count');

        return view('dashboard.dosen', compact(
            'kelasList',
            'totalKelas',
            'totalMahasiswa',
            'totalMateri'
        ));
    }

    /**
     * Dashboard for Mahasiswa
     */
    private function mahasiswaDashboard()
    {
        $user = Auth::user();

        // Get enrolled kelas with progress
        $enrolledKelas = Kelas::whereHas('enrollments', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['dosen', 'materi'])
            ->get()
            ->map(function ($kelas) use ($user) {
                $totalMateri = $kelas->materi->count();
                $completedMateri = Progress::where('user_id', $user->id)
                    ->whereIn('materi_id', $kelas->materi->pluck('id'))
                    ->where('status', 'completed')
                    ->count();

                $kelas->progress_percentage = $totalMateri > 0
                    ? round(($completedMateri / $totalMateri) * 100)
                    : 0;
                $kelas->total_materi = $totalMateri;
                $kelas->completed_materi = $completedMateri;

                return $kelas;
            });

        // Statistics
        $totalEnrolled = $enrolledKelas->count();
        $completedKelas = $enrolledKelas->where('progress_percentage', 100)->count();
        $avgProgress = $enrolledKelas->avg('progress_percentage') ?? 0;

        return view('dashboard.mahasiswa', compact(
            'enrolledKelas',
            'totalEnrolled',
            'completedKelas',
            'avgProgress'
        ));
    }
}
