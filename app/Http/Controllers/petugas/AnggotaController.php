<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = User::where('role', 'anggota')->get();
        return view('petugas.anggota.index', compact('anggota'));
    }

    public function create()
    {
        return view('petugas.anggota.create');
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'role' => 'anggota'
        ]);

        return redirect()->route('petugas.anggota')->with('success', 'Anggota berhasil ditambahkan');
    }

    public function edit($id)
    {
        $anggota = User::findOrFail($id);
        return view('petugas.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, $id)
    {
        $anggota = User::findOrFail($id);

        $anggota->update([
            'name' => $request->name,
            'username' => $request->username
        ]);

        return redirect()->route('petugas.anggota')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus');
    }
}
