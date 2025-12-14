<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PropertyImageSeeder extends Seeder
{
    /**
     * Danh sách URL ảnh từ Cloudinary (luxury home và background)
     */
    private function getCloudinaryImages(): array
    {
        return [
            // Folder: luxury home (15 ảnh)
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680536/salman-saqib-WaC-JFfF21M-unsplash_yvcull.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680536/clay-banks-4zlQ3CCoIyo-unsplash_tshd93.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680535/florian-schmidinger-b_79nOqf95I-unsplash_cllnqk.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680534/lycs-architecture-kUdbEEMcRwE-unsplash_o6n6m6.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680532/roberto-nickson-emqnSQwQQDo-unsplash_c4uaq0.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680531/clay-banks-zGRQYKuNa2E-unsplash_bzzfoi.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680531/roberto-nickson-rEJxpBskj3Q-unsplash_hb4jvb.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680529/avi-werde-hHz4yrvxwlA-unsplash_jd4cgx.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680529/clay-banks-vPsvT8tkLSQ-unsplash_j4bftm.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680529/lotus-design-n-print-xroM8RaMnSI-unsplash_vy4wj2.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680528/roberto-nickson-so3wgJLwDxo-unsplash_vbsrnq.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680528/aranprime-KbytCpI1i5I-unsplash_qwddvp.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680528/aaron-huber-G7sE2S4Lab4-unsplash_kxrblb.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680527/vj-von-art-BP1Ze0qcp-c-unsplash_u14aqc.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680527/clay-banks-C-FqIffctHI-unsplash_lmpnwo.jpg',
            // Folder: background (11 ảnh)
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680217/sarmat-batagov-2HbkIQeZRBc-unsplash_rchtsw.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680217/nate-holland-X88xujrI04Q-unsplash_lo7mvo.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680216/josh-hild-jaJfFe0HAnM-unsplash_lci95t.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680215/alex-robertson-RPFvgzbPWxA-unsplash_yko56n.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680214/diana-bondarenko-ZFaz6FJgVIw-unsplash_nk1dha.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680214/marco-grosso-4OyGSc2c0vw-unsplash_ffziqh.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680214/marco-grosso-m41uew-oMlU-unsplash_p7gdv9.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680213/junel-mujar-yJmR_0Fookg-unsplash_ieuwa5.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680213/huy-phan-5V0BTf2XMBY-unsplash_skiybp.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680212/jason-dent-w3eFhqXjkZE-unsplash_s64awu.jpg',
            'https://res.cloudinary.com/dkrnmtema/image/upload/v1765680212/salman-saqib-WaC-JFfF21M-unsplash_q1zvny.jpg',
        ];
    }

    /**
     * Lấy tên file từ URL
     */
    private function getImageNameFromUrl(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        $filename = basename($path);
        return $filename ?: 'image.jpg';
    }

    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $now   = Carbon::now();

        $this->command->info('   → Đang tạo hình ảnh cho bất động sản...');
        $propertyIds = DB::table('properties')->pluck('id')->all();
        $totalProperties = count($propertyIds);

        if ($totalProperties > 0) {
            // Lấy danh sách ảnh từ Cloudinary
            $cloudinaryImages = $this->getCloudinaryImages();
            $this->command->line("   ✓ Đã tải {$totalProperties} bất động sản và " . count($cloudinaryImages) . " ảnh từ Cloudinary");

            $bar = $this->command->getOutput()->createProgressBar($totalProperties);
            $bar->setFormat('   %current%/%max% [%bar%] %percent:3s%% %message%');
            $bar->setMessage('Đang xử lý...');
            $bar->start();

            $totalImages = 0;
            foreach ($propertyIds as $pid) {
                // Mỗi property có 3-7 ảnh
                $imgCount = $faker->numberBetween(3, 7);
                $primary  = $faker->numberBetween(1, $imgCount);

                // Chọn ngẫu nhiên các ảnh từ danh sách Cloudinary
                $selectedImages = $faker->randomElements($cloudinaryImages, min($imgCount, count($cloudinaryImages)));

                $rows = [];
                foreach ($selectedImages as $index => $imageUrl) {
                    $rows[] = [
                        'property_id' => $pid,
                        'image_path'  => $imageUrl, // URL từ Cloudinary
                        'image_name'  => $this->getImageNameFromUrl($imageUrl),
                        'is_primary'  => ($index + 1) === $primary,
                        'sort_order'  => $index + 1,
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ];
                }
                DB::table('property_images')->insert($rows);
                $totalImages += count($rows);

                if (($pid % 50 == 0) || ($pid == end($propertyIds))) {
                    $bar->setMessage("Đã tạo hình ảnh cho {$pid} bất động sản");
                }
                $bar->advance();
            }

            $bar->setMessage('Hoàn thành!');
            $bar->finish();
            $this->command->newLine();
            $this->command->line("   ✓ Đã tạo {$totalImages} hình ảnh cho {$totalProperties} bất động sản");
            $this->command->line("   📸 Sử dụng ảnh thật từ Cloudinary (luxury home & background)");
        } else {
            $this->command->warn('   ⚠ Không có bất động sản nào để tạo hình ảnh');
        }
    }
}
