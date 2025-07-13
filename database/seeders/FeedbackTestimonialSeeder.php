<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FeedbackTestimonial;

class FeedbackTestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'rating' => 5,
                'message' => 'Pelayanan sangat memuaskan! Teknisi datang tepat waktu dan berhasil memperbaiki laptop saya dengan cepat. Harga juga sangat terjangkau. Terima kasih Tecomp99!',
                'status' => 'approved',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'name' => 'Sari Dewi',
                'email' => 'sari.dewi@email.com',
                'rating' => 5,
                'message' => 'Sudah beberapa kali servis di Tecomp99, selalu puas dengan hasilnya. Tim teknisinya profesional dan ramah. Recommended banget!',
                'status' => 'approved',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@email.com',
                'rating' => 4,
                'message' => 'Servis onsite sangat membantu, tidak perlu repot bawa komputer ke toko. Teknisinya datang dengan peralatan lengkap dan menyelesaikan masalah dengan baik.',
                'status' => 'approved',
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
            [
                'name' => 'Maya Putri',
                'email' => 'maya.putri@email.com',
                'rating' => 5,
                'message' => 'Beli RAM dan SSD di Tecomp99, harga kompetitif dan barang original. Bonus install gratis pula. Pelayanan ramah dan profesional.',
                'status' => 'approved',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'name' => 'Doni Pratama',
                'email' => 'doni.pratama@email.com',
                'rating' => 4,
                'message' => 'Konsultasi gratis sangat membantu sebelum memutuskan servis. Tim support responsif dan memberikan solusi yang tepat.',
                'status' => 'approved',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'name' => 'Linda Sari',
                'email' => 'linda.sari@email.com',
                'rating' => 5,
                'message' => 'Laptop gaming saya bermasalah, setelah servis di Tecomp99 kembali normal dan performa meningkat. Garansi 30 hari juga memberikan rasa aman.',
                'status' => 'approved',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ];

        foreach ($testimonials as $testimonial) {
            FeedbackTestimonial::create($testimonial);
        }
    }
}
