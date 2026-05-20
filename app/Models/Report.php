<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
    protected $fillable = [
        'title', 'description', 'priority', 'status',
        'player_name', 'player_steam_id', 'server_name',
        'evidence_link', 'user_id'
    ];

    public function comments()
    {
        return $this->hasMany(ReportComment::class)->orderBy('id', 'asc');
    }
}
