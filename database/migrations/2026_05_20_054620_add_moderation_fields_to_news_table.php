<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('description');
            $table->foreignId('moderated_by')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            $table->text('moderation_comment')->nullable()->after('moderated_by');
            $table->timestamp('moderated_at')->nullable()->after('moderation_comment');
        });
    }

    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['moderated_by']);
            $table->dropColumn(['status', 'moderated_by', 'moderation_comment', 'moderated_at']);
        });
    }
};
