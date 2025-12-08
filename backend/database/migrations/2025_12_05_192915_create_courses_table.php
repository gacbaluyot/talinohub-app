<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            // Educator that owns the course
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('title');
            $table->string('slug')->unique();

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            // Free / Paid
            $table->boolean('is_free')->default(true);
            $table->decimal('price', 10, 2)->default(0);

            // Optional meta
            $table->string('thumbnail_path')->nullable();
            $table->string('category')->nullable();
            $table->string('level')->nullable();    // Beginner, Intermediate, etc.
            $table->string('language')->nullable();

            // Publish state
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
