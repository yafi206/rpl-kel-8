<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Facility;
use App\Models\FacilityType;
use App\Models\Notification;
use App\Models\Report;
use App\Models\ReportAiAnalysis;
use App\Models\ReportPhoto;
use App\Models\ReportStatusHistory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'mahasiswa', 'description' => 'Pelapor fasilitas rusak'],
            ['name' => 'admin', 'description' => 'Administrator sistem'],
            ['name' => 'petugas', 'description' => 'Petugas teknis'],
            ['name' => 'kaprodi', 'description' => 'Kepala program studi'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(['name' => $roleData['name']], $roleData);
        }

        $adminRole = Role::where('name', 'admin')->first();
        $studentRole = Role::where('name', 'mahasiswa')->first();
        $technicianRole = Role::where('name', 'petugas')->first();
        $kaprodiRole = Role::where('name', 'kaprodi')->first();

        $admin = User::firstOrCreate(
            ['email' => 'admin@kampus.test'],
            [
                'name' => 'Admin Kampus',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id,
                'department' => 'Admin',
                'phone' => '081200000001',
                'is_active' => true,
            ]
        );

        $student = User::firstOrCreate(
            ['email' => 'mahasiswa@kampus.test'],
            [
                'name' => 'Mahasiswa Demo',
                'password' => Hash::make('password123'),
                'role_id' => $studentRole->id,
                'student_id' => '2023101001',
                'department' => 'Teknik Informatika',
                'phone' => '081200000002',
                'is_active' => true,
            ]
        );

        $technician = User::firstOrCreate(
            ['email' => 'petugas@kampus.test'],
            [
                'name' => 'Petugas Teknis',
                'password' => Hash::make('password123'),
                'role_id' => $technicianRole->id,
                'department' => 'Maintenance',
                'phone' => '081200000003',
                'is_active' => true,
            ]
        );

        $kaprodi = User::firstOrCreate(
            ['email' => 'kaprodi@kampus.test'],
            [
                'name' => 'Kaprodi Demo',
                'password' => Hash::make('password123'),
                'role_id' => $kaprodiRole->id,
                'department' => 'Teknik Informatika',
                'phone' => '081200000004',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'yafia@kampus.test'],
            [
                'name' => 'yafia',
                'password' => Hash::make('123'),
                'role_id' => $studentRole->id,
                'student_id' => '2023101011',
                'department' => 'Teknik Informatika',
                'phone' => '08123456789',
                'is_active' => true,
            ]
        );

        $facilityType = FacilityType::firstOrCreate(['name' => 'Listrik'], ['description' => 'Fasilitas kelistrikan']);
        $facility = Facility::firstOrCreate(
            ['name' => 'Lampu Koridor A'],
            [
                'facility_type_id' => $facilityType->id,
                'building' => 'Gedung A',
                'room' => 'Koridor LT1',
                'floor' => '1',
                'latitude' => -6.200123,
                'longitude' => 106.816456,
                'status' => 'damaged',
            ]
        );

        $report = Report::firstOrCreate(
            ['title' => 'Lampu koridor tidak menyala'],
            [
                'reporter_id' => $student->id,
                'facility_id' => $facility->id,
                'description' => 'Lampu di koridor gedung A mati dan beberapa titik berdenyut. Kondisi ini membahayakan keamanan mahasiswa saat malam hari.',
                'urgency' => 'tinggi',
                'status' => 'assigned',
                'location_detail' => 'Koridor Gedung A, lantai 1',
                'latitude' => -6.200123,
                'longitude' => 106.816456,
            ]
        );

        ReportStatusHistory::firstOrCreate(
            ['report_id' => $report->id, 'new_status' => 'pending'],
            ['old_status' => null, 'changed_by_user_id' => $student->id, 'notes' => 'Laporan baru dibuat']
        );

        ReportStatusHistory::firstOrCreate(
            ['report_id' => $report->id, 'new_status' => 'assigned'],
            ['old_status' => 'pending', 'changed_by_user_id' => $admin->id, 'notes' => 'Petugas teknis ditugaskan']
        );

        Assignment::firstOrCreate(
            ['report_id' => $report->id],
            ['assigned_to_user_id' => $technician->id, 'assigned_by_user_id' => $admin->id, 'notes' => 'Periksa rangkaian listrik dan ganti lampu']
        );

        ReportAiAnalysis::firstOrCreate(
            ['report_id' => $report->id],
            ['summary' => 'AI/OpenCV menilai ada tanda-tanda pencahayaan yang tidak berfungsi dengan tingkat risiko sedang.', 'confidence_score' => 84, 'risk_level' => 'sedang', 'recommendation' => 'Periksa titik sambungan kabel dan ganti lampu cadangan.']
        );

        Notification::firstOrCreate(
            ['user_id' => $technician->id, 'report_id' => $report->id, 'title' => 'Tugas baru'],
            ['message' => 'Anda memiliki tugas perbaikan lampu koridor.', 'type' => 'assignment', 'is_read' => false]
        );

        ReportPhoto::firstOrCreate(
            ['report_id' => $report->id],
            ['path' => 'reports/demo-lampu.jpg', 'caption' => 'Foto kerusakan lampu', 'is_primary' => true]
        );
    }
}
