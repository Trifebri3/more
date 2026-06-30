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
        Schema::create('walk_ins', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('whatsapp');
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->foreignId('stylist_id')->nullable()->constrained('stylists')->nullOnDelete();
            $table->string('queue_number');
            $table->string('status')->default('waiting'); // waiting, serving, completed, skipped
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walk_ins');
    }
};
