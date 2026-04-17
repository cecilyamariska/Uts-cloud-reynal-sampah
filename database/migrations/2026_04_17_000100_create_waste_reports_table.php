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
        Schema::create('waste_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('photo_path')->nullable();
            $table->string('photo_disk', 50)->default('s3');
            $table->enum('status', ['baru', 'diproses', 'selesai'])->default('baru');
            $table->timestamp('reported_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('reported_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_reports');
    }
};
