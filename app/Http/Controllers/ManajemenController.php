<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Nozle;
use App\Models\Produk;
use App\Models\Pulau;
use App\Models\Spbu;
use App\Models\Tangki;
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

    $tangki = Tangki::where('SpbuId', $SpbuId)->get();

    $pulau = Pulau::where('SpbuId', $SpbuId)->get();

    return view('manajemen', compact('karyawan', 'nozle', 'produk', 'pulau', 'SpbuId', 'tangki'));
}



  public function storeKaryawan(Request $request)
{
    $request->validate([
        'Nama' => 'required|string|max:255',
        'Role' => 'required|string|max:50',
        'Nip' => 'required|string|max:20',
        'Cv' => 'nullable|file|mimes:pdf|max:2048',
        'FilePribadi' => 'nullable|file|mimes:pdf|max:2048',
        'Status' => 'required|in:aktif,nonaktif',
    ]);

    $nomorSpbu = Auth::user()->NomorSPBU;

    $Cv = null;
    $Filepribadi = null;

    if ($request->hasFile('Cv')) {
        $CvOriginalName = $request->file('Cv')->getClientOriginalName();
        $Cv = $request->file('Cv')->storeAs('Cv', $CvOriginalName, 'public');
    }
    if ($request->hasFile('FilePribadi')) {
        $filePribadiOriginalName = $request->file('FilePribadi')->getClientOriginalName();
        $Filepribadi = $request->file('FilePribadi')->storeAs('FilePribadi', $filePribadiOriginalName, 'public');
    }

    Karyawan::create([
        'Nama' => $request->Nama,
        'Role' => $request->Role,
        'Nip' => $request->Nip,
        'NomorSPBU' => $nomorSpbu,
        'Cv' => $Cv,
        'FilePribadi' => $Filepribadi,
        'Status' => $request->Status,
    ]);

    return redirect()->route('manajemen')->with('success', 'Karyawan berhasil ditambahkan.');
}


    public function UpdateKaryawan(Request $request)
{
    $karyawan = Karyawan::findOrFail($request->id);

    $request->validate([
        'Nama' => 'required|string|max:255',
        'Nip' => 'required|string|max:20',
        'Role' => 'required|string|max:50',
        'Cv' => 'nullable|file|mimes:pdf|max:2048',
        'FilePribadi' => 'nullable|file|mimes:pdf|max:2048',
        'Status' => 'required|in:aktif,nonaktif',
    ]);

    $data = [
        'Nama' => $request->Nama,
        'Nip' => $request->Nip,
        'Role' => $request->Role,
        'Status' => $request->Status,
    ];

    // Jika ada file CV baru
    if ($request->hasFile('Cv')) {
        $CvOriginalName = $request->file('Cv')->getClientOriginalName();
        $data['Cv'] = $request->file('Cv')->storeAs('Cv', $CvOriginalName, 'public');
    }

    // Jika ada file FilePribadi baru
    if ($request->hasFile('FilePribadi')) {
        $filePribadiOriginalName = $request->file('FilePribadi')->getClientOriginalName();
        $data['FilePribadi'] = $request->file('FilePribadi')->storeAs('FilePribadi', $filePribadiOriginalName, 'public');
    }

    $karyawan->update($data);

    return redirect()->route('manajemen')->with('success', 'Karyawan berhasil diperbarui.');
}

    public function EditProduk(Request $request){
    $produk = Produk::findOrFail($request->id);

    $request->validate([
        'NamaProduk' => 'required|string|max:255',
        'HargaPerLiter' => 'required|numeric',
    ]);

    $produk->update([
        'Nama' => $request->NamaProduk,
        'HargaPerLiter' => $request->HargaPerLiter,
    ]);

    return redirect()->route('manajemen')->with('success', 'Produk berhasil diperbarui.');
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