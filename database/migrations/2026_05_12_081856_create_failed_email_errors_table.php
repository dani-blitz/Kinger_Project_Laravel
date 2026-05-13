<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('failed_email_errors', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('code', 6);
            $table->string('error_type'); // smtp, connection, auth, timeout, unknown
            $table->text('error_message');
            $table->integer('attempts')->default(1);
            $table->timestamp('failed_at');
            $table->timestamps();

            // Индексы для быстрого поиска
            $table->index('email');
            $table->index('error_type');
            $table->index('failed_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('failed_email_errors');
    }
};
