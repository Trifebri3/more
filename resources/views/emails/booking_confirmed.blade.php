<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F5F2EB;
            color: #1C1B1A;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #E5E1D8;
        }
        .header {
            background-color: #1C1B1A;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 2px;
            color: #1E6EBD;
            font-weight: 900;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }
        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #F0ECE3;
        }
        .details-table td:first-child {
            color: #8C8880;
            font-weight: 500;
            width: 150px;
        }
        .details-table td:last-child {
            font-weight: bold;
        }
        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #FDFBF7;
            border-radius: 12px;
            border: 1px dashed #1E6EBD;
        }
        .qr-code {
            width: 150px;
            height: 150px;
            margin-bottom: 10px;
        }
        .code-text {
            font-size: 18px;
            font-weight: 900;
            color: #1E6EBD;
            letter-spacing: 1px;
        }
        .footer {
            background-color: #FDFBF7;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #8C8880;
            border-top: 1px solid #E5E1D8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>MORE</h1>
            <p style="margin: 5px 0 0 0; font-size: 12px; text-transform: uppercase; tracking: 1px; color: #8C8880;">Re-define Your Grooming Experience</p>
        </div>
        <div class="content">
            <div class="greeting">Halo {{ $customer->full_name }},</div>
            <p>Booking lo di MORE Barber telah terkonfirmasi! Berikut adalah ringkasan jadwal dan detail pesanan lo:</p>
            
            <table class="details-table">
                <tr>
                    <td>Kode Booking</td>
                    <td class="code-text">{{ $booking->booking_code }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Waktu</td>
                    <td>{{ $booking->booking_time }} WIB</td>
                </tr>
                <tr>
                    <td>Total Bayar</td>
                    <td>Rp {{ number_format($booking->final_price, 0, ',', '.') }}</td>
                </tr>
            </table>

            <div class="qr-section">
                <p style="margin: 0 0 15px 0; font-weight: bold; font-size: 14px;">TIKET QR CODE LO</p>
                <img class="qr-code" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $booking->booking_code }}" alt="QR Code">
                <div class="code-text">{{ $booking->booking_code }}</div>
                <p style="margin: 10px 0 0 0; font-size: 11px; color: #8C8880;">Tunjukkan QR Code ini kepada staff saat lo tiba di outlet.</p>
            </div>

            <p style="margin-top: 30px;">Vibe check seru, potong rambut super premium, dan kopi gratis menanti lo. Sampai jumpa di outlet, sob!</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 MORE Barber System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
