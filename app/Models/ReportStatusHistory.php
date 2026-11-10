<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'report_status_history';

    protected $fillable = ['report_id', 'old_status', 'new_status', 'changed_by_user_id', 'notes'];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
