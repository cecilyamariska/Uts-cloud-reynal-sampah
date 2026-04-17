@extends('layouts.app', ['title' => 'Data Petugas'])

@section('content')
    <section class="panel">
        <div class="panel-head">
            <h1>Monitoring Petugas Kebersihan</h1>
            <a href="{{ route('officers.create') }}" class="btn-primary">Tambah Petugas</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Area</th>
                        <th>Shift</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($officers as $officer)
                        <tr>
                            <td>{{ $officer->name }}</td>
                            <td>{{ $officer->phone ?: '-' }}</td>
                            <td>{{ $officer->assigned_area }}</td>
                            <td>{{ ucfirst($officer->shift) }}</td>
                            <td><span class="pill">{{ ucfirst($officer->status) }}</span></td>
                            <td class="action-row">
                                <a class="btn-secondary" href="{{ route('officers.show', $officer) }}">Detail</a>
                                <a class="btn-secondary" href="{{ route('officers.edit', $officer) }}">Edit</a>
                                <form method="POST" action="{{ route('officers.destroy', $officer) }}" onsubmit="return confirm('Hapus data petugas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada data petugas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $officers->links() }}
    </section>
@endsection
