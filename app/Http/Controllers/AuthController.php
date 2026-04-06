<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // LOGIN VIEW
    public function showLogin()
    {
        return view('auth.login');
    }

    // REGISTER VIEW
    public function showRegister()
    {
        return view('auth.register');
    }

    // 🔥 LOGIN FIX + ROLE
    public function login(Request $request)
    {
        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
        ])) {

            $user = Auth::user();

            // 🔥 BEDAIN ROLE
            if ($user->role == 'petugas') {
                return redirect('/dashboard-petugas');
            } else {
                return redirect('/dashboard');
            }
        }

        return back()->with('error', 'Username atau password salah');
    }

    // 🔥 REGISTER FIX
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'alamat' => 'required',
            'no_telp' => 'required',
            'password' => 'required|confirmed|min:4'
        ]);

        User::create([
            'name' => $request->username,
            'username' => $request->username,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'password' => Hash::make($request->password),
            'role' => 'anggota'
        ]);

        return redirect('/login')->with('success', 'Register berhasil!');
    }
}
