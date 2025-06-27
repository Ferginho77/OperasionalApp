<?php

namespace App\Http\Controllers;

use App\Models\Spbu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); 
        $karyawan = $user ? $user->karyawan : null;
        $nomorspbu = $user ? $user->NomorSPBU : '-';
        $namaspbu = SPBU::where('NomorSPBU', $nomorspbu)->value('NamaSPBU');
        return view('dashboard', compact('user', 'nomorspbu', 'namaspbu'));
    }
}
