@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard-shell">
        <div class="dashboard-header">
            <div class="header-user">
                <div class="soft-chip" style="min-width: 140px;">{{ ucfirst($user->role?->name ?? 'User') }}</div>
                <strong style="font-size:1.1rem;">{{ $user->name }}</strong>
            </div>
            <div class="menu-toggle" aria-label="Menu"><span></span></div>
        </div>

        @if (session('success'))
            <div style="margin-bottom: 18px; background: rgba(92, 227, 138, 0.14); border:1px solid rgba(92,227,138,0.28); color: white; padding: 12px 14px; border-radius: 12px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="stats-grid">
            @foreach (['semua' => 'Total', 'pending' => 'Pending', 'assigned' => 'Assigned', 'in_progress' => 'Proses', 'verified' => 'Verified', 'resolved' => 'Selesai'] as $key => $label)
                @if (isset($stats[$key]))
                    <div class="stat-box">
                        <div class="label">{{ $label }}</div>
                        <div class="value">{{ $stats[$key] }}</div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="dashboard-grid">
            <div class="glass-card">
                <div class="card-head">
                    <h3>Ringkasan</h3>
                    <div class="menu-toggle" style="width:32px;height:32px;"><span></span></div>
                </div>
                <ul class="info-list">
                    <li><span>Peran</span><strong>{{ ucfirst($user->role?->name ?? 'User') }}</strong></li>
                    <li><span>Email</span><strong>{{ $user->email }}</strong></li>
                    <li><span>Departemen</span><strong>{{ $user->department ?? 'Belum diatur' }}</strong></li>
                    <li><span>Telepon</span><strong>{{ $user->phone ?? 'Belum diatur' }}</strong></li>
                </ul>
            </div>

            <div class="glass-card">
                <div class="card-head">
                    <h3>Notifikasi</h3>
                    <div class="menu-toggle" style="width:32px;height:32px;"><span></span></div>
                </div>
                <div class="status-row">
                    <span class="muted">Laporan</span>
                    <strong>{{ $stats['semua'] ?? 0 }}</strong>
                </div>
                <div class="status-row">
                    <span class="muted">Menunggu</span>
                    <strong>{{ $stats['pending'] ?? 0 }}</strong>
                </div>
                <div class="status-row">
                    <span class="muted">Terverifikasi</span>
                    <strong>{{ $stats['verified'] ?? 0 }}</strong>
                </div>
                <div class="status-row">
                    <span class="muted">Selesai</span>
                    <strong>{{ $stats['resolved'] ?? 0 }}</strong>
                </div>
            </div>

            <div class="glass-card card-wide">
                <div class="card-head">
                    <h3>Daftar Laporan</h3>
                    @if ($user->role?->name === 'mahasiswa')
                        <a href="{{ route('reports.create') }}" class="soft-chip" style="min-width: 120px;">+ Baru</a>
                    @endif
                </div>

                @php
                    $items = $reports ?? ($assignedReports ?? []);
                @endphp

                @if ($items->isNotEmpty())
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Urgensi</th>
                                    <th>Pelapor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $report)
                                    <tr>
                                        <td><strong>{{ $report->title }}</strong></td>
                                        <td>{{ $report->facility?->building ?? 'Lokasi tidak tercatat' }}</td>
                                        <td><span class="badge">{{ $report->status }}</span></td>
                                        <td>{{ ucfirst($report->urgency) }}</td>
                                        <td>{{ $report->reporter?->name ?? '-' }}</td>
                                        <td><a href="{{ route('reports.show', $report) }}" class="soft-chip">Lihat</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="muted" style="margin: 8px 0 0;">Belum ada laporan yang tersedia untuk role saat ini.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
