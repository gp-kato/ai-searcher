<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('functions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('tool_function', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')
                ->constrained('tools')
                ->cascadeOnDelete();
            $table->foreignId('function_id')
                ->constrained('functions')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['tool_id', 'function_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_function');
        Schema::dropIfExists('functions');
    }
};
