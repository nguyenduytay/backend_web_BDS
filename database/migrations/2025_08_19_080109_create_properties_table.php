<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();

            $table->foreignId('location_id')->constrained('locations');
            $table->foreignId('property_type_id')->constrained('property_types');

            $table->string('status')->default('available');
            $table->decimal('price', 15, 2);
            $table->decimal('area', 10, 2);
            $table->integer('bedrooms')->default(0);
            $table->integer('bathrooms')->default(0);
            $table->integer('floors')->default(1);

            $table->text('address');
            $table->string('postal_code', 20)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('year_built')->nullable();

            $table->foreignId('contact_id')->constrained('contacts');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });

        // Add CHECK constraint for PostgreSQL compatibility
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE properties ADD CONSTRAINT properties_status_check CHECK (status IN ('available', 'sold', 'rented', 'pending'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
