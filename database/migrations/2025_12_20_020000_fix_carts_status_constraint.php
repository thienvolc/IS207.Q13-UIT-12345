<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix lỗi carts_chk_1 violated khi update status = 4
        // Kiểm tra xem constraint có tồn tại không trước khi drop để tránh lỗi nếu chạy lại
        try {
            DB::statement("ALTER TABLE carts DROP CHECK carts_chk_1");
        } catch (\Exception $e) {
            // Log warning or ignore if constraint not found
            \Illuminate\Support\Facades\Log::warning("Could not drop carts_chk_1: " . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần restore lại constraint cũ gây lỗi
    }
};
