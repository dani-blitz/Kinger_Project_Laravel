<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $fillable = ['user_id', 'permission', 'value'];

    protected $casts = [
        'value' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
