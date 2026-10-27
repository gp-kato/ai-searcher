<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('thumbnail');
        });

        Schema::create('tool_favorites', function (Blueprint $table) {
            $table->id();
            $table->string('browser_id', 36);
            $table->foreignId('tool_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['browser_id', 'tool_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_favorites');

        Schema::table('tools', function (Blueprint $table) {
            $table->dropColumn('is_verified');
        });
    }
};
