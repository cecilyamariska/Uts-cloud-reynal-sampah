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
        Schema::create('officers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('assigned_area');
            $table->enum('shift', ['pagi', 'siang', 'malam'])->default('pagi');
            $table->enum('status', ['aktif', 'cuti', 'nonaktif'])->default('aktif');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['assigned_area', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officers');
    }
};
