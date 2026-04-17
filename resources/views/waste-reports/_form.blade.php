@php
    $isEdit = isset($report);
@endphp

<div class="form-grid">
    <div>
        <label>Judul Laporan</label>
        <input type="text" name="title" value="{{ old('title', $report->title ?? '') }}" required>
    </div>

    <div>
        <label>Status</label>
        <select name="status" required>
            @foreach (['baru', 'diproses', 'selesai'] as $status)
                <option value="{{ $status }}" @selected(old('status', $report->status ?? 'baru') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="full">
        <label>Lokasi</label>
        <input type="text" name="location" value="{{ old('location', $report->location ?? '') }}" required>
    </div>

    <div class="full" style="display: flex; gap: 10px; align-items: flex-end;">
        <div style="flex: 1;">
            <label>Latitude</label>
            <input type="number" step="0.0000001" name="latitude" id="latitude" value="{{ old('latitude', $report->latitude ?? '') }}" placeholder="Klik tombol di sebelah">
        </div>

        <div style="flex: 1;">
            <label>Longitude</label>
            <input type="number" step="0.0000001" name="longitude" id="longitude" value="{{ old('longitude', $report->longitude ?? '') }}" placeholder="Klik tombol di sebelah">
        </div>

        <button type="button" id="useLocationBtn" class="btn-primary" style="white-space: nowrap;">📍 Gunakan Lokasi Saya</button>
    </div>

    <div>
        <label>Waktu Laporan</label>
        <input type="datetime-local" name="reported_at" value="{{ old('reported_at', isset($report) && $report->reported_at ? $report->reported_at->format('Y-m-d\TH:i') : '') }}">
    </div>

    <div class="full">
        <label>Deskripsi</label>
        <textarea name="description" rows="5" required>{{ old('description', $report->description ?? '') }}</textarea>
    </div>

    <div class="full">
        <label>Foto Laporan {{ $isEdit ? '(opsional, ganti jika ingin update)' : '(opsional)' }}</label>
        <input type="file" name="photo" accept="image/*">

        @if ($isEdit && $report->photoUrl())
            <div class="image-preview">
                <img src="{{ $report->photoUrl() }}" alt="Foto laporan">
            </div>
        @endif
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn-primary">{{ $isEdit ? 'Update Laporan' : 'Simpan Laporan' }}</button>
    <a href="{{ route('waste-reports.index') }}" class="btn-secondary">Batal</a>
</div>
