<?php

namespace App\Http\Controllers;


class DashboardController extends Controller
{
    public function index()
{
    return view('page.dashboard.index', [
        'sedangDipinjam' => 2,
        'bukuTerlambat' => 1,
        'totalDenda' => 1
    ]);
}
}
