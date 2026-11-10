<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'facility_id',
        'title',
        'description',
        'urgency',
        'status',
        'location_detail',
        'latitude',
        'longitude',
        'resolved_at',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'resolved_at' => 'datetime',
    ];

    public static function statusOptions(): array
    {
        return ['pending', 'assigned', 'in_progress', 'verified', 'resolved', 'rejected'];
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function photos()
    {
        return $this->hasMany(ReportPhoto::class);
    }

    public function aiAnalysis()
    {
        return $this->hasOne(ReportAiAnalysis::class);
    }

    public function verifications()
    {
        return $this->hasMany(ReportVerification::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(ReportStatusHistory::class)->orderBy('created_at');
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
