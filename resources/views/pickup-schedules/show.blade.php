@extends('layouts.app', ['title' => 'Detail Jadwal'])

@section('content')
    <section class="panel">
        <div class="panel-head">
            <h1>Detail Jadwal</h1>
            <a href="{{ route('pickup-schedules.index') }}" class="btn-secondary">Kembali</a>
        </div>

        <div class="detail-grid single">
            <div>
                <p><strong>Nama Rute:</strong> {{ $schedule->route_name }}</p>
                <p><strong>Wilayah:</strong> {{ $schedule->area }}</p>
                <p><strong>Tanggal:</strong> {{ $schedule->pickup_date->format('d M Y') }}</p>
                <p><strong>Jam:</strong> {{ $schedule->pickup_time ?? '-' }}</p>
                <p><strong>Kode Truk:</strong> {{ $schedule->truck_code ?? '-' }}</p>
                <p><strong>Status:</strong> <span class="pill">{{ ucfirst($schedule->status) }}</span></p>
                <p><strong>Catatan:</strong> {{ $schedule->notes ?: '-' }}</p>
            </div>
        </div>
    </section>
@endsection
