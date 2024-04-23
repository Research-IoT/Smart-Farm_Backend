<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DevicesBackup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'devices_backup';
    protected $fillable = [
        '',
    ];

    protected $hidden = [
        'id',
        'updated_at',
        'created_at'
    ];
}
