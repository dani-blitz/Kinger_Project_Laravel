<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerificationCode extends Model
{
    protected $fillable = ['email', 'code', 'is_verified', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];
}
