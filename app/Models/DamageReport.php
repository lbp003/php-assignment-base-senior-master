<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    use HasFactory;

    const STATE_NEW = 'new';
    const STATE_APPROVED = 'approved';
    const STATE_REJECTED = 'rejected';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'created_by',
        'state_by',
        'damage_report_number',
        'description',
        'latitude',
        'longitude',
        'date',
        'state',
        'reason',
    ];

    /**
     * Get the created user that owns the damage report.
     */
    public function createdUser()
    {
        return $this->belongsTo(User::class, 'id', 'created_by');
    }

    /**
     * Get the approved user that owns the damage report.
     */
    public function approvedUser()
    {
        return $this->belongsTo(User::class, 'id', 'state_by');
    }

    /**
     * Get the approved user that owns the damage report.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
