<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Sistem Pengelolaan Sampah' }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-shell">
        <div class="bg-orb orb-1"></div>
        <div class="bg-orb orb-2"></div>

        <header class="main-header">
            <a href="{{ route('dashboard') }}" class="brand">SampahRey</a>
            <nav class="main-nav">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('waste-reports.index') }}" class="{{ request()->routeIs('waste-reports.*') ? 'active' : '' }}">Laporan Sampah</a>
                <a href="{{ route('pickup-schedules.index') }}" class="{{ request()->routeIs('pickup-schedules.*') ? 'active' : '' }}">Jadwal Angkut</a>
                <a href="{{ route('officers.index') }}" class="{{ request()->routeIs('officers.*') ? 'active' : '' }}">Petugas</a>
            </nav>
        </header>

        <main class="page-wrap reveal">
            @if (session('success'))
                <div class="notice success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="notice error">
                    <strong>Periksa kembali input:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </body>
</html>
