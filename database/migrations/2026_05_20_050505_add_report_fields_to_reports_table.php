<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('player_name')->after('title');
            $table->string('player_steam_id')->nullable()->after('player_name');
            $table->string('server_name')->nullable()->after('player_steam_id');
            $table->string('evidence_link')->nullable()->after('server_name');
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['player_name', 'player_steam_id', 'server_name', 'evidence_link']);
        });
    }
};
