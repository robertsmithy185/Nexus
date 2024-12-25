<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
use App\Models\Pengurus;
use App\Models\Admin;

class LoginController extends Controller
{
    public function login_view(Request $request)
    {
        return view('login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'nim' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $credentials = ['nim' => $request->nim, 'password' => $request->password];
        
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->put('role', 'admin');
            $request->session()->put('nim', $request->nim); // Menyimpan NIM ke session
            return redirect()->route('admin_home');
        }

        $pengurus = Pengurus::where('nim_pengurus', $request->nim)->first();
        if ($pengurus && Auth::guard('pengurus')->attempt(['nim_pengurus' => $request->nim, 'password' => $request->password])) {
            $request->session()->put('role', 'pengurus');
            $request->session()->put('kode_ukm', $pengurus->kode_ukm); // Menyimpan kode_ukm di session
            $request->session()->put('nim_pengurus', $pengurus->nim_pengurus); // Menyimpan nim_pengurus di session
            $namaPengurus = Mahasiswa::where('nim', $pengurus->nim_pengurus)->value('name'); // Ambil nama berdasarkan nim_pengurus
            $request->session()->put('name', $namaPengurus); // Menyimpan nama pengurus ke session
        
            return redirect()->route('home');
        }

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

        if ($mahasiswa && Auth::guard('mahasiswa')->attempt(['nim' => $request->nim, 'password' => $request->password])) {
            // Tambahkan session untuk role
            $request->session()->put('role', 'mahasiswa');
            $request->session()->put('nim', $mahasiswa->nim);
            $request->session()->put('foto_profil', $mahasiswa->foto_profil);
            $request->session()->put('name', $mahasiswa->name); // Menyimpan nama mahasiswa ke session
            return redirect()->route('home');
        }

        return back()->withErrors(['login' => 'NIM atau password salah.']);
    }
}
