<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['report_id', 'path', 'caption', 'is_primary'];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
