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
        Schema::create('stylist_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stylist_id')->constrained('stylists')->onDelete('cascade');
            $table->date('date');
            $table->time('start_time')->default('09:00');
            $table->time('end_time')->default('21:00');
            $table->string('status')->default('available'); // available, off, leave
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stylist_schedules');
    }
};
