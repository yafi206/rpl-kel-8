<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Notification;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role?->name === 'mahasiswa') {
            $reports = Report::with(['facility', 'aiAnalysis'])
                ->where('reporter_id', $user->id)
                ->orderByDesc('created_at')
                ->get();

            $stats = [
                'semua' => $reports->count(),
                'pending' => $reports->where('status', 'pending')->count(),
                'assigned' => $reports->where('status', 'assigned')->count(),
                'resolved' => $reports->where('status', 'resolved')->count(),
            ];

            return view('dashboard.index', compact('user', 'reports', 'stats'));
        }

        if ($user->role?->name === 'petugas') {
            $assignedReports = Report::with(['facility', 'reporter', 'assignment'])
                ->whereHas('assignment', fn ($query) => $query->where('assigned_to_user_id', $user->id))
                ->orderByDesc('created_at')
                ->get();

            $stats = [
                'semua' => $assignedReports->count(),
                'in_progress' => $assignedReports->where('status', 'in_progress')->count(),
                'verified' => $assignedReports->where('status', 'verified')->count(),
                'resolved' => $assignedReports->where('status', 'resolved')->count(),
            ];

            return view('dashboard.index', compact('user', 'assignedReports', 'stats'));
        }

        if ($user->role?->name === 'kaprodi') {
            $reports = Report::with(['facility', 'reporter', 'aiAnalysis'])->orderByDesc('created_at')->get();
            $stats = [
                'semua' => $reports->count(),
                'pending' => $reports->where('status', 'pending')->count(),
                'verified' => $reports->where('status', 'verified')->count(),
                'resolved' => $reports->where('status', 'resolved')->count(),
            ];

            return view('dashboard.index', compact('user', 'reports', 'stats'));
        }

        $reports = Report::with(['facility', 'reporter', 'assignment', 'aiAnalysis'])->orderByDesc('created_at')->get();
        $stats = [
            'semua' => $reports->count(),
            'pending' => $reports->where('status', 'pending')->count(),
            'assigned' => $reports->where('status', 'assigned')->count(),
            'resolved' => $reports->where('status', 'resolved')->count(),
        ];

        $notifications = Notification::with('report')->where('user_id', $user->id)->orderByDesc('created_at')->limit(10)->get();

        return view('dashboard.index', compact('user', 'reports', 'stats', 'notifications'));
    }
}
