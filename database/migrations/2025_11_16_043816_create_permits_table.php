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
        Schema::create('permits', function (Blueprint $table) {
            $table->id();
            $table->string('permit_number')->unique();
            $table->string('type')->default('work'); // work, hot_work, confined_space, electrical, etc.
            $table->string('title');
            $table->text('description');
            $table->text('work_description');
            $table->string('location');
            $table->dateTime('planned_start');
            $table->dateTime('planned_end');
            $table->dateTime('actual_start')->nullable();
            $table->dateTime('actual_end')->nullable();
            $table->string('status')->default('draft'); // draft, pending_approval, approved, active, suspended, closed, cancelled
            $table->text('hazards_identified')->nullable();
            $table->text('control_measures')->nullable();
            $table->text('ppe_required')->nullable();
            $table->text('emergency_procedures')->nullable();
            $table->json('approvals')->nullable(); // JSON array of approval steps
            $table->json('attachments')->nullable();
            $table->text('closure_notes')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->unsignedBigInteger('closed_by')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('issued_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('closed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permits');
    }
};
