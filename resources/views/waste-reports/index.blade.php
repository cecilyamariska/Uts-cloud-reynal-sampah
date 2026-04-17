@extends('layouts.app', ['title' => 'Laporan Sampah'])

@section('content')
    <section class="panel">
        <div class="panel-head">
            <h1>Laporan Sampah Liar</h1>
            <a href="{{ route('waste-reports.create') }}" class="btn-primary">Tambah Laporan</a>
        </div>

        <form method="GET" class="inline-filter">
            <label for="status">Filter status</label>
            <select name="status" id="status" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach (['baru', 'diproses', 'selesai'] as $item)
                    <option value="{{ $item }}" @selected($status === $item)>{{ ucfirst($item) }}</option>
                @endforeach
            </select>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                        <tr>
                            <td>{{ $report->title }}</td>
                            <td>{{ $report->location }}</td>
                            <td><span class="pill">{{ ucfirst($report->status) }}</span></td>
                            <td>
                                @if ($report->photoUrl())
                                    <a href="{{ $report->photoUrl() }}" target="_blank" class="btn-link">Lihat File</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="action-row">
                                <a class="btn-secondary" href="{{ route('waste-reports.show', $report) }}">Detail</a>
                                <a class="btn-secondary" href="{{ route('waste-reports.edit', $report) }}">Edit</a>
                                <form method="POST" action="{{ route('waste-reports.destroy', $report) }}" onsubmit="return confirm('Hapus laporan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Belum ada data laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $reports->links() }}
    </section>
@endsection
