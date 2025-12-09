<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('title');
            $table->string('slug')->unique();

            // Order of the lesson inside a course
            $table->unsignedInteger('position')->default(1);

            // Type of lesson
            $table->enum('type', ['video', 'document']);

            // For video lessons (stored in local storage)
            $table->string('video_url')->nullable();

            // For document lessons
            $table->string('document_path')->nullable();  // storage path

            // Optional extra info
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->boolean('is_preview')->default(false); // free preview lesson?

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
