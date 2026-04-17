<?php

namespace App\Http\Controllers;

use App\Models\PickupSchedule;
use Illuminate\Http\Request;

class PickupScheduleController extends Controller
{
    public function index()
    {
        return view('pickup-schedules.index', [
            'schedules' => PickupSchedule::query()
                ->orderBy('pickup_date')
                ->orderBy('pickup_time')
                ->paginate(10),
        ]);
    }

    public function create()
    {
        return view('pickup-schedules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_name' => ['required', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'pickup_date' => ['required', 'date'],
            'pickup_time' => ['nullable', 'date_format:H:i'],
            'truck_code' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:terjadwal,berjalan,selesai,tertunda'],
        ]);

        PickupSchedule::create($validated);

        return redirect()
            ->route('pickup-schedules.index')
            ->with('success', 'Jadwal pengangkutan berhasil ditambahkan.');
    }

    public function show(PickupSchedule $pickupSchedule)
    {
        return view('pickup-schedules.show', [
            'schedule' => $pickupSchedule,
        ]);
    }

    public function edit(PickupSchedule $pickupSchedule)
    {
        return view('pickup-schedules.edit', [
            'schedule' => $pickupSchedule,
        ]);
    }

    public function update(Request $request, PickupSchedule $pickupSchedule)
    {
        $validated = $request->validate([
            'route_name' => ['required', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'pickup_date' => ['required', 'date'],
            'pickup_time' => ['nullable', 'date_format:H:i'],
            'truck_code' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:terjadwal,berjalan,selesai,tertunda'],
        ]);

        $pickupSchedule->update($validated);

        return redirect()
            ->route('pickup-schedules.index')
            ->with('success', 'Jadwal pengangkutan berhasil diperbarui.');
    }

    public function destroy(PickupSchedule $pickupSchedule)
    {
        $pickupSchedule->delete();

        return redirect()
            ->route('pickup-schedules.index')
            ->with('success', 'Jadwal pengangkutan berhasil dihapus.');
    }
}
