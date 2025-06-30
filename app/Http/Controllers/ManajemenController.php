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
    public function index(Request $request)
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

            $karyawanQuery = Karyawan::where('NomorSPBU', $nomorSpbu);
    if ($request->filled('search_karyawan')) {
        $karyawanQuery->where('Nama', 'like', '%' . $request->search_karyawan . '%');
    }
    $karyawan = $karyawanQuery->orderBy('Nama')->get();

    // Filter Nozle
    $nozleQuery = Nozle::with('pulau')->where('SpbuId', $SpbuId);
    if ($request->filled('search_nozle')) {
        $nozleQuery->where('NamaNozle', 'like', '%' . $request->search_nozle . '%');
    }
    $nozle = $nozleQuery->get();

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

        return redirect()->back()
            ->withErrors(['manajemen' => 'Nip Sudah Terdaftar.'])
            ->withInput();
    }

    public function UpdateKaryawan(Request $request)
{
    $karyawan = Karyawan::findOrFail($request->id);

    $request->validate([
        'Nama' => 'required|string|max:255',
        'Nip' => 'required|string|max:20',
    ]);

    $karyawan->update([
        'Nama' => $request->Nama,
        'Nip' => $request->Nip,
    ]);

    return redirect()->route('manajemen')->with('success', 'Karyawan berhasil diperbarui.');
}

public function destroyKaryawan($id)
{
    $karyawan = Karyawan::findOrFail($id);
    $karyawan->delete();

    return redirect()->route('manajemen')->with('success', 'Karyawan berhasil dihapus.');
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

    public function UpdateNozle(Request $request)
    {
        $nozle = Nozle::findOrFail($request->id);

        $request->validate([
            'NamaNozle' => 'required|string|max:255',
            'PulauId' => 'required|integer',
        ]);

        $nozle->update([
            'NamaNozle' => $request->NamaNozle,
            'PulauId' => $request->PulauId,
        ]);

        return redirect()->route('manajemen')->with('success', 'Nozle berhasil diperbarui.');
    }


    public function destroyNozle($id)
    {
        $nozle = Nozle::findOrFail($id);
        $nozle->delete();

        return redirect()->route('manajemen')->with('success', 'Nozle berhasil dihapus.');
    }
}