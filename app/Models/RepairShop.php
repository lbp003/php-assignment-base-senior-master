<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairShop extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'latitude',
        'longitude',
    ];

    /**
     * The customers that belong to the repair shop.
     */
    public function customers()
    {
        return $this->belongsToMany(Customer::class)->withTimestamps();
    }
}
