<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\PickupSchedule;
use App\Models\WasteReport;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'reportTotal' => WasteReport::count(),
            'reportDone' => WasteReport::where('status', 'selesai')->count(),
            'reportPending' => WasteReport::whereIn('status', ['baru', 'diproses'])->count(),
            'scheduleToday' => PickupSchedule::whereDate('pickup_date', now()->toDateString())->count(),
            'activeOfficers' => Officer::where('status', 'aktif')->count(),
            'recentReports' => WasteReport::latest()->limit(5)->get(),
            'upcomingSchedules' => PickupSchedule::orderBy('pickup_date')->orderBy('pickup_time')->limit(5)->get(),
        ]);
    }
}
