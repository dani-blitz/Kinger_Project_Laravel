<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('failed_code_errors', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('code_entered', 6); // введённый код
            $table->string('expected_code', 6)->nullable(); // ожидаемый код
            $table->string('error_type'); // invalid, expired, wrong_email, format
            $table->text('error_message');
            $table->string('ip_address')->nullable();
            $table->timestamp('failed_at');
            $table->timestamps();

            // Индексы
            $table->index('email');
            $table->index('error_type');
            $table->index('failed_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('failed_code_errors');
    }
};
