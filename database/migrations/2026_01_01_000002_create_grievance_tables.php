<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grievance_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('code')->nullable();
            $table->string('name');
            $table->boolean('is_sensitive')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('grievances', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_id')->unique();
            $table->string('acknowledgment_no')->nullable()->unique();
            $table->string('mode_of_receipt')->default('online'); // online/verbal/written/phone/drop-box/meeting
            $table->foreignId('category_id')->nullable()->constrained('grievance_categories')->nullOnDelete();

            // Complainant details (may be blank when anonymous)
            $table->string('name')->nullable();
            $table->string('gender')->nullable();
            $table->integer('age')->nullable();
            $table->string('caste')->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('place_village')->nullable();
            $table->foreignId('beel_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();

            $table->text('description');
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_confidential')->default(false);
            $table->boolean('is_sensitive')->default(false);

            $table->string('status')->default('registered'); // registered/under_review/escalated/resolved/closed/reopened
            $table->unsignedTinyInteger('current_level')->default(1); // 1,2,3
            $table->timestamp('due_at')->nullable();
            $table->text('resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('registered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('grievance_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grievance_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->string('mime')->nullable();
            $table->timestamps();
        });

        Schema::create('grievance_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grievance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // registered/acknowledged/reviewed/commented/escalated/assigned/resolved/reopened/closed
            $table->unsignedTinyInteger('from_level')->nullable();
            $table->unsignedTinyInteger('to_level')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('grievance_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grievance_id')->constrained()->cascadeOnDelete();
            $table->boolean('informed')->nullable();
            $table->boolean('heard_respectfully')->nullable();
            $table->boolean('response_time_ok')->nullable();
            $table->string('satisfaction')->nullable();       // fully/partly/not
            $table->string('transparency')->nullable();       // good/average/poor
            $table->string('official_behavior')->nullable();  // good/average/poor
            $table->boolean('feel_safe')->nullable();
            $table->integer('rating')->nullable();            // 1-5 overall
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grievance_feedback');
        Schema::dropIfExists('grievance_actions');
        Schema::dropIfExists('grievance_documents');
        Schema::dropIfExists('grievances');
        Schema::dropIfExists('grievance_categories');
    }
};
