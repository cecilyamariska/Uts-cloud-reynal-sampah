@php
    $isEdit = isset($officer);
@endphp

<div class="form-grid">
    <div>
        <label>Nama Petugas</label>
        <input type="text" name="name" value="{{ old('name', $officer->name ?? '') }}" required>
    </div>

    <div>
        <label>No. Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $officer->phone ?? '') }}">
    </div>

    <div>
        <label>Area Tugas</label>
        <input type="text" name="assigned_area" value="{{ old('assigned_area', $officer->assigned_area ?? '') }}" required>
    </div>

    <div>
        <label>Shift</label>
        <select name="shift" required>
            @foreach (['pagi', 'siang', 'malam'] as $shift)
                <option value="{{ $shift }}" @selected(old('shift', $officer->shift ?? 'pagi') === $shift)>
                    {{ ucfirst($shift) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Status</label>
        <select name="status" required>
            @foreach (['aktif', 'cuti', 'nonaktif'] as $status)
                <option value="{{ $status }}" @selected(old('status', $officer->status ?? 'aktif') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="full">
        <label>Catatan</label>
        <textarea name="notes" rows="4">{{ old('notes', $officer->notes ?? '') }}</textarea>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn-primary">{{ $isEdit ? 'Update Petugas' : 'Simpan Petugas' }}</button>
    <a href="{{ route('officers.index') }}" class="btn-secondary">Batal</a>
</div>
