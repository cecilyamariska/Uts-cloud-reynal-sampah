@extends('layouts.app', ['title' => 'Detail Petugas'])

@section('content')
    <section class="panel">
        <div class="panel-head">
            <h1>Detail Petugas</h1>
            <a href="{{ route('officers.index') }}" class="btn-secondary">Kembali</a>
        </div>

        <div class="detail-grid single">
            <div>
                <p><strong>Nama:</strong> {{ $officer->name }}</p>
                <p><strong>Telepon:</strong> {{ $officer->phone ?: '-' }}</p>
                <p><strong>Area Tugas:</strong> {{ $officer->assigned_area }}</p>
                <p><strong>Shift:</strong> {{ ucfirst($officer->shift) }}</p>
                <p><strong>Status:</strong> <span class="pill">{{ ucfirst($officer->status) }}</span></p>
                <p><strong>Catatan:</strong> {{ $officer->notes ?: '-' }}</p>
            </div>
        </div>
    </section>
@endsection
