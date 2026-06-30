<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Outlet;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\Stylist;
use App\Models\Customer;
use App\Models\Promotion;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Create Default Users (Roles: admin, stylist, staff, customer)
        $adminUser = User::create([
            'name' => 'Admin MORE',
            'email' => 'admin@more.id',
            'password' => Hash::make('password123'),
        ]);
        // Set role via name/metadata or just simulate user role checking.
        // We'll write role flags / access control helper later, but let's make roles clear.
        
        $staffUser = User::create([
            'name' => 'Budi Staff',
            'email' => 'staff@more.id',
            'password' => Hash::make('password123'),
        ]);

        $stylistUser = User::create([
            'name' => 'Alexandre Dumas',
            'email' => 'stylist@more.id',
            'password' => Hash::make('password123'),
        ]);

        $customerUser = User::create([
            'name' => 'Rian Wijaya',
            'email' => 'customer@more.id',
            'password' => Hash::make('password123'),
        ]);

        // 2. Create Outlets
        $gi = Outlet::create([
            'name' => 'MORE Grand Indonesia',
            'slug' => 'more-grand-indonesia',
            'address' => 'Grand Indonesia Shopping Town, East Mall, Level 3, Jl. M.H. Thamrin No.1, Jakarta Pusat',
            'phone' => '+62 21-23580001',
            'latitude' => -6.195221,
            'longitude' => 106.820297,
            'image_url' => 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?q=80&w=600&auto=format&fit=crop',
            'rating' => 4.90,
            'opening_hours' => [
                ['start' => '10:00', 'end' => '22:00'], // Sun
                ['start' => '10:00', 'end' => '22:00'], // Mon
                ['start' => '10:00', 'end' => '22:00'], // Tue
                ['start' => '10:00', 'end' => '22:00'], // Wed
                ['start' => '10:00', 'end' => '22:00'], // Thu
                ['start' => '10:00', 'end' => '22:00'], // Fri
                ['start' => '10:00', 'end' => '22:00'], // Sat
            ],
            'description' => 'Flagship outlet located in the heart of Jakarta. Redefining your grooming experience with premium tools and services.',
        ]);

        $sc = Outlet::create([
            'name' => 'MORE Senayan City',
            'slug' => 'more-senayan-city',
            'address' => 'Senayan City Mall, Level LG, Jl. Asia Afrika No.19, Jakarta Selatan',
            'phone' => '+62 21-72781002',
            'latitude' => -6.227122,
            'longitude' => 106.797434,
            'image_url' => 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?q=80&w=600&auto=format&fit=crop',
            'rating' => 4.85,
            'opening_hours' => [
                ['start' => '10:00', 'end' => '22:00'], // Sun
                ['start' => '10:00', 'end' => '22:00'],
                ['start' => '10:00', 'end' => '22:00'],
                ['start' => '10:00', 'end' => '22:00'],
                ['start' => '10:00', 'end' => '22:00'],
                ['start' => '10:00', 'end' => '22:00'],
                ['start' => '10:00', 'end' => '22:00'],
            ],
            'description' => 'Modern design meet classic hair cuts. Perfect for the south Jakarta urban lifestyle.',
        ]);

        $cp = Outlet::create([
            'name' => 'MORE Central Park',
            'slug' => 'more-central-park',
            'address' => 'Central Park Mall, Level LG, Jl. Letjen S. Parman No.28, Jakarta Barat',
            'phone' => '+62 21-56985003',
            'latitude' => -6.177402,
            'longitude' => 106.790693,
            'image_url' => 'https://images.unsplash.com/photo-1599351431247-f5094087e882?q=80&w=600&auto=format&fit=crop',
            'rating' => 4.75,
            'opening_hours' => [
                ['start' => '10:00', 'end' => '22:00'],
                ['start' => '10:00', 'end' => '22:00'],
                ['start' => '10:00', 'end' => '22:00'],
                ['start' => '10:00', 'end' => '22:00'],
                ['start' => '10:00', 'end' => '22:00'],
                ['start' => '10:00', 'end' => '22:00'],
                ['start' => '10:00', 'end' => '22:00'],
            ],
            'description' => 'Spacious lounge with premium coffee. Enjoy a relaxing define session with west Jakarta’s top stylists.',
        ]);

        // 3. Create Service Categories
        $haircutCat = ServiceCategory::create(['name' => 'Haircut', 'slug' => 'haircut', 'description' => 'Classic and modern scissor and clipper cuts.']);
        $coloringCat = ServiceCategory::create(['name' => 'Coloring', 'slug' => 'coloring', 'description' => 'Professional hair dye and bleaching.']);
        $smoothingCat = ServiceCategory::create(['name' => 'Smoothing', 'slug' => 'smoothing', 'description' => 'Keratin and chemical straightening treatments.']);
        $permingCat = ServiceCategory::create(['name' => 'Perming', 'slug' => 'perming', 'description' => 'Texturizing and permanent curls.']);
        $treatmentCat = ServiceCategory::create(['name' => 'Treatment', 'slug' => 'treatment', 'description' => 'Scalp massage, hair masking, and hair spa.']);

        // 4. Create Services
        Service::create([
            'category_id' => $haircutCat->id,
            'name' => 'MORE Signature Haircut',
            'slug' => 'more-signature-haircut',
            'description' => 'Our highly demanded cut. Includes consultation, double wash, warm towel service, professional haircut, blow dry, and styling with premium pomade.',
            'duration' => 45,
            'price' => 150000.00,
            'image_url' => 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?q=80&w=300&auto=format&fit=crop',
            'is_active' => true,
        ]);

        Service::create([
            'category_id' => $haircutCat->id,
            'name' => 'Buzz Cut / Fade Only',
            'slug' => 'buzz-cut-fade-only',
            'description' => 'Precision clipper cut focusing on side fades or standard short buzz cuts. Quick, sharp, and clean.',
            'duration' => 30,
            'price' => 100000.00,
            'image_url' => 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?q=80&w=300&auto=format&fit=crop',
            'is_active' => true,
        ]);

        Service::create([
            'category_id' => $coloringCat->id,
            'name' => 'Vinto Gray Coverage',
            'slug' => 'vinto-gray-coverage',
            'description' => 'Permanent root touch up or full gray coverage using natural-toned premium dyes.',
            'duration' => 90,
            'price' => 350000.00,
            'image_url' => 'https://images.unsplash.com/photo-1605497746444-ac9da58d7fc0?q=80&w=300&auto=format&fit=crop',
            'is_active' => true,
        ]);

        Service::create([
            'category_id' => $coloringCat->id,
            'name' => 'Creative Bleaching & Tinting',
            'slug' => 'creative-bleaching-tinting',
            'description' => 'Premium fashion hair coloring. Includes pre-bleaching and custom dye application.',
            'duration' => 150,
            'price' => 600000.00,
            'image_url' => 'https://images.unsplash.com/photo-1562322140-8baeececf3df?q=80&w=300&auto=format&fit=crop',
            'is_active' => true,
        ]);

        Service::create([
            'category_id' => $smoothingCat->id,
            'name' => 'Keratin Define Session',
            'slug' => 'keratin-define-session',
            'description' => 'Straightens, smoothens, and eliminates frizz using organic keratin complex formula.',
            'duration' => 120,
            'price' => 450000.00,
            'image_url' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?q=80&w=300&auto=format&fit=crop',
            'is_active' => true,
        ]);

        Service::create([
            'category_id' => $treatmentCat->id,
            'name' => 'Anti-Dandruff Scalp Treatment',
            'slug' => 'anti-dandruff-scalp-treatment',
            'description' => 'Purifying scrub, tonic application, and high-frequency ozone therapy to reduce dandruff and itchiness.',
            'duration' => 60,
            'price' => 200000.00,
            'image_url' => 'https://images.unsplash.com/photo-1519699047748-de8e457a634e?q=80&w=300&auto=format&fit=crop',
            'is_active' => true,
        ]);

        // 5. Create Stylists
        $stylistSchedule = [
            ['start' => '10:00', 'end' => '20:00'], // Sun
            ['start' => '10:00', 'end' => '20:00'], // Mon
            ['start' => '10:00', 'end' => '20:00'], // Tue
            ['start' => '10:00', 'end' => '20:00'], // Wed
            ['start' => '10:00', 'end' => '20:00'], // Thu
            ['start' => '10:00', 'end' => '20:00'], // Fri
            ['start' => '10:00', 'end' => '20:00'], // Sat
        ];

        // Link Alexandre Dumas to the primary stylist@more.id user
        $alexandreUser = User::where('email', 'stylist@more.id')->first();

        $rianUser = User::create([
            'name' => 'Rian Hidayat',
            'email' => 'rian.stylist@more.id',
            'password' => Hash::make('password123'),
        ]);

        $vitoUser = User::create([
            'name' => 'Vito Corleone',
            'email' => 'vito.stylist@more.id',
            'password' => Hash::make('password123'),
        ]);

        $sitiUser = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti.stylist@more.id',
            'password' => Hash::make('password123'),
        ]);

        $dennyUser = User::create([
            'name' => 'Denny Pratama',
            'email' => 'denny.stylist@more.id',
            'password' => Hash::make('password123'),
        ]);

        Stylist::create([
            'user_id' => $alexandreUser->id,
            'outlet_id' => $gi->id,
            'name' => 'Alexandre Dumas',
            'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop',
            'specialty' => 'Classic Haircuts & Beard Styling',
            'rating' => 4.95,
            'experience_years' => 8,
            'schedule' => $stylistSchedule,
            'is_active' => true,
        ]);

        Stylist::create([
            'user_id' => $rianUser->id,
            'outlet_id' => $gi->id,
            'name' => 'Rian Hidayat',
            'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop',
            'specialty' => 'Fades, Buzz Cuts, Pompadour',
            'rating' => 4.88,
            'experience_years' => 5,
            'schedule' => $stylistSchedule,
            'is_active' => true,
        ]);

        Stylist::create([
            'user_id' => $vitoUser->id,
            'outlet_id' => $sc->id,
            'name' => 'Vito Corleone',
            'avatar_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=200&auto=format&fit=crop',
            'specialty' => 'Scissor Cut & Creative Coloring',
            'rating' => 4.92,
            'experience_years' => 12,
            'schedule' => $stylistSchedule,
            'is_active' => true,
        ]);

        Stylist::create([
            'user_id' => $sitiUser->id,
            'outlet_id' => $sc->id,
            'name' => 'Siti Aminah',
            'avatar_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=200&auto=format&fit=crop',
            'specialty' => 'Coloring & Scalp Spa',
            'rating' => 4.80,
            'experience_years' => 4,
            'schedule' => $stylistSchedule,
            'is_active' => true,
        ]);

        Stylist::create([
            'user_id' => $dennyUser->id,
            'outlet_id' => $cp->id,
            'name' => 'Denny Pratama',
            'avatar_url' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?q=80&w=200&auto=format&fit=crop',
            'specialty' => 'Fades, Smoothing, Texturizing',
            'rating' => 4.78,
            'experience_years' => 6,
            'schedule' => $stylistSchedule,
            'is_active' => true,
        ]);

        // 6. Create Customer Records
        Customer::create([
            'user_id' => $customerUser->id,
            'full_name' => 'Rian Wijaya',
            'email' => 'customer@more.id',
            'phone' => '081234567890',
            'whatsapp' => '081234567890',
            'date_of_birth' => '1995-06-29', // Birthday matches today in local time for demo!
            'gender' => 'male',
            'membership_status' => 'gold',
            'loyalty_points' => 350,
            'total_visits' => 12,
            'total_spending' => 1850000.00,
            'last_visit_at' => now()->subDays(10),
        ]);

        Customer::create([
            'user_id' => null,
            'full_name' => 'Jane Doe (Guest)',
            'email' => 'jane.doe@example.com',
            'phone' => '08987654321',
            'whatsapp' => '08987654321',
            'date_of_birth' => '1998-12-15',
            'gender' => 'female',
            'membership_status' => 'regular',
            'loyalty_points' => 10,
            'total_visits' => 1,
            'total_spending' => 150000.00,
            'last_visit_at' => now()->subDays(2),
        ]);

        // 7. Create Promotions
        Promotion::create([
            'code' => 'GRANDLAUNCH',
            'name' => 'Grand Launch Promo',
            'description' => 'Enjoy 20% discount on all haircut and treatment services to celebrate MORE Grand Opening.',
            'discount_type' => 'percentage',
            'discount_value' => 20.00,
            'min_spend' => 100000.00,
            'start_date' => now()->subDays(30),
            'end_date' => now()->addDays(30),
            'is_active' => true,
        ]);

        Promotion::create([
            'code' => 'MORE10K',
            'name' => 'Direct Rp 10,000 Off',
            'description' => 'Direct discount of Rp 10,000 for any styling products or haircut combination.',
            'discount_type' => 'fixed',
            'discount_value' => 10000.00,
            'min_spend' => 150000.00,
            'start_date' => now()->subDays(30),
            'end_date' => now()->addDays(30),
            'is_active' => true,
        ]);

        // 8. Seed Sample Reviews
        $customer1 = Customer::first();
        $customer2 = Customer::where('id', '!=', $customer1->id)->first();
        $stylist1 = Stylist::first();
        $stylist2 = Stylist::where('id', '!=', $stylist1->id)->first();

        if ($customer1 && $stylist1) {
            Review::create([
                'customer_id' => $customer1->id,
                'stylist_id' => $stylist1->id,
                'rating' => 5,
                'comment' => 'Potongan fade-nya rapi banget dan ga buru-buru. Pelayanan premium parah, dapet kopi gratis pula!'
            ]);
        }

        if ($customer2 && $stylist2) {
            Review::create([
                'customer_id' => $customer2->id,
                'stylist_id' => $stylist2->id,
                'rating' => 5,
                'comment' => 'Baru pertama kali coba haircut di Senayan City, stylist-nya profesional banget ngasih saran model yang pas!'
            ]);
        }
    }
}
