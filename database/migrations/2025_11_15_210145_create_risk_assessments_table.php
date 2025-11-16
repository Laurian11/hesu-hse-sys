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
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->string('assessment_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->string('risk_level')->default('low'); // low, medium, high, critical
            $table->string('probability')->default('low'); // low, medium, high
            $table->string('impact')->default('low'); // low, medium, high
            $table->text('mitigation_plan')->nullable();
            $table->text('residual_risk')->nullable();
            $table->string('status')->default('draft'); // draft, pending_review, approved, rejected
            $table->date('assessment_date');
            $table->date('review_date')->nullable();
            $table->json('attachments')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_assessments');
    }
};
