<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = User::where('role', 'anggota')->latest()->get();
        return view('petugas.anggota.index', compact('anggota'));
    }

    public function create()
    {
        return view('petugas.anggota.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
            'no_telp' => $validated['no_telp'],
            'alamat' => $validated['alamat'],
            'role' => 'anggota'
        ]);

        return redirect()->route('petugas.anggota.index')->with('success', 'Anggota berhasil ditambahkan');
    }

    public function edit($id)
    {
        $anggota = User::findOrFail($id);
        return view('petugas.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, $id)
    {
        $anggota = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $anggota->id,
            'password' => 'nullable|string|min:6',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        $payload = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'no_telp' => $validated['no_telp'],
            'alamat' => $validated['alamat'],
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = bcrypt($validated['password']);
        }

        $anggota->update($payload);

        return redirect()->route('petugas.anggota.index')->with('success', 'Data anggota berhasil diperbarui');
    }

    public function show($id)
    {
        $anggota = User::where('role', 'anggota')->findOrFail($id);
        return view('petugas.anggota.show', compact('anggota'));
    }

    public function destroy($id)
    {
        $anggota = User::where('role', 'anggota')->findOrFail($id);

        if ($anggota->id === auth()->id()) {
            return back()->with('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        $anggota->update([
            'remember_token' => null,
        ]);

        $anggota->delete();
        return back()->with('success', 'Data anggota berhasil dihapus');
    }
}
