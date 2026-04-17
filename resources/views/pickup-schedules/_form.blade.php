@php
    $isEdit = isset($schedule);
@endphp

<div class="form-grid">
    <div>
        <label>Nama Rute</label>
        <input type="text" name="route_name" value="{{ old('route_name', $schedule->route_name ?? '') }}" required>
    </div>

    <div>
        <label>Wilayah</label>
        <input type="text" name="area" value="{{ old('area', $schedule->area ?? '') }}" required>
    </div>

    <div>
        <label>Tanggal Angkut</label>
        <input type="date" name="pickup_date" value="{{ old('pickup_date', isset($schedule) ? $schedule->pickup_date->format('Y-m-d') : '') }}" required>
    </div>

    <div>
        <label>Jam Angkut</label>
        <input type="time" name="pickup_time" value="{{ old('pickup_time', $schedule->pickup_time ?? '') }}">
    </div>

    <div>
        <label>Kode Truk</label>
        <input type="text" name="truck_code" value="{{ old('truck_code', $schedule->truck_code ?? '') }}">
    </div>

    <div>
        <label>Status</label>
        <select name="status" required>
            @foreach (['terjadwal', 'berjalan', 'selesai', 'tertunda'] as $status)
                <option value="{{ $status }}" @selected(old('status', $schedule->status ?? 'terjadwal') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="full">
        <label>Catatan</label>
        <textarea name="notes" rows="4">{{ old('notes', $schedule->notes ?? '') }}</textarea>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn-primary">{{ $isEdit ? 'Update Jadwal' : 'Simpan Jadwal' }}</button>
    <a href="{{ route('pickup-schedules.index') }}" class="btn-secondary">Batal</a>
</div>
