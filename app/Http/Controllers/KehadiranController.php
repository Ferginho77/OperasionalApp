<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
     public function index()
   {
      $karyawan = Karyawan::all();
       return view('kehadiran', compact('karyawan'));
   }
}
