<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Nozle;
use App\Models\Produk;
use App\Models\Pulau;
use App\Models\Spbu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManajemenController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $nomorSpbu = $user->NomorSPBU;

    // Ambil SPBU berdasar NomorSPBU
   $spbu = Spbu::where('NomorSPBU', $user->NomorSPBU)->first();

    if (!$spbu) {
        abort(403, 'SPBU dengan NomorSPBU ini tidak ditemukan.');
    }

    $SpbuId = $spbu->id;

    $karyawan = Karyawan::where('NomorSPBU', $nomorSpbu)
        ->orderBy('Nama')
        ->get();

    $nozle = Nozle::with('pulau')
        ->where('SpbuId', $SpbuId)
        ->get();

    $produk = Produk::all();

    $pulau = Pulau::where('SpbuId', $SpbuId)->get();

    return view('manajemen', compact('karyawan', 'nozle', 'produk', 'pulau', 'SpbuId'));
}



    public function storeKaryawan(Request $request)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
            'Role' => 'required|in:Operator',
            'Nip' => 'required|string|max:20|',
            
        ]);

        $nomorSpbu = Auth::user()->NomorSPBU;

        Karyawan::create([
            'Nama' => $request->Nama,
            'Role' => $request->Role,
            'Nip' => $request->Nip,
            'NomorSPBU' => $nomorSpbu,
        ]);

        return redirect()->route('manajemen')->with('success', 'Jadwal berhasil dibuat.');
    }

public function storeNozle(Request $request)
{
    $request->validate([
        'NamaNozle' => 'required|string|max:255',
        'PulauId' => 'required|integer',
        'SpbuId' => 'required|integer',
    ]);

    Nozle::create([
        'NamaNozle' => $request->NamaNozle,
        'PulauId' => $request->PulauId,
        'SpbuId' => $request->SpbuId,
    ]);

    return redirect()->route('manajemen')->with('success', 'Nozle berhasil ditambahkan.');
}


}
