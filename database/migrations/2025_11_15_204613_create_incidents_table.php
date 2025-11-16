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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_number')->unique();
            $table->enum('type', ['injury', 'property_damage', 'near_miss', 'environmental', 'other']);
            $table->enum('severity', ['minor', 'moderate', 'major', 'critical']);
            $table->string('title');
            $table->text('description');
            $table->dateTime('incident_date');
            $table->string('location');
            $table->json('affected_parties')->nullable(); // Names of people involved
            $table->json('witnesses')->nullable(); // Names of witnesses
            $table->text('immediate_actions')->nullable();
            $table->enum('status', ['open', 'investigating', 'closed'])->default('open');
            $table->text('root_cause')->nullable();
            $table->text('corrective_actions')->nullable();
            $table->text('preventive_actions')->nullable();
            $table->dateTime('closure_date')->nullable();
            $table->json('attachments')->nullable(); // File paths
            $table->unsignedBigInteger('reported_by');
            $table->unsignedBigInteger('investigated_by')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->timestamps();

            $table->foreign('reported_by')->references('id')->on('users');
            $table->foreign('investigated_by')->references('id')->on('users')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
