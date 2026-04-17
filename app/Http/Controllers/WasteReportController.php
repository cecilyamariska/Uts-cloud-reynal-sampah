<?php

namespace App\Http\Controllers;

use App\Models\WasteReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WasteReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $reports = WasteReport::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('waste-reports.index', [
            'reports' => $reports,
            'status' => $status,
        ]);
    }

    public function create()
    {
        return view('waste-reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'status' => ['required', 'in:baru,diproses,selesai'],
            'reported_at' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $disk = config('app.report_upload_disk', config('filesystems.default'));

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('waste-reports', $disk);
            $validated['photo_disk'] = $disk;
        }

        WasteReport::create($validated);

        return redirect()
            ->route('waste-reports.index')
            ->with('success', 'Laporan sampah berhasil ditambahkan.');
    }

    public function show(WasteReport $wasteReport)
    {
        return view('waste-reports.show', [
            'report' => $wasteReport,
        ]);
    }

    public function edit(WasteReport $wasteReport)
    {
        return view('waste-reports.edit', [
            'report' => $wasteReport,
        ]);
    }

    public function update(Request $request, WasteReport $wasteReport)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'status' => ['required', 'in:baru,diproses,selesai'],
            'reported_at' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $disk = $wasteReport->photo_disk ?: config('app.report_upload_disk', config('filesystems.default'));

        if ($request->hasFile('photo')) {
            if ($wasteReport->photo_path) {
                Storage::disk($disk)->delete($wasteReport->photo_path);
            }

            $newDisk = config('app.report_upload_disk', config('filesystems.default'));
            $validated['photo_path'] = $request->file('photo')->store('waste-reports', $newDisk);
            $validated['photo_disk'] = $newDisk;
        }

        $wasteReport->update($validated);

        return redirect()
            ->route('waste-reports.index')
            ->with('success', 'Laporan sampah berhasil diperbarui.');
    }

    public function destroy(WasteReport $wasteReport)
    {
        if ($wasteReport->photo_path) {
            Storage::disk($wasteReport->photo_disk)->delete($wasteReport->photo_path);
        }

        $wasteReport->delete();

        return redirect()
            ->route('waste-reports.index')
            ->with('success', 'Laporan sampah berhasil dihapus.');
    }
}
