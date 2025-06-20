<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index()
    {
        return view('owner');
    }

    public function ShowAbsen(){
         $karyawan = Karyawan::all();
         $absensi = Absensi::with('karyawan')->get();

           return view('absensiKaryawan', compact('karyawan', 'absensi'));
    }
}
