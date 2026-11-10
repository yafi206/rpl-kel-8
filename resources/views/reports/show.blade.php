@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
    <div class="page">
        <div class="container">
            <div class="flex space-between" style="align-items: flex-end; margin-bottom: 20px;">
                <div>
                    <p class="muted" style="margin:0 0 8px; text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.8rem;">Detail laporan</p>
                    <h1 style="margin:0;">{{ $report->title }}</h1>
                </div>
                <span class="badge {{ $report->status }}">{{ $report->status }}</span>
            </div>

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            <div class="grid grid-2">
                <div class="card">
                    <h3 style="margin-top:0;">Informasi laporan</h3>
                    <ul class="status-list">
                        <li><span>Pelapor</span><strong>{{ $report->reporter->name }}</strong></li>
                        <li><span>Fasilitas</span><strong>{{ $report->facility->name }}</strong></li>
                        <li><span>Gedung</span><strong>{{ $report->facility->building }}</strong></li>
                        <li><span>Lokasi</span><strong>{{ $report->location_detail ?? 'Belum diisi' }}</strong></li>
                        <li><span>Urgensi</span><strong>{{ ucfirst($report->urgency) }}</strong></li>
                        <li><span>Waktu</span><strong>{{ $report->created_at->translatedFormat('d F Y, H:i') }}</strong></li>
                    </ul>
                    <div class="mt-16">
                        <h4>Deskripsi</h4>
                        <p class="muted" style="margin:0;">{{ $report->description }}</p>
                    </div>
                    @if ($report->photos->isNotEmpty())
                        <div class="mt-16">
                            <h4>Foto</h4>
                            <img src="{{ asset('storage/' . $report->photos->first()->path) }}" alt="Foto laporan" style="width:100%; border-radius: 12px; max-height:300px; object-fit:cover; border:1px solid #ddd;">
                        </div>
                    @endif
                </div>

                <div class="card">
                    <h3 style="margin-top:0;">AI OpenCV / analisis foto</h3>
                    @if ($report->aiAnalysis)
                        <ul class="status-list">
                            <li><span>Risiko</span><strong>{{ ucfirst($report->aiAnalysis->risk_level) }}</strong></li>
                            <li><span>Kepercayaan</span><strong>{{ $report->aiAnalysis->confidence_score }}%</strong></li>
                        </ul>
                        <p class="muted" style="margin-top: 16px;">{{ $report->aiAnalysis->summary }}</p>
                        <p><strong>Rekomendasi:</strong> {{ $report->aiAnalysis->recommendation }}</p>
                    @else
                        <p class="muted">Belum ada hasil analisis AI.</p>
                    @endif
                </div>
            </div>

            @if (Auth::user()->role?->name === 'admin' || Auth::user()->role?->name === 'kaprodi')
                <div class="card mt-16">
                    <h3 style="margin-top:0;">Penugasan petugas</h3>
                    <form method="POST" action="{{ route('reports.assign', $report) }}">
                        @csrf
                        <div class="field-grid">
                            <div class="form-row">
                                <label class="field-label" for="assigned_to_user_id">Petugas</label>
                                <select id="assigned_to_user_id" name="assigned_to_user_id" required>
                                    <option value="">Pilih petugas</option>
                                    @foreach (\App\Models\User::whereHas('role', fn($query) => $query->where('name', 'petugas'))->get() as $user)
                                        <option value="{{ $user->id }}" {{ optional($report->assignment)->assigned_to_user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row">
                                <label class="field-label" for="notes">Catatan</label>
                                <input id="notes" name="notes" value="{{ optional($report->assignment)->notes ?? '' }}">
                            </div>
                        </div>
                        <button type="submit" class="button primary">Simpan penugasan</button>
                    </form>
                </div>

                <div class="card mt-16">
                    <h3 style="margin-top:0;">Verifikasi laporan</h3>
                    <form method="POST" action="{{ route('reports.verify', $report) }}">
                        @csrf
                        <div class="field-grid">
                            <div class="form-row">
                                <label class="field-label" for="verdict">Keputusan</label>
                                <select id="verdict" name="verdict" required>
                                    <option value="approve">Setujui</option>
                                    <option value="reject">Tolak</option>
                                </select>
                            </div>
                            <div class="form-row">
                                <label class="field-label" for="verification_notes">Catatan</label>
                                <input id="verification_notes" name="notes">
                            </div>
                        </div>
                        <button type="submit" class="button primary">Proses verifikasi</button>
                    </form>
                </div>
            @endif

            @if (Auth::user()->role?->name === 'petugas' || Auth::user()->role?->name === 'admin')
                <div class="card mt-16">
                    <h3 style="margin-top:0;">Update status</h3>
                    <form method="POST" action="{{ route('reports.status', $report) }}">
                        @csrf
                        <div class="field-grid">
                            <div class="form-row">
                                <label class="field-label" for="status">Status baru</label>
                                <select id="status" name="status" required>
                                    <option value="in_progress" {{ $report->status == 'in_progress' ? 'selected' : '' }}>Dalam proses</option>
                                    <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Selesai</option>
                                    <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                            <div class="form-row">
                                <label class="field-label" for="status_notes">Catatan pekerjaan</label>
                                <input id="status_notes" name="notes">
                            </div>
                        </div>
                        <button type="submit" class="button primary">Update status</button>
                    </form>
                </div>
            @endif

            <div class="card mt-16">
                <h3 style="margin-top:0;">Riwayat status</h3>
                @if ($report->statusHistory->isNotEmpty())
                    <ul class="status-list">
                        @foreach ($report->statusHistory as $history)
                            <li>
                                <span>{{ ucfirst($history->new_status) }}</span>
                                <strong>{{ $history->created_at->translatedFormat('d M Y, H:i') }}</strong>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="muted">Belum ada riwayat status.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
