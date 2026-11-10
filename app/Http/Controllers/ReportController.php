<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Facility;
use App\Models\FacilityType;
use App\Models\Notification;
use App\Models\Report;
use App\Models\ReportAiAnalysis;
use App\Models\ReportPhoto;
use App\Models\ReportStatusHistory;
use App\Models\ReportVerification;
use App\Models\User;
use App\Services\ReportAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function create()
    {
        $facilityTypes = FacilityType::all();

        return view('reports.create', compact('facilityTypes'));
    }

    public function store(Request $request, ReportAnalysisService $analysisService)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'min:20'],
            'facility_name' => ['required', 'string', 'max:150'],
            'building' => ['required', 'string', 'max:150'],
            'room' => ['nullable', 'string', 'max:80'],
            'floor' => ['nullable', 'string', 'max:30'],
            'urgency' => ['required', 'in:rendah,sedang,tinggi,darurat'],
            'location_detail' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $facilityType = FacilityType::firstOrCreate(
            ['name' => $request->input('facility_type', 'Umum')],
            ['description' => 'Fasilitas yang dilaporkan']
        );

        $facility = Facility::firstOrCreate(
            [
                'name' => $validated['facility_name'],
                'building' => $validated['building'],
                'room' => $validated['room'] ?? null,
                'floor' => $validated['floor'] ?? null,
            ],
            [
                'facility_type_id' => $facilityType->id,
                'status' => 'damaged',
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
            ]
        );

        $report = Report::create([
            'reporter_id' => Auth::id(),
            'facility_id' => $facility->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'urgency' => $validated['urgency'],
            'status' => 'pending',
            'location_detail' => $validated['location_detail'] ?? $validated['building'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ]);

        ReportStatusHistory::create([
            'report_id' => $report->id,
            'old_status' => null,
            'new_status' => 'pending',
            'changed_by_user_id' => Auth::id(),
            'notes' => 'Laporan baru dikirim oleh pelapor.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reports', 'public');
            ReportPhoto::create([
                'report_id' => $report->id,
                'path' => $photoPath,
                'caption' => 'Foto dokumen kerusakan',
                'is_primary' => true,
            ]);
        }

        $analysis = $analysisService->analyze($validated['description'], $photoPath);
        ReportAiAnalysis::create([
            'report_id' => $report->id,
            'summary' => $analysis['summary'],
            'confidence_score' => $analysis['confidence_score'],
            'risk_level' => $analysis['risk_level'],
            'recommendation' => $analysis['recommendation'],
        ]);

        $adminIds = User::whereHas('role', fn ($query) => $query->where('name', 'admin'))->pluck('id');
        foreach ($adminIds as $adminId) {
            Notification::create([
                'user_id' => $adminId,
                'report_id' => $report->id,
                'title' => 'Laporan baru masuk',
                'message' => 'Laporan ' . $report->title . ' menunggu pengecekan admin.',
                'type' => 'report',
                'is_read' => false,
            ]);
        }

        return redirect()->route('reports.show', $report)->with('success', 'Laporan berhasil dikirim.');
    }

    public function show(Report $report)
    {
        $report->load(['facility', 'reporter', 'photos', 'aiAnalysis', 'statusHistory.changedBy', 'assignment.assignedTo', 'verifications.verifier']);

        return view('reports.show', compact('report'));
    }

    public function assign(Request $request, Report $report)
    {
        abort_unless(Auth::user()?->role?->name === 'admin' || Auth::user()?->role?->name === 'kaprodi', 403);

        $validated = $request->validate([
            'assigned_to_user_id' => ['required', 'exists:users,id'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $assignment = Assignment::updateOrCreate(
            ['report_id' => $report->id],
            [
                'assigned_to_user_id' => $validated['assigned_to_user_id'],
                'assigned_by_user_id' => Auth::id(),
                'notes' => $validated['notes'] ?? null,
            ]
        );

        $oldStatus = $report->status;
        $report->update(['status' => 'assigned']);
        ReportStatusHistory::create([
            'report_id' => $report->id,
            'old_status' => $oldStatus,
            'new_status' => 'assigned',
            'changed_by_user_id' => Auth::id(),
            'notes' => $validated['notes'] ?? 'Laporan ditugaskan kepada petugas.',
        ]);

        Notification::create([
            'user_id' => $validated['assigned_to_user_id'],
            'report_id' => $report->id,
            'title' => 'Tugas baru diberikan',
            'message' => 'Laporan "' . $report->title . '" telah ditugaskan kepada Anda.',
            'type' => 'assignment',
            'is_read' => false,
        ]);

        return back()->with('success', 'Penugasan berhasil dibuat.');
    }

    public function verify(Request $request, Report $report)
    {
        abort_unless(Auth::user()?->role?->name === 'admin' || Auth::user()?->role?->name === 'kaprodi', 403);

        $validated = $request->validate([
            'verdict' => ['required', 'in:approve,reject'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $report->verifications()->create([
            'verifier_id' => Auth::id(),
            'verdict' => $validated['verdict'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $status = $validated['verdict'] === 'approve' ? 'verified' : 'rejected';
        $oldStatus = $report->status;
        $report->update(['status' => $status]);
        ReportStatusHistory::create([
            'report_id' => $report->id,
            'old_status' => $oldStatus,
            'new_status' => $status,
            'changed_by_user_id' => Auth::id(),
            'notes' => $validated['notes'] ?? 'Verifikasi laporan selesai.',
        ]);

        return back()->with('success', 'Verifikasi laporan berhasil diproses.');
    }

    public function updateStatus(Request $request, Report $report)
    {
        abort_unless(Auth::user()?->role?->name === 'petugas' || Auth::user()?->role?->name === 'admin', 403);

        $validated = $request->validate([
            'status' => ['required', 'in:in_progress,resolved,rejected'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $oldStatus = $report->status;
        $report->update(['status' => $validated['status']]);
        ReportStatusHistory::create([
            'report_id' => $report->id,
            'old_status' => $oldStatus,
            'new_status' => $validated['status'],
            'changed_by_user_id' => Auth::id(),
            'notes' => $validated['notes'] ?? 'Update status oleh petugas teknis.',
        ]);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
