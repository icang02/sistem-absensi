@extends('templates.main')

@section('content')
  @can('user')
    <div class="container">
      <div class="row justify-content-center mb-3">
        <div class="col-md-6">
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
            <h2>{{ $waktuAbsen->mulai }} - {{ $waktuAbsen->selesai }}</h2>
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

          {{-- form proses qr kode --}}
          <form action="{{ url('/validasi') }}" method="post" class="d-none">
            @csrf
            <input type="hidden" name="qr_kode" id="qr_kode">
            <input type="submit" value="Submit" id="btnKirim">
          </form>
        </div>
      </div>
    </div>

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
  @endcan

  @can('admin')
    <style>
      #mobileView {
        display: none;
      }

      @media only screen and (max-width: 576px) {
        #mobileView {
          display: block;
        }

        #desktopView {
          display: none;
        }
      }
    </style>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">

          {{-- mobile view --}}
          <div id="mobileView">
            <div class="bg-primary shadow px-2 py-2 text-center text-light mt-2 rounded">Data Absen</div>
            <div class="bg-light shadow px-3 py-2 rounded d-flex justify-content-between align-items-center">
              <span class="fw-medium">Pilih Aksi :</span>
              <div>
                <button class="btn badge btn-secondary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah
                  Data</button>
                <button class="btn badge btn-secondary" data-bs-toggle="modal" data-bs-target="#modalEditWaktuAbsen">Edit
                  Waktu Absen</button>
              </div>
            </div>

            @forelse ($absen as $item)
              <div class="col">
                <div class="card shadow">
                  <div class="card-body d-flex justify-content-between">
                    <div>
                      <div class="fw-medium">{{ $item->user->nama }}</div>
                      <div class="fw-medium">
                        <small>{{ $item->waktu_absen }} WITA / {{ $item->tanggal }}</small>
                      </div>
                      @if ($item->keterangan == 'Tepat Waktu')
                        <button class="btn btn-success badge mt-2">{{ $item->keterangan }}</button>
                      @elseif ($item->keterangan == 'Terlambat')
                        <button class="btn btn-danger badge mt-2">{{ $item->keterangan }}</button>
                      @else
                        <button class="btn btn-dark badge mt-2">{{ $item->keterangan }}</button>
                      @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-center flex-column ">
                      <button class="btn-sm btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#exampleModal"
                        onclick="changeData({{ $item->user_id }}, '{{ $item->user->nama }}', '{{ $item->waktu_absen }}', '{{ $item->tanggal }}', '{{ $item->keterangan }}')">Edit</button>
                      <a class="btn-sm btn btn-secondary w-100 mt-1" href="{{ urL('data-absen/' . $item->user_id) }}"
                        data-confirm-delete="true">Hapus</a>
                    </div>
                  </div>
                </div>
              </div>
            @empty
              rererjeoij
            @endforelse
          </div>

          {{-- desktop view --}}
          <div id="desktopView">
            <div class="card shadow mt-2">
              <div class="card-header bg-primary text-white text-center">
                Data Absen
              </div>
              <div class="card-body">
                <div class="mb-2 text-end">
                  <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah
                    Data</button>
                  <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditWaktuAbsen">Edit
                    Waktu
                    Absen</button>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col" class="text-center">Waktu Absen</th>
                        <th scope="col" class="text-center">Tanggal</th>
                        <th scope="col" class="text-center">Keterangan</th>
                        <th scope="col" class="text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($absen as $item)
                        <tr>
                          <th scope="row">{{ $loop->iteration }}</th>
                          <td>{{ $item->user->nama }}</td>
                          <td class="text-center">{{ $item->waktu_absen }}</td>
                          <td class="text-center">{{ $item->tanggal }}</td>
                          <td class="text-center">
                            @if ($item->keterangan == 'Tepat Waktu')
                              <button class="badge btn btn-success">{{ $item->keterangan }}</button>
                            @elseif ($item->keterangan == 'Terlambat')
                              <button class="badge btn btn-danger">{{ $item->keterangan }}</button>
                            @else
                              <button class="badge btn btn-dark">{{ $item->keterangan }}</button>
                            @endif
                          </td>
                          <td class="text-center">
                            <button class="btn btn-secondary badge" data-bs-toggle="modal" data-bs-target="#exampleModal"
                              id="btnEdit"
                              onclick="changeData({{ $item->user_id }}, '{{ $item->user->nama }}', '{{ $item->waktu_absen }}', '{{ $item->tanggal }}', '{{ $item->keterangan }}')">Edit</button>
                            <a href="{{ urL('data-absen/' . $item->user_id) }}" class="btn btn-secondary badge"
                              data-confirm-delete="true">Hapus</a>
                            </form>
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td class="text-center" colspan="6">Tidak ada data.</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <form action="{{ url('waktu-absen') }}" method="post">
        @csrf
        @method('put')
        <div class="modal fade" id="modalEditWaktuAbsen" tabindex="-1" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Waktu Absen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="container-fluid">
                  <div class="row">
                    <input type="hidden" name="waktuAbsenId" value="{{ $waktuAbsen->id }}">
                    <div class="col-md-6 mb-3">
                      <label for="mulai" class="form-label">Mulai</label>
                      <input type="time" class="form-control" name="mulai" id="mulai"
                        value="{{ $waktuAbsen->mulai }}" required>
                    </div>
                    <div class="col-md-6">
                      <label for="selesai" class="form-label">Selesai</label>
                      <input type="time" class="form-control" name="selesai" id="selesai"
                        value="{{ $waktuAbsen->selesai }}" required>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
            </div>
          </div>
        </div>
      </form>

      {{-- modal tambah --}}
      <form action="{{ url('data-absen') }}" method="post">
        @csrf
        <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Absen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label">Nama</label>
                  @php
                    $users = App\Models\User::where('level', 'user')
                        ->orderBy('nama')
                        ->get();
                  @endphp
                  <select class="form-select" name="user_id" required>
                    <option value="">Pilih user..</option>
                    @foreach ($users as $user)
                      <option value="{{ $user->id }}">{{ $user->nama }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Waktu Absen</label>
                  <input type="time" name="waktu_absen" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Tanggal</label>
                  <input type="date" name="tanggal" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Keterangan</label>
                  <select class="form-select" name="keterangan" required>
                    <option value="">Pilih opsi..</option>
                    <option value="Tepat Waktu">Tepat Waktu</option>
                    <option value="Terlambat">Terlambat</option>
                    <option value="Tidak Hadir">Tidak Hadir</option>
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
            </div>
          </div>
        </div>
      </form>

      {{-- modal edit --}}
      <form action="{{ url('data-absen') }}" method="post">
        @csrf
        @method('put')
        <input type="hidden" name="user_id" id="userId">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label for="formNama" class="form-label">Nama</label>
                  <input type="text" class="form-control" id="formNama" disabled>
                </div>
                <div class="mb-3">
                  <label for="formWaktuAbsen" class="form-label">Waktu Absen</label>
                  <input type="text" maxlength="8" name="waktu_absen" class="form-control" id="formWaktuAbsen">
                </div>
                <div class="mb-3">
                  <label for="formTanggal" class="form-label">Tanggal</label>
                  <input type="date" name="tanggal" class="form-control" id="formTanggal">
                </div>
                <div class="mb-3">
                  <label for="formKeterangan" class="form-label">Keterangan</label>
                  <select class="form-select" name="keterangan" id="formKeterangan">
                    <option value="Tepat Waktu">Tepat Waktu</option>
                    <option value="Terlambat">Terlambat</option>
                    <option value="Tidak Hadir">Tidak Hadir</option>
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>

    <script>
      function changeData(userId, nama, waktuAbsen, tanggal, keterangan) {
        $('#userId').val(userId);
        $('#formNama').val(nama);
        $('#formWaktuAbsen').val(waktuAbsen);
        $('#formTanggal').val(tanggal);
        $('#formKeterangan').val(keterangan);
      }
    </script>
  @endcan
@endsection
