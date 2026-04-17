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
        Schema::create('pickup_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('route_name');
            $table->string('area');
            $table->date('pickup_date');
            $table->time('pickup_time')->nullable();
            $table->string('truck_code')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['terjadwal', 'berjalan', 'selesai', 'tertunda'])->default('terjadwal');
            $table->timestamps();

            $table->index(['pickup_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_schedules');
    }
};
