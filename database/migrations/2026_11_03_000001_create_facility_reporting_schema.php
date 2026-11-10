<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('email')->constrained('roles');
            $table->string('student_id')->nullable()->after('role_id');
            $table->string('department')->nullable()->after('student_id');
            $table->string('phone')->nullable()->after('department');
            $table->boolean('is_active')->default(true)->after('phone');
        });

        Schema::create('facility_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_type_id')->nullable()->constrained('facility_types');
            $table->string('name');
            $table->string('building')->nullable();
            $table->string('room')->nullable();
            $table->string('floor')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users');
            $table->foreignId('facility_id')->constrained('facilities');
            $table->string('title');
            $table->text('description');
            $table->string('urgency')->default('sedang');
            $table->string('status')->default('pending');
            $table->text('location_detail')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('report_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('report_ai_analysis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->text('summary');
            $table->unsignedTinyInteger('confidence_score');
            $table->string('risk_level')->default('rendah');
            $table->text('recommendation')->nullable();
            $table->timestamps();
        });

        Schema::create('report_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->foreignId('verifier_id')->nullable()->constrained('users');
            $table->string('verdict')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('report_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users');
            $table->foreignId('assigned_by_user_id')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('report_id')->nullable()->constrained('reports')->nullOnDelete();
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('info');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('report_status_history');
        Schema::dropIfExists('report_verifications');
        Schema::dropIfExists('report_ai_analysis');
        Schema::dropIfExists('report_photos');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('facility_types');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role_id', 'student_id', 'department', 'phone', 'is_active']);
        });
        Schema::dropIfExists('roles');
    }
};
