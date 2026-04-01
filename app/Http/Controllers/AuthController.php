<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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

    // PROSES LOGIN (PAKAI NAME)
    public function login(Request $request)
{
    if (Auth::attempt([
        'name' => $request->username, // ✅ pakai name
        'password' => $request->password
    ])) {
        return redirect('/dashboard');
    }

    return back()->with('error', 'Username atau password salah');
}

   // PROSES REGISTER
public function register(Request $request)
{
    $request->validate([
        'username' => 'required|unique:users,name',
        'alamat' => 'required',
        'no_telp' => 'required',
        'password' => 'required|confirmed|min:4'
    ]);

    User::create([
    'name' => $request->username, // ✅ WAJIB ADA
    'email' => $request->username . '@dummy.com',
    'alamat' => $request->alamat,
    'no_telp' => $request->no_telp,
    'password' => Hash::make($request->password)
]);
    return redirect('/login')->with('success', 'Register berhasil!');
}
}
