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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('symptoms');
            $table->date('preferred_date');
            $table->enum('preferred_time', ['morning', 'afternoon', 'all_day']);
            $table->unsignedSmallInteger('animal_age_months');

            $table->foreignId('animal_id')->constrained();

            $table->foreignId('receptionist_id')->nullable()->constrained('users');
            $table->foreignId('medic_id')->nullable()->constrained('users');
            $table->timestamp('assigned_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
