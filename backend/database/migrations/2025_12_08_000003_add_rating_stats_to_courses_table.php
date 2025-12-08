<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 2)->default(0)->after('language');
            $table->unsignedInteger('ratings_count')->default(0)->after('average_rating');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['average_rating', 'ratings_count']);
        });
    }
};
