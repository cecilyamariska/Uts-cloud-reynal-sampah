@extends('layouts.app', ['title' => 'Edit Petugas'])

@section('content')
    <section class="panel">
        <h1>Edit Data Petugas</h1>
        <form method="POST" action="{{ route('officers.update', $officer) }}">
            @csrf
            @method('PUT')
            @include('officers._form')
        </form>
    </section>
@endsection
