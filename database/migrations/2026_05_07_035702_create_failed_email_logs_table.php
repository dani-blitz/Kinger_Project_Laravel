<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('failed_email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('code', 6);
            $table->text('error_message');
            $table->string('queue_name')->default('failed');
            $table->integer('attempts')->default(1);
            $table->timestamp('failed_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('failed_email_logs');
    }
};
