@extends('layouts.app', ['title' => 'Tambah Laporan'])

@section('content')
    <section class="panel">
        <h1>Tambah Laporan Sampah</h1>
        <form method="POST" action="{{ route('waste-reports.store') }}" enctype="multipart/form-data">
            @csrf
            @include('waste-reports._form')
        </form>
    </section>

    <script>
        document.getElementById('useLocationBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung Geolocation. Silakan masukkan koordinat secara manual.');
                return;
            }

            this.disabled = true;
            this.textContent = '⏳ Mengambil lokasi...';

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    document.getElementById('latitude').value = lat.toFixed(7);
                    document.getElementById('longitude').value = lng.toFixed(7);
                    
                    document.getElementById('useLocationBtn').disabled = false;
                    document.getElementById('useLocationBtn').textContent = '✓ Lokasi Terambil!';
                    
                    setTimeout(() => {
                        document.getElementById('useLocationBtn').textContent = '📍 Gunakan Lokasi Saya';
                    }, 2000);
                },
                function(error) {
                    let errorMsg = 'Gagal mengambil lokasi.';
                    if (error.code === error.PERMISSION_DENIED) {
                        errorMsg = 'Anda menolak akses lokasi. Silakan izinkan di browser settings.';
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        errorMsg = 'Lokasi tidak tersedia. Coba di tempat yang lebih terbuka.';
                    } else if (error.code === error.TIMEOUT) {
                        errorMsg = 'Timeout mengambil lokasi. Coba lagi.';
                    }
                    alert(errorMsg);
                    document.getElementById('useLocationBtn').disabled = false;
                    document.getElementById('useLocationBtn').textContent = '📍 Gunakan Lokasi Saya';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });
    </script>
@endsection
