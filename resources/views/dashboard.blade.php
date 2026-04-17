@extends('layouts.app')

@section('content')
    <section class="hero-card">
        <div>
            <p class="eyebrow">Aplikasi Fullstack Pengelolaan Sampah</p>
            <h1>Monitoring laporan, jadwal, dan petugas dalam satu panel.</h1>
            <p class="hero-subtext">
                Siap di-deploy ke AWS EC2 menggunakan Docker, penyimpanan file ke S3, dan database RDS.
            </p>
        </div>
        <div class="hero-badge">
            <span>Cloud Ready</span>
            <strong>EC2 + RDS + S3</strong>
        </div>
    </section>

    <section class="stats-grid stagger">
        <article class="stat-card">
            <p>Total Laporan</p>
            <h2>{{ $reportTotal }}</h2>
        </article>
        <article class="stat-card">
            <p>Laporan Selesai</p>
            <h2>{{ $reportDone }}</h2>
        </article>
        <article class="stat-card">
            <p>Laporan Diproses</p>
            <h2>{{ $reportPending }}</h2>
        </article>
        <article class="stat-card">
            <p>Jadwal Hari Ini</p>
            <h2>{{ $scheduleToday }}</h2>
        </article>
        <article class="stat-card">
            <p>Petugas Aktif</p>
            <h2>{{ $activeOfficers }}</h2>
        </article>
    </section>

    <section class="panel-grid">
        <article class="panel">
            <div class="panel-head">
                <h3>Laporan Terbaru</h3>
                <a class="btn-secondary" href="{{ route('waste-reports.index') }}">Kelola</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentReports as $report)
                            <tr>
                                <td>{{ $report->title }}</td>
                                <td>{{ $report->location }}</td>
                                <td><span class="pill">{{ ucfirst($report->status) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Belum ada laporan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        <article class="panel">
            <div class="panel-head">
                <h3>Jadwal Mendatang</h3>
                <a class="btn-secondary" href="{{ route('pickup-schedules.index') }}">Kelola</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Rute</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($upcomingSchedules as $schedule)
                            <tr>
                                <td>{{ $schedule->route_name }}</td>
                                <td>{{ $schedule->pickup_date->format('d M Y') }}</td>
                                <td><span class="pill">{{ ucfirst($schedule->status) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Belum ada jadwal.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
@endsection
