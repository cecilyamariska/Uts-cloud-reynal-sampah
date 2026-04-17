<?php

namespace Database\Seeders;

use App\Models\Officer;
use App\Models\PickupSchedule;
use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]);

        if (WasteReport::count() === 0) {
            WasteReport::query()->insert([
                [
                    'title' => 'Sampah Menumpuk di Pinggir Jalan',
                    'description' => 'Tumpukan sampah rumah tangga belum diangkut lebih dari 2 hari.',
                    'location' => 'Jl. Merdeka, RT 03/RW 02',
                    'status' => 'baru',
                    'reported_at' => now()->subHours(6),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Tempat Sampah Pasar Rusak',
                    'description' => 'Kontainer sampah di area pasar sudah pecah dan menimbulkan bau.',
                    'location' => 'Pasar Induk Blok B',
                    'status' => 'diproses',
                    'reported_at' => now()->subDay(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        if (PickupSchedule::count() === 0) {
            PickupSchedule::query()->insert([
                [
                    'route_name' => 'Rute Kota Barat',
                    'area' => 'Kecamatan Barat',
                    'pickup_date' => now()->toDateString(),
                    'pickup_time' => '07:00',
                    'truck_code' => 'TRK-01',
                    'status' => 'terjadwal',
                    'notes' => 'Prioritaskan area pasar.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'route_name' => 'Rute Kota Timur',
                    'area' => 'Kecamatan Timur',
                    'pickup_date' => now()->addDay()->toDateString(),
                    'pickup_time' => '08:30',
                    'truck_code' => 'TRK-03',
                    'status' => 'terjadwal',
                    'notes' => 'Fokus area sekolah dan perumahan.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        if (Officer::count() === 0) {
            Officer::query()->insert([
                [
                    'name' => 'Rudi Hartono',
                    'phone' => '081234567891',
                    'assigned_area' => 'Kecamatan Barat',
                    'shift' => 'pagi',
                    'status' => 'aktif',
                    'notes' => 'Koordinator lapangan.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Sinta Mulyani',
                    'phone' => '081234567892',
                    'assigned_area' => 'Kecamatan Timur',
                    'shift' => 'siang',
                    'status' => 'aktif',
                    'notes' => 'Penanggung jawab jadwal minggu ini.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
