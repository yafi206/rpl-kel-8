@extends('layouts.app')

@section('title', 'Layanan Pelaporan Fasilitas Rusak')

@section('content')
    <section class="hero">
        <div class="container">
            <div class="grid grid-2" style="align-items:center;">
                <div>
                    <p class="muted" style="margin:0 0 12px; text-transform: uppercase; letter-spacing: 0.12em; font-size: 0.8rem; font-weight:700;">Sistem digital kampus</p>
                    <h1>Layanan Pelaporan Fasilitas Rusak</h1>
                    <p>Platform terintegrasi untuk melaporkan kerusakan fasilitas akademik, memantau status penanganan, dan memastikan proses verifikasi berjalan sesuai hak akses masing-masing role.</p>
                    <div class="hero-actions">
                        <a class="button primary" href="{{ route('register') }}">Daftar akun</a>
                        <a class="button secondary" href="{{ route('login') }}">Masuk sistem</a>
                        <a class="button secondary" href="{{ route('ui.preview') }}">Preview UI</a>
                    </div>
                </div>
                <div class="card">
                    <h3 style="margin-top:0;">Fitur utama</h3>
                    <ul class="status-list">
                        <li><span>Pelaporan fasilitas rusak</span><strong>?</strong></li>
                        <li><span>Hasil AI/OpenCV</span><strong>?</strong></li>
                        <li><span>Verifikasi admin & Kaprodi</span><strong>?</strong></li>
                        <li><span>Penugasan petugas</span><strong>?</strong></li>
                        <li><span>Riwayat status & notifikasi</span><strong>?</strong></li>
                        <li><span>Peta lokasi real-time</span><strong>?</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="page" style="padding-top: 12px;">
        <div class="container">
            <div class="stats">
                <div class="stat-box">
                    <div class="stat-label">Peran</div>
                    <div class="stat-value">4</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Alur kerja</div>
                    <div class="stat-value">7</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Entri utama</div>
                    <div class="stat-value">11</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Status</div>
                    <div class="stat-value">6</div>
                </div>
            </div>
        </div>
    </section>
@endsection
