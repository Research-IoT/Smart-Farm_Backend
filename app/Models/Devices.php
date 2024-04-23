<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Devices extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'devices';
    protected $fillable = [
        'name',
        'category',
        'population',
        'status',
        'automatic',
        'relay_a',
        'relay_b'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    protected $casts = [
        'automatic' =>'boolean',
        'relay_a' =>'boolean',
        'relay_b' =>'boolean'
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function sensor()
    {
        return $this->hasMany(DevicesSensors::class);
    }
}
