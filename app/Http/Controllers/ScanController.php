<?php

namespace App\Http\Controllers;

use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Absen;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ScanController extends Controller
{
    public function index()
    {
        $id = auth()->user()->id;
        $tanggal = Carbon::now()->format('Y-m-d');
        $cekUser = Absen::where('user_id', $id)->where('tanggal', $tanggal)->first();

        // cek session alert
        if (session('success')) {
            Alert::success('Berhasil', session('success'));
        } else if (session("error")) {
            Alert::error('Gagal', session('error'));
        } else if (session('info')) {
            Alert::info('Info', session('info'));
        }

        return view('scan', [
            'absen' => $cekUser
        ]);
    }

    public function validasi(Request $request)
    {
        $stringHash = 'qwerty123';
        if (Hash::check($stringHash, $request->qr_kode)) {

            // ettingan waktu keterlambatan
            $current_time = new DateTime();
            $start_time = new DateTime('08:00');
            $end_time = new DateTime('08:15');

            // cek kalau belum waktu absen
            if ($start_time > $current_time) {
                return redirect('/')->with('info', 'Belum masuk waktu absen');
            }

            // tambah data user ke tabel absen
            $waktuAbsen = Carbon::now()->format('H:i:s');
            $tanggal = Carbon::now()->format('Y-m-d');

            // cek keterlambatan
            $cekTerlambat = $current_time > $end_time;
            $id = auth()->user()->id;
            $tanggal = Carbon::now()->format('Y-m-d');

            // tambahkan data baru ke tabel absen
            Absen::create([
                'user_id' => $id,
                'waktu_absen' => $waktuAbsen,
                'tanggal' => $tanggal,
                'keterangan' => $cekTerlambat ? 'Terlambat' : 'Tepat Waktu',
            ]);

            return redirect('/')->with('success', 'Absen sukses');
        }

        // qr kode tidak valid
        return redirect('/')->with('error', 'Kode QR tidak valid');
    }

    public function riwayatAbsen()
    {
        $id = auth()->user()->id;
        return view('riwayat', [
            'riwayat' => Absen::where('user_id', $id)->get(),
        ]);
    }
}
