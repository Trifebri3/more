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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('cascade');
            $table->foreignId('stylist_id')->constrained('stylists')->onDelete('cascade');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->integer('duration'); // in minutes
            $table->decimal('total_price', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('final_price', 10, 2);
            $table->string('status')->default('booked'); // booked, checked_in, in_progress, completed, cancelled, no_show
            $table->json('service_ids'); // array of service ids
            $table->string('source')->default('online'); // online, kiosk, manual
            $table->string('qr_code_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
