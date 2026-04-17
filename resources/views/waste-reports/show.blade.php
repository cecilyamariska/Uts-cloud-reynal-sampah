@extends('layouts.app', ['title' => 'Detail Laporan'])

@section('content')
    <section class="panel">
        <div class="panel-head">
            <h1>Detail Laporan</h1>
            <a href="{{ route('waste-reports.index') }}" class="btn-secondary">Kembali</a>
        </div>

        <div class="detail-grid">
            <div>
                <h3>{{ $report->title }}</h3>
                <p>{{ $report->description }}</p>
                <p><strong>Lokasi:</strong> {{ $report->location }}</p>
                <p><strong>Status:</strong> <span class="pill">{{ ucfirst($report->status) }}</span></p>
                <p><strong>Koordinat:</strong> {{ $report->latitude ?? '-' }}, {{ $report->longitude ?? '-' }}</p>
                <p><strong>Waktu Laporan:</strong> {{ $report->reported_at?->format('d M Y H:i') ?? '-' }}</p>
            </div>
            <div>
                @if ($report->photoUrl())
                    <img class="detail-image" src="{{ $report->photoUrl() }}" alt="Foto laporan">
                @else
                    <div class="empty-photo">Belum ada foto laporan</div>
                @endif
            </div>
        </div>
    </section>
@endsection
