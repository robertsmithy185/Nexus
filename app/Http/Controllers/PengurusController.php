<?php

namespace App\Http\Controllers;

use App\Models\Pengurus;
use App\Models\Ukm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PengurusController extends Controller
{
    public function create_pengurus()
    {
        return view('add_pengurus'); // Tampilkan view form
    }
    public function add_pengurus(Request $request)
    {
        // Validasi input
        $request->validate([
            'nim_pengurus' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'kode_ukm' => 'required|string|max:100',
            'password' => 'required|string|min:6',
        ]);
    
        try {
            Pengurus::create([
                'nim_pengurus' => $request->nim_pengurus,
                'name' => $request->name,
                'kode_ukm' => $request->kode_ukm,
                'role' => 'pengurus',
                'password' => Hash::make($request->password),
            ]);
    
            // Redirect dengan pesan sukses jika berhasil
            return redirect()->route('pengurus.create')->with('success', 'Pengguna berhasil ditambahkan.');
    
        } catch (\Illuminate\Database\QueryException $e) {
            // Memeriksa jika error disebabkan karena NIM sudah ada
            if ($e->errorInfo[1] == 1062) { // 1062 adalah kode error untuk duplicate entry di MySQL
                return redirect()->back()->withErrors(['nim' => 'NIM sudah digunakan.']);
            }
    
            // Untuk error database lainnya
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan pada server.']);
        }
    }
    public function add_link(Request $request)
    {
        // Validasi input
        $request->validate([
            'link' => 'required|url',
        ]);

        // Ambil kode UKM dari session
        $kode_ukm = session('kode_ukm');

        // Periksa apakah kode_ukm ada di session
        if (!$kode_ukm) {
            return redirect()->back()->with('error', 'Kode UKM tidak ditemukan di session.');
        }

        $ukm = Ukm::where('kode', $kode_ukm)->first();

        if ($ukm) {
            $ukm->link_pendaftaran = $request->input('link');
            $ukm->save();

            return redirect()->route('open')->with('success', 'Link pendaftaran berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'UKM tidak ditemukan.');
        }
    }

}
