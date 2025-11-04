<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('property_id');

            // Chỉ cần 1 lần timestamps
            $table->timestamps();

            // Ràng buộc quan hệ
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('property_id')
                  ->references(columns: 'id')->on('properties')
                  ->onDelete('cascade');

            // Một user chỉ có thể favorite 1 property duy nhất
            $table->unique(['user_id', 'property_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
