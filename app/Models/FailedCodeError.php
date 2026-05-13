<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedCodeError extends Model
{
    protected $table = 'failed_code_errors';

    protected $fillable = [
        'email', 'code_entered', 'expected_code', 'error_type', 'error_message', 'ip_address', 'failed_at'
    ];

    protected $casts = [
        'failed_at' => 'datetime'
    ];
}
