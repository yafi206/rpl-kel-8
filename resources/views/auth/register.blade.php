@extends('layouts.app')

@section('title', 'Daftar - Laporan Fasilitas')

@section('content')
    <div class="page auth-shell">
        <div class="auth-grid">
            <div class="auth-card">
                <div class="soft-chip">Register</div>

                @if ($errors->any())
                    <div class="muted" style="margin-top:12px; font-size:0.75rem; color:#fff5f5;">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    <div class="field-wrap">
                        <label class="field-label" for="name">Nama</label>
                        <input class="text-input" id="name" name="name" value="{{ old('name') }}" placeholder="Nama" required>
                    </div>

                    <div class="field-wrap">
                        <label class="field-label" for="email">Email</label>
                        <input class="text-input" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Email" required>
                    </div>

                    <div class="field-wrap">
                        <label class="field-label" for="password">Password</label>
                        <input class="text-input" id="password" name="password" type="password" placeholder="Password" required>
                    </div>

                    <button class="primary-btn" type="submit">Sign Up</button>
                </form>

                <a class="auth-link" href="{{ route('login') }}">Kembali ke Login Page</a>
            </div>

            <div class="auth-card auth-card--success">
                <div class="success-circle">✓</div>
                <div class="success-title">Successful</div>
                <div class="success-meta">Kembali ke Login Page</div>
            </div>
        </div>
    </div>
@endsection
