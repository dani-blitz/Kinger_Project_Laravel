<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Название сервера
            $table->string('ip');              // IP адрес
            $table->integer('port')->default(7787); // Порт
            $table->string('map')->nullable(); // Текущая карта
            $table->integer('players')->default(0); // Игроков онлайн
            $table->integer('max_players')->default(100); // Максимум игроков
            $table->enum('status', ['online', 'offline', 'maintenance'])->default('offline');
            $table->text('description')->nullable(); // Описание
            $table->integer('order')->default(0); // Сортировка
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servers');
    }
};
