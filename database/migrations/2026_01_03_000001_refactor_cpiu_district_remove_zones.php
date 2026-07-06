<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A CPIU now covers many districts (each district maps to one CPIU).
        Schema::table('districts', function (Blueprint $table) {
            $table->foreignId('cpiu_id')->nullable()->after('code')->constrained()->nullOnDelete();
        });

        // Zone Management removed.
        if (Schema::hasColumn('cpius', 'zone_id')) {
            Schema::table('cpius', function (Blueprint $table) {
                $table->dropConstrainedForeignId('zone_id');
            });
        }
        Schema::dropIfExists('zones');
    }

    public function down(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::table('cpius', function (Blueprint $table) {
            $table->foreignId('zone_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });
        Schema::table('districts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cpiu_id');
        });
    }
};
