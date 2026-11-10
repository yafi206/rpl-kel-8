<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_mahasiswa_can_create_valid_report(): void
    {
        $studentRole = Role::where('name', 'mahasiswa')->first();
        $student = User::factory()->create([
            'email' => 'student.test@example.com',
            'password' => bcrypt('password123'),
            'role_id' => $studentRole->id,
        ]);

        $response = $this->actingAs($student)->post('/reports', [
            'title' => 'Lampu koridor mati',
            'description' => 'Lampu di koridor gedung A tidak menyala sejak pukul 19.00. Kondisi ini mengganggu keamanan mahasiswa.',
            'facility_name' => 'Lampu Koridor A',
            'building' => 'Gedung A',
            'room' => 'Koridor LT1',
            'floor' => '1',
            'urgency' => 'tinggi',
            'location_detail' => 'Depan ruang kelas 101',
            'latitude' => -6.200123,
            'longitude' => 106.816456,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reports', ['title' => 'Lampu koridor mati']);
    }

    public function test_invalid_report_data_is_rejected(): void
    {
        $studentRole = Role::where('name', 'mahasiswa')->first();
        $student = User::factory()->create([
            'email' => 'invalid.student@example.com',
            'password' => bcrypt('password123'),
            'role_id' => $studentRole->id,
        ]);

        $response = $this->actingAs($student)->post('/reports', [
            'title' => '',
            'description' => 'Singkat',
            'facility_name' => 'Lampu',
            'building' => 'Gedung A',
            'urgency' => 'tinggi',
        ]);

        $response->assertSessionHasErrors(['title', 'description']);
    }

    public function test_special_characters_are_handled_as_safe_text(): void
    {
        $studentRole = Role::where('name', 'mahasiswa')->first();
        $student = User::factory()->create([
            'email' => 'edge.student@example.com',
            'password' => bcrypt('password123'),
            'role_id' => $studentRole->id,
        ]);

        $malicious = "DROP TABLE reports; -- OR '1'='1";

        $response = $this->actingAs($student)->post('/reports', [
            'title' => "Pecah' OR '1'='1",
            'description' => $malicious,
            'facility_name' => 'Kursi laboratorium',
            'building' => 'Gedung B',
            'room' => 'Lab 3',
            'floor' => '2',
            'urgency' => 'sedang',
            'location_detail' => 'Samping pintu utama',
            'latitude' => -6.210100,
            'longitude' => 106.823500,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reports', ['title' => "Pecah' OR '1'='1"]);
        $this->assertDatabaseHas('reports', ['description' => $malicious]);
    }
}
