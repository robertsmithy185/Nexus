<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Informasi;

class InformasiController extends Controller
{
    public function add_informasi(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'informasi' => 'required|string',
        ]);

        // Ambil nim_pengurus dari session
        $nimPengurus = session('nim_pengurus');
        if (!$nimPengurus) {
            return redirect()->back()->withErrors(['error' => 'Session pengurus tidak ditemukan.']);
        }

        // Menyimpan data ke tabel informasi
        Informasi::create([
            'nim_pengurus' => $nimPengurus,
            'judul' => $validatedData['judul'],
            'informasi' => $validatedData['informasi'],
        ]);

        // Redirect atau respons sukses
        return redirect()->back()->with('success', 'Informasi berhasil ditambahkan!');
    }
}

