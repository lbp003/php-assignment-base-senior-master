<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'damage_report_id',
        'filename',
        'disk',
        'path',
    ];

    /**
     * Get the damage report that owns the image.
     */
    public function damageReport()
    {
        return $this->belongsTo(DamageReport::class);
    }
}
