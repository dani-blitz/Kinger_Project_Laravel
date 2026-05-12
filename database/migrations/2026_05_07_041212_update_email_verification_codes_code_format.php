<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('email_verification_codes', function (Blueprint $table) {
            // Проверяем, что code состоит только из 6 цифр
            $table->string('code', 6)->change();
        });
    }

    public function down()
    {
        Schema::table('email_verification_codes', function (Blueprint $table) {
            $table->string('code', 6)->change();
        });
    }
};
