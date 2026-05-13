<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedEmailError extends Model
{
    protected $table = 'failed_email_errors';

    protected $fillable = [
        'email', 'code', 'error_type', 'error_message', 'attempts', 'failed_at'
    ];

    protected $casts = [
        'failed_at' => 'datetime',
        'attempts' => 'integer'
    ];
}
