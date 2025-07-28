<?php

namespace App\Http\Controllers;

use App\Models\JadwalOperator;
use App\Models\Karyawan;
use App\Models\Kehadiran;
use App\Models\Spbu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); 
        $nomorspbu = $user ? $user->NomorSPBU : '-';
        $namaspbu = SPBU::where('NomorSPBU', $nomorspbu)->value('NamaSPBU');

        // Hitung operator yang hadir hari ini
        $tanggalHariIni = now()->toDateString();
        $operatorAktif = Kehadiran::whereDate('WaktuMasuk', $tanggalHariIni)
            ->whereHas('karyawan', function($q) use ($nomorspbu) {
                $q->where('Role', 'Operator')->where('NomorSPBU', $nomorspbu);
            })
            ->distinct('KaryawanId')
            ->count('KaryawanId');

        return view('dashboard', compact('user', 'nomorspbu', 'namaspbu', 'operatorAktif'));
    }
}
