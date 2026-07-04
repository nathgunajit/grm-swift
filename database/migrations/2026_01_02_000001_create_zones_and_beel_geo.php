<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
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

        Schema::table('beels', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('cpiu_id');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('beels', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
        Schema::table('cpius', function (Blueprint $table) {
            $table->dropConstrainedForeignId('zone_id');
        });
        Schema::dropIfExists('zones');
    }
};
