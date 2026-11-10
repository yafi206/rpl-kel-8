<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_type_id',
        'name',
        'building',
        'room',
        'floor',
        'latitude',
        'longitude',
        'status',
    ];

    public function type()
    {
        return $this->belongsTo(FacilityType::class, 'facility_type_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
