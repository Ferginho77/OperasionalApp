<?php

namespace App\Http\Controllers;

use App\Models\JadwalOperator;
use App\Models\Karyawan;
use App\Models\Spbu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); 
        $nomorspbu = $user ? $user->NomorSPBU : '-';
         $karyawan = Karyawan::where('Role', 'Operator')
            ->where('NomorSPBU', $nomorspbu)
            ->count();
        $namaspbu = SPBU::where('NomorSPBU', $nomorspbu)->value('NamaSPBU');
        return view('dashboard', compact('user', 'nomorspbu', 'namaspbu', 'karyawan'));
    }
}
