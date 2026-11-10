<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAiAnalysis extends Model
{
    use HasFactory;

    protected $table = 'report_ai_analysis';

    protected $fillable = [
        'report_id',
        'summary',
        'confidence_score',
        'risk_level',
        'recommendation',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
