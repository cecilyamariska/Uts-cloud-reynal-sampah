<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    public function index()
    {
        return view('officers.index', [
            'officers' => Officer::latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('officers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'assigned_area' => ['required', 'string', 'max:255'],
            'shift' => ['required', 'in:pagi,siang,malam'],
            'status' => ['required', 'in:aktif,cuti,nonaktif'],
            'notes' => ['nullable', 'string'],
        ]);

        Officer::create($validated);

        return redirect()
            ->route('officers.index')
            ->with('success', 'Data petugas berhasil ditambahkan.');
    }

    public function show(Officer $officer)
    {
        return view('officers.show', [
            'officer' => $officer,
        ]);
    }

    public function edit(Officer $officer)
    {
        return view('officers.edit', [
            'officer' => $officer,
        ]);
    }

    public function update(Request $request, Officer $officer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'assigned_area' => ['required', 'string', 'max:255'],
            'shift' => ['required', 'in:pagi,siang,malam'],
            'status' => ['required', 'in:aktif,cuti,nonaktif'],
            'notes' => ['nullable', 'string'],
        ]);

        $officer->update($validated);

        return redirect()
            ->route('officers.index')
            ->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy(Officer $officer)
    {
        $officer->delete();

        return redirect()
            ->route('officers.index')
            ->with('success', 'Data petugas berhasil dihapus.');
    }
}
