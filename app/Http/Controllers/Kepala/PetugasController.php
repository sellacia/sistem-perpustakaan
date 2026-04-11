<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = User::where('role', 'petugas')->get();

        return view('kepala.petugas.index', compact('petugas'));
    }
    public function create()
    {
        return view('kepala.petugas.create');
    }
    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role' => 'petugas'
        ]);

        return redirect()->route('kepala.petugas.index');
    }
    public function edit($id)
    {
        $petugas = User::findOrFail($id);
        return view('kepala.petugas.edit', compact('petugas'));
    }
    public function update(Request $request, $id)
    {
        $petugas = User::findOrFail($id);

        $petugas->update([
            'name' => $request->name,
            'username' => $request->username,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('kepala.petugas.index');
    }
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('kepala.petugas.index');
    }
}
