<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnggotaUkm;

class AnggotaUkmController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nim' => 'required|exists:mahasiswa,nim', // Pastikan NIM ada di tabel mahasiswa
        ]);

        // Ambil kode_ukm dari session
        $kodeUkm = session('kode_ukm'); // Pastikan session 'kode_ukm' diset saat login pengurus

        // Tambahkan anggota baru
        AnggotaUkm::create([
            'nim' => $validatedData['nim'],
            'kode_ukm' => $kodeUkm,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Anggota berhasil ditambahkan!');
    }
}

