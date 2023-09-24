<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Absen QR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body style="background: #eaeaea;">

    <h2 class="text-center">HALAMAN ABSEN</h2>
    <div class="text-center">{!! QrCode::size(150)->generate(Hash::make('qwerty123')) !!}</div>
    <h6 class="text-center mt-2">Scan Kode berikut untuk absensi</h6>

    <hr>

    <div class="text-center mt-4">
        Live Atendance
        <div class="h1" id="clock"></div>
        <small>{{ Carbon\Carbon::now()->format('D, d M Y') }}</small>
    </div>

    <hr>

    <div class="text-center mt-2">
        Waktu Absen
        <div class="h1">08:00 - 08:15 <br><span class="h2">WITA</span></div>
    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

</body>

</html>
