<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\WaktuAbsen;
use Illuminate\Http\Request;

class KelolaAbsenController extends Controller
{
    public function store(Request $request)
    {
        $cekAbsen = Absen::where('user_id', $request->user_id)->where('tanggal', $request->tanggal)->get()->first();

        if ($cekAbsen) {
            toast('Data sudah ada', 'error');
            return redirect()->back();
        }

        Absen::create([
            'user_id' => $request->user_id,
            'waktu_absen' => $request->waktu_absen,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);
        toast('Data berhasil ditambahkan', 'success');
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $userId = $request->user_id;
        $data = Absen::where('user_id', $userId)->get()->first();

        $data->update([
            'waktu_absen' => $request->waktu_absen,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);
        toast('Data berhasil diupdate', 'success');
        return redirect()->back();
    }

    public function delete($userId)
    {
        $data = Absen::where('user_id', $userId)->get()->first();
        $data->delete();
        toast('Data berhasil dihapus', 'success');
        return redirect()->back();
    }

    public function updateWaktuAbsen(Request $request)
    {
        $waktuAbsen = WaktuAbsen::find($request->waktuAbsenId);
        $waktuAbsen->update([
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
        ]);
        toast('Waktu absen diupdate', 'success');
        return redirect()->back();
    }
}
