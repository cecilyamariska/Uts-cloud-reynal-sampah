@extends('layouts.app', ['title' => 'Tambah Jadwal'])

@section('content')
    <section class="panel">
        <h1>Tambah Jadwal Pengangkutan</h1>
        <form method="POST" action="{{ route('pickup-schedules.store') }}">
            @csrf
            @include('pickup-schedules._form')
        </form>
    </section>
@endsection
