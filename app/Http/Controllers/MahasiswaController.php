<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function create()
    {
        return view('add_pengguna'); // Tampilkan view form
    }

        public function add_pengguna(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:50',
            'prodi' => 'required|string|max:100',
            'password' => 'required|string|min:6',
        ]);

        try {
            // Membuat user baru
            Mahasiswa::create([
                'name' => $request->name,
                'nim' => $request->nim,
                'prodi' => $request->prodi,
                'role' => 'mahasiswa',
                'password' => Hash::make($request->password),
            ]);

            // Redirect dengan pesan sukses jika berhasil
            return redirect()->route('user.create')->with('success', 'Pengguna berhasil ditambahkan.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Memeriksa jika error disebabkan karena NIM sudah ada
            if ($e->errorInfo[1] == 1062) { // 1062 adalah kode error untuk duplicate entry di MySQL
                return redirect()->back()->withErrors(['nim' => 'NIM sudah digunakan.']);
            }

            // Untuk error database lainnya
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan pada server.']);
        }
    }
}
