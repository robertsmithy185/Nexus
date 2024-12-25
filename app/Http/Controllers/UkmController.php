<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ukm;
use App\Models\Mahasiswa;

class UkmController extends Controller
{
    public function add_ukm(){
        $nim = session('nim'); // Ambil NIM dari session

        // Ambil nama mahasiswa berdasarkan NIM dari session
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $name = $mahasiswa ? $mahasiswa->name : null;

        return view('add_ukm' , compact('name'));
    }
    public function store(Request $request)
    {

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:ukm,kode',
            'texteditor' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload gambar jika ada
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('ukm_images', 'public');
        }

        // Simpan data ke database
        Ukm::create([
            'name' => $request->input('name'),
            'kode' => $request->input('kode'),
            'deskripsi' => $request->input('texteditor'),
            'gambar' => $gambarPath,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Data UKM berhasil disimpan!');
    }
}

