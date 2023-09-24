@extends('templates.main')

@section('content')
    {{-- kamera scan --}}
    @if ($absen == null)
        <div class="bg-white shadow py-1 text-center" id="kameraScan">
            <div id="reader"></div>
            <h6 class="mt-1">Scan QR Code untuk absen</h6>
        </div>
    @endif

    {{-- waktu absen dan tanggalnya --}}
    <div class="bg-white shadow mt-2 p-3 text-center">
        <div>Live Attendance</div>
        <h1 id="clock"></h1>
        <small>{{ Carbon\Carbon::now()->format('D, d M Y') }}</small>
    </div>
    <div class="bg-white shadow mt-2 p-3 text-center">
        <div class="fw-semibold">Absen Hari ini</div>
        <h2>08:00 - 08:15</h2>
        <button
            class="btn {{ $absen == null ? 'btn-warning' : ($absen->keterangan == 'Terlambat' ? 'btn-danger' : 'btn-success') }} fw-bold mt-2"
            id="tombolAbsen">
            {{ $absen == null ? 'Belum Absen' : $absen->keterangan }}
        </button>
    </div>

    {{-- detail absen --}}
    <div class="bg-white shadow mt-2 p-3 mb-2">
        <div class="row">
            <div class="col-12 mb-2">
                <p>Detail Absen :</p>
            </div>
            <div class="col-4">
                <h6>Nama</h6>
            </div>
            <div class="col-8">
                <h6 id="nama">{{ $absen == null ? '-' : $absen->user->nama }}</h6>
            </div>
            <div class="col-4">
                <h6>Alamat</h6>
            </div>
            <div class="col-8">
                <h6 id="alamat">{{ $absen == null ? '-' : $absen->user->alamat }}</h6>
            </div>
            <div class="col-4">
                <h6>Waktu Absen</h6>
            </div>
            <div class="col-8">
                <h6 id="waktuAbsen">{{ $absen == null ? '-' : $absen->waktu_absen }}</h6>
            </div>
            <div class="col-4">
                <h6>Tanggal</h6>
            </div>
            <div class="col-8">
                <h6 id="tanggal">{{ $absen == null ? '-' : $absen->tanggal }}</h6>
            </div>
            <div class="col-4">
                <h6>Keterangan</h6>
            </div>
            <div class="col-8">
                <h6 id="keterangan">{{ $absen == null ? '-' : $absen->keterangan }}</h6>
            </div>
        </div>
    </div>

    {{-- detail absen --}}
    <div class="row bg-success text-white p-3" id="scanResult" style="display: none;">
        <div class="col-md-2">Nama</div>
        <div class="col-md-10" id="nama">:</div>
        <div class="col-md-2">Waktu Absen</div>
        <div class="col-md-10" id="waktuAbsen">:</div>
    </div>

    <form action="{{ url('/validasi') }}" method="post" class="d-none">
        @csrf
        <input type="text" name="qr_kode" id="qr_kode">
        <input type="submit" value="Submit" id="btnKirim">
    </form>

    {{-- script scan kode --}}
    @if ($absen == null)
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script>
            let scanSuccess = false;

            function onScanSuccess(decodedText, decodedResult) {

                if (!scanSuccess) { // Tambahkan pengecekan apakah pemindaian sudah berhasil sebelumnya
                    $('#qr_kode').val(decodedText);
                    $('#btnKirim').click();
                    scanSuccess = true; // Setel ke true untuk mencegah pemindaian berulang
                }
            }

            function onScanFailure(error) {

                // console.warn(`Code scan error = ${error}`);
            }

            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },
                /* verbose= */
                false);
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        </script>
    @endif

    {{-- script jam real time --}}
    <script>
        function updateClock() {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;
            document.getElementById('clock').textContent = timeString;
        }

        // Memperbarui jam setiap detik
        setInterval(updateClock, 1000);

        // Memanggil fungsi pertama kali saat halaman dimuat
        updateClock();
    </script>
@endsection
