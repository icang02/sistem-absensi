<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        return view('profil');
    }

    public function update(Request $request)
    {
        $id = auth()->user()->id;
        $user = User::find($id);

        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'alamat' => $request->alamat,
        ]);

        toast('Profil diperbarui', 'success');
        return redirect()->back();
    }
}
