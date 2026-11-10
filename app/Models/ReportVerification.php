<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportVerification extends Model
{
    use HasFactory;

    protected $fillable = ['report_id', 'verifier_id', 'verdict', 'notes'];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }
}
