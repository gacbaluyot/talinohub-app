<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('course_comment_likes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('course_comment_id')
                ->constrained()
                ->onDelete('cascade');

            $table->timestamp('liked_at')->useCurrent();

            // Prevent duplicate likes
            $table->unique(['user_id', 'course_comment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_comment_likes');
    }
};
