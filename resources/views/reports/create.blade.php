@extends('layouts.app')

@section('title', 'Buat Laporan')

@section('content')
    <div class="page">
        <div class="container forms">
            <div class="form-card">
                <h2 style="margin-top:0; margin-bottom: 8px; font-size: 2rem;">Buat laporan fasilitas rusak</h2>
                <p class="muted" style="margin-bottom: 24px;">Isi lokasi, detail kerusakan, dan lampirkan foto agar proses verifikasi lebih cepat.</p>

                @if ($errors->any())
                    <div class="alert error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="field-grid">
                        <div class="form-row">
                            <label class="field-label" for="title">Judul laporan</label>
                            <input id="title" name="title" value="{{ old('title') }}" required>
                        </div>
                        <div class="form-row">
                            <label class="field-label" for="urgency">Urgensi</label>
                            <select id="urgency" name="urgency" required>
                                <option value="rendah" {{ old('urgency') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="sedang" {{ old('urgency') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="tinggi" {{ old('urgency') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                <option value="darurat" {{ old('urgency') == 'darurat' ? 'selected' : '' }}>Darurat</option>
                            </select>
                        </div>
                    </div>

                    <div class="field-grid">
                        <div class="form-row">
                            <label class="field-label" for="facility_name">Nama fasilitas</label>
                            <input id="facility_name" name="facility_name" value="{{ old('facility_name') }}" required>
                        </div>
                        <div class="form-row">
                            <label class="field-label" for="facility_type">Tipe fasilitas</label>
                            <select id="facility_type" name="facility_type">
                                @foreach ($facilityTypes as $type)
                                    <option value="{{ $type->name }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="field-grid">
                        <div class="form-row">
                            <label class="field-label" for="building">Gedung</label>
                            <input id="building" name="building" value="{{ old('building') }}" required>
                        </div>
                        <div class="form-row">
                            <label class="field-label" for="room">Ruangan</label>
                            <input id="room" name="room" value="{{ old('room') }}">
                        </div>
                    </div>

                    <div class="field-grid">
                        <div class="form-row">
                            <label class="field-label" for="floor">Lantai</label>
                            <input id="floor" name="floor" value="{{ old('floor') }}">
                        </div>
                        <div class="form-row">
                            <label class="field-label" for="location_detail">Detail lokasi</label>
                            <input id="location_detail" name="location_detail" value="{{ old('location_detail') }}">
                        </div>
                    </div>

                    <div class="field-grid">
                        <div class="form-row">
                            <label class="field-label" for="latitude">Latitude</label>
                            <input id="latitude" name="latitude" type="number" step="any" value="{{ old('latitude') }}">
                        </div>
                        <div class="form-row">
                            <label class="field-label" for="longitude">Longitude</label>
                            <input id="longitude" name="longitude" type="number" step="any" value="{{ old('longitude') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="field-label" for="description">Detail kerusakan</label>
                        <textarea id="description" name="description" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="form-row">
                        <label class="field-label" for="photo">Foto kerusakan</label>
                        <input id="photo" name="photo" type="file" accept="image/*">
                    </div>

                    <div class="mt-16">
                        <p class="muted" style="margin-bottom: 8px; font-weight: 700;">Peta lokasi</p>
                        <div id="map" class="map-box"></div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="button primary">Kirim laporan</button>
                        <a href="{{ route('dashboard') }}" class="button secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([-6.2, 106.8], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker([-6.2, 106.8]).addTo(map);

        map.on('click', function (event) {
            const { lat, lng } = event.latlng;
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
            marker.setLatLng([lat, lng]);
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 16);
                marker.setLatLng([lat, lng]);
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
            });
        }
    </script>
@endsection
