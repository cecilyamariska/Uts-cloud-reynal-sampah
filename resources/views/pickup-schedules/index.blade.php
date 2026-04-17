@extends('layouts.app', ['title' => 'Jadwal Pengangkutan'])

@section('content')
    <section class="panel">
        <div class="panel-head">
            <h1>Jadwal Pengangkutan Sampah</h1>
            <a href="{{ route('pickup-schedules.create') }}" class="btn-primary">Tambah Jadwal</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Rute</th>
                        <th>Wilayah</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->route_name }}</td>
                            <td>{{ $schedule->area }}</td>
                            <td>{{ $schedule->pickup_date->format('d M Y') }}</td>
                            <td>{{ $schedule->pickup_time ?? '-' }}</td>
                            <td><span class="pill">{{ ucfirst($schedule->status) }}</span></td>
                            <td class="action-row">
                                <a class="btn-secondary" href="{{ route('pickup-schedules.show', $schedule) }}">Detail</a>
                                <a class="btn-secondary" href="{{ route('pickup-schedules.edit', $schedule) }}">Edit</a>
                                <form method="POST" action="{{ route('pickup-schedules.destroy', $schedule) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada jadwal pengangkutan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $schedules->links() }}
    </section>
@endsection
