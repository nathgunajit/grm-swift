<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Demo SMS gateway log — every message the (demo) gateway "sends" is stored here
    // so it can be inspected in the admin panel. Swap SmsService for a real gateway later.
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('mobile', 20);
            $table->text('message');
            $table->string('purpose')->nullable();   // e.g. registered / resolved
            $table->string('status')->default('sent (demo)');
            $table->string('gateway')->default('demo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
