<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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

        $functionIdsByName = [];
        $usedSlugs = [];

        DB::table('tools')
            ->select(['id', 'function'])
            ->orderBy('id')
            ->chunkById(100, function ($tools) use (&$functionIdsByName, &$usedSlugs): void {
                foreach ($tools as $tool) {
                    $name = trim($tool->function);

                    if ($name === '') {
                        continue;
                    }

                    if (! isset($functionIdsByName[$name])) {
                        $baseSlug = Str::slug($name);

                        if ($baseSlug === '') {
                            $baseSlug = 'function';
                        }

                        $slug = $baseSlug;

                        if (isset($usedSlugs[$slug])) {
                            $baseSlug .= '-'.substr(hash('sha256', $name), 0, 8);
                            $slug = $baseSlug;
                            $suffix = 2;

                            while (isset($usedSlugs[$slug])) {
                                $slug = $baseSlug.'-'.$suffix++;
                            }
                        }

                        $now = now();
                        $functionIdsByName[$name] = DB::table('functions')->insertGetId([
                            'name' => $name,
                            'slug' => $slug,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                        $usedSlugs[$slug] = true;
                    }

                    DB::table('tool_function')->insert([
                        'tool_id' => $tool->id,
                        'function_id' => $functionIdsByName[$name],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });

        Schema::table('tools', function (Blueprint $table) {
            $table->dropColumn('function');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->string('function')->nullable();
        });

        Schema::dropIfExists('tool_function');
        Schema::dropIfExists('functions');
    }
};
