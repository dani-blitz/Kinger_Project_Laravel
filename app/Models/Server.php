<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = [
        'name', 'ip', 'port', 'map', 'players', 'max_players',
        'status', 'description', 'order'
    ];

    protected $casts = [
        'players' => 'integer',
        'max_players' => 'integer',
        'order' => 'integer',
    ];
}
