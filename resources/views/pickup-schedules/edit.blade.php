@extends('layouts.app', ['title' => 'Edit Jadwal'])

@section('content')
    <section class="panel">
        <h1>Edit Jadwal Pengangkutan</h1>
        <form method="POST" action="{{ route('pickup-schedules.update', $schedule) }}">
            @csrf
            @method('PUT')
            @include('pickup-schedules._form')
        </form>
    </section>
@endsection
