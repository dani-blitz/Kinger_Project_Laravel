<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedEmailLog extends Model
{
    protected $table = 'failed_email_logs';

    protected $fillable = [
        'email',
        'code',
        'error_message',
        'queue_name',
        'attempts',
        'failed_at'
    ];

    protected $casts = [
        'failed_at' => 'datetime',
        'attempts' => 'integer'
    ];
}
