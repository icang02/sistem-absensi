@extends('templates.main')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow mt-2">
                <div class="card-header bg-primary text-white text-center">
                    Data Profil
                </div>
                <div class="card-body">
                    <table cellpadding="2">
                        <tr>
                            <td>Nama</td>
                            <td>&nbsp;&nbsp; :</td>
                            <td>{{ auth()->user()->nama }}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>&nbsp;&nbsp; :</td>
                            <td>{{ auth()->user()->email }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>&nbsp;&nbsp; :</td>
                            <td>{{ auth()->user()->alamat }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ url('profil') }}" method="post">
        @csrf
        @method('put')
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input value="{{ old('nama', auth()->user()->nama) }}" type="nama" name="nama"
                                class="form-control" id="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input value="{{ old('email', auth()->user()->email) }}" type="email" name="email"
                                class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" id="alamat" rows="3" required>{{ old('alamat', auth()->user()->alamat) }}</textarea>
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
@endsection
