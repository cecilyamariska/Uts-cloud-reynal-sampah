@extends('layouts.app', ['title' => 'Tambah Petugas'])

@section('content')
    <section class="panel">
        <h1>Tambah Data Petugas</h1>
        <form method="POST" action="{{ route('officers.store') }}">
            @csrf
            @include('officers._form')
        </form>
    </section>
@endsection
