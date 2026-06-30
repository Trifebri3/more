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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('whatsapp')->unique();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable(); // male, female
            $table->string('membership_status')->default('regular'); // regular, silver, gold, platinum
            $table->integer('loyalty_points')->default(0);
            $table->integer('total_visits')->default(0);
            $table->decimal('total_spending', 12, 2)->default(0.00);
            $table->timestamp('last_visit_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
