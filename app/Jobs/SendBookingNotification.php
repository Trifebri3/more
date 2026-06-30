<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Mail\BookingConfirmedMail;

class SendBookingNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $booking;
    public $customer;

    /**
     * Create a new job instance.
     */
    public function __construct(Booking $booking, Customer $customer)
    {
        $this->booking = $booking;
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Send Email Notification if email is provided
        if ($this->customer->email) {
            try {
                Mail::to($this->customer->email)->send(new BookingConfirmedMail($this->booking, $this->customer));
                Log::info("Email Booking Confirmation sent to: {$this->customer->email}");
            } catch (\Throwable $e) {
                Log::error("Failed to send email confirmation: " . $e->getMessage());
            }
        }

        // 2. Send WhatsApp Notification
        try {
            $this->sendWhatsAppNotification();
        } catch (\Throwable $e) {
            Log::error("Failed to send WhatsApp confirmation: " . $e->getMessage());
        }
    }

    protected function sendWhatsAppNotification()
    {
        $phone = $this->customer->whatsapp;
        $code = $this->booking->booking_code;
        $date = $this->booking->booking_date;
        $time = $this->booking->booking_time;
        $price = number_format($this->booking->final_price, 0, ',', '.');
        
        $serviceIds = $this->booking->service_ids ?? [];
        $services = \App\Models\Service::whereIn('id', $serviceIds)->pluck('name')->join(', ');
        if (empty($services)) {
            $services = 'Layanan Haircut';
        }
        
        $message = "Halo *{$this->customer->full_name}*,\n\n"
            . "Booking lo di *MORE* Barber telah dikonfirmasi! 🚀\n\n"
            . "🎫 *Kode Booking*: {$code}\n"
            . "📅 *Tanggal*: {$date}\n"
            . "⏰ *Waktu*: {$time} WIB\n"
            . "✂️ *Layanan*: {$services}\n"
            . "💵 *Total*: Rp {$price}\n\n"
            . "Tunjukkan tiket QR code lo saat tiba di lokasi. Jangan sampai terlambat ya! Vibe check & kopi premium gratis menanti lo.\n\n"
            . "Redefine Your Grooming, \n*MORE Barber Team*";

        // Call WhatsApp API (e.g. Fonnte API as default gateway)
        $whatsappApiUrl = env('WHATSAPP_API_URL', 'https://api.fonnte.com/send');
        $token = env('WHATSAPP_API_TOKEN', 'mock-token-123456');

        // Send request
        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->post($whatsappApiUrl, [
            'target' => $phone,
            'message' => $message,
            'countryCode' => '62',
        ]);

        Log::info("WhatsApp Booking Confirmation requested for: {$phone}. Response Status: " . $response->status());
        Log::debug("WhatsApp Message: \n" . $message);
    }
}
