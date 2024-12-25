<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ukm;
use App\Models\Blog;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Informasi;
use App\Models\Mahasiswa;
use App\Models\Pengurus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;



class MainControllers extends Controller
{
    public function dashboard()
    {
        $role = session('role');
        $nim = session('nim'); // Ambil NIM dari session

        // Ambil nama mahasiswa berdasarkan NIM dari session
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $name = $mahasiswa ? $mahasiswa->name : null;

        $blogs = Blog::with('pengurus') // Pastikan relasi ke pengurus sudah diatur
                    ->orderBy('created_at', 'desc') // Opsional, untuk mengurutkan berdasarkan waktu
                    ->get();

        // Kirim nama ke view bersama dengan data lainnya
        return view('dashboard', compact('role', 'blogs', 'name'));
    }

    public function login(){
        return view('login');
    }
    public function profil(Request $request)
{
    // Ambil NIM dari sesi
    $nim = $request->session()->get('nim');
    $nim_pengurus = $request->session()->get('nim_pengurus');

    // Redirect jika NIM tidak ditemukan
    if (!$nim && !$nim_pengurus) {
        return redirect()->route('login');
    }

    // Ambil data mahasiswa berdasarkan NIM
    if ($nim) {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        // Ambil daftar UKM yang diikuti oleh mahasiswa
        $ukms = DB::table('anggota_ukm')
            ->join('ukm', 'anggota_ukm.kode_ukm', '=', 'ukm.kode')
            ->where('anggota_ukm.nim', $nim)
            ->select('ukm.name', 'ukm.gambar')
            ->get();

        // Mengirimkan data mahasiswa yang lengkap
        return view('profil', [
            'mahasiswa' => $mahasiswa,  // Pastikan mahasiswa mencakup data name, prodi, foto_profil
            'ukms' => $ukms,
            'role' => 'mahasiswa'
        ]);
    }

    // Jika pengurus
    if ($nim_pengurus) {
        // Cari pengurus berdasarkan nim_pengurus
        $pengurus = Pengurus::where('nim_pengurus', $nim_pengurus)->first();

        if ($pengurus) {
            // Ambil data mahasiswa yang terkait dengan pengurus
            $mahasiswa = Mahasiswa::where('nim', $pengurus->nim_pengurus)->first();  // Mengambil data mahasiswa berdasarkan nim_pengurus

            // Ambil daftar UKM yang diikuti oleh mahasiswa
            $ukms = DB::table('anggota_ukm')
                ->join('ukm', 'anggota_ukm.kode_ukm', '=', 'ukm.kode')
                ->where('anggota_ukm.nim', $pengurus->nim_pengurus)
                ->select('ukm.name', 'ukm.gambar')
                ->get();

            return view('profil', [
                'mahasiswa' => $mahasiswa,  // Mengirim data mahasiswa yang terkait dengan pengurus
                'ukms' => $ukms,
                'role' => 'pengurus'
            ]);
        }
    }

    // Redirect jika tidak ada data
    return redirect()->route('login');
}

    public function pengurus(){
        $nim = session('nim'); // Ambil NIM dari session

        // Ambil nama mahasiswa berdasarkan NIM dari session
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $name = $mahasiswa ? $mahasiswa->name : null;

        return view('opsi_pengurus', compact('name'));
    }
    public function edit_profil(Request $request)
    {
        // Cek apakah 'nim' atau 'nim_pengurus' ada di session
        $nim = $request->session()->get('nim');
        $nim_pengurus = $request->session()->get('nim_pengurus');

        if (!$nim && !$nim_pengurus) {
            return redirect()->route('login');
        }

        // Jika ada nim_pengurus, cek sebagai pengurus
        if ($nim_pengurus) {
            $pengurus = Pengurus::where('nim_pengurus', $nim_pengurus)->first();
            if ($pengurus) {
                $mahasiswa = Mahasiswa::where('nim', $pengurus->nim_pengurus)->first();
                return view('edit_profil', ['user' => $mahasiswa, 'role' => 'pengurus']);
            }
        }

        // Jika ada nim, cek sebagai mahasiswa
        if ($nim) {
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            if ($mahasiswa) {
                return view('edit_profil', ['user' => $mahasiswa, 'role' => 'mahasiswa']);
            }
        }

        return redirect()->route('login');
    }

    public function update_profil(Request $request)
{
    // Validasi input
    $request->validate([
        'name' => 'required|string|max:255',
        'prodi' => 'required|string|max:255',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Cek apakah 'nim' atau 'nim_pengurus' ada di session
    $nim = $request->session()->get('nim');
    $nim_pengurus = $request->session()->get('nim_pengurus');

    if (!$nim && !$nim_pengurus) {
        return redirect()->route('login');
    }

    // Update data pengguna berdasarkan role
    if ($nim_pengurus) {
        $pengurus = Pengurus::where('nim_pengurus', $nim_pengurus)->first();
        if ($pengurus) {
            $mahasiswa = Mahasiswa::where('nim', $pengurus->nim_pengurus)->first();
        }
    } elseif ($nim) {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
    }

    // Jika data mahasiswa ditemukan, lakukan update
    if (isset($mahasiswa)) {
        $mahasiswa->name = $request->input('name');
        $mahasiswa->prodi = $request->input('prodi');

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('profile_pictures', 'public');
            $mahasiswa->foto_profil = $gambarPath;
        }

        $mahasiswa->save();

        return redirect()->route('edit')->with('success', 'Profil berhasil diperbarui.');
    }

    return redirect()->route('edit')->with('error', 'Data pengguna tidak ditemukan.');
}


    public function open(){
        $nim = session('nim'); // Ambil NIM dari session

        // Ambil nama mahasiswa berdasarkan NIM dari session
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $name = $mahasiswa ? $mahasiswa->name : null;

        return view('buka_pendaftaran', compact('name'));
    }
    public function add_blog(){
        $nim = session('nim'); // Ambil NIM dari session

        // Ambil nama mahasiswa berdasarkan NIM dari session
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $name = $mahasiswa ? $mahasiswa->name : null;

        return view('add_blog', compact('name'));
    }
    public function add_anggota(){
        $nim = session('nim'); // Ambil NIM dari session

        // Ambil nama mahasiswa berdasarkan NIM dari session
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $name = $mahasiswa ? $mahasiswa->name : null;
        return view('add_anggota' ,compact('name'));
    }

    public function view_blog($judul){
        // Mengambil data blog berdasarkan judul, pastikan kolom 'judul' di database memiliki indeks yang unik atau menggunakan `where`
        $blog = Blog::where('judul', $judul)->firstOrFail();

        return view('view-blog', compact('blog'));
    }

    public function add_informasi()
    {
        $nim = session('nim'); // Ambil NIM dari session

        // Ambil nama mahasiswa berdasarkan NIM dari session
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $name = $mahasiswa ? $mahasiswa->name : null;

        return view('add_informasi', compact('name'));
    }

    public function ukm(Request $request) {
        $search = $request->input('search'); // Ambil query pencarian dari input
        $ukms = Ukm::query(); // Query default UKM
        $nim = session('nim'); // Ambil NIM dari session
    
        // Ambil nama mahasiswa berdasarkan NIM dari session
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $name = $mahasiswa ? $mahasiswa->name : null;
    
        $recommendationNames = []; // Nama-nama UKM dari rekomendasi API
        $recommendedUkms = []; // UKM yang akan ditampilkan sebagai rekomendasi
        if ($search) {
            // Kirim request ke API Python
            $response = Http::post('http://127.0.0.1:5000/recommend', [
                'query' => $search,
            ]);
    
            if ($response->successful()) {
                $recommendationNames = $response->json()['recommendations']; // Ambil nama UKM dari API
            }
    
            // Filter UKM berdasarkan pencarian
            $ukms = $ukms->where('name', 'like', '%' . $search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $search . '%');
        }
    
        // Ambil data UKM berdasarkan nama yang dikirimkan oleh API
        if (!empty($recommendationNames)) {
            $recommendedUkms = Ukm::whereIn('name', $recommendationNames)->get();
        }
    
        // Ambil semua data UKM setelah filter pencarian
        $ukms = $ukms->orderBy('name', 'asc')->get();
    
        return view('ukm', compact('ukms', 'search', 'name', 'recommendedUkms')); // Kirim data ke view
    }
    
    public function show($identifier)
{
    // Ambil data UKM berdasarkan kode atau nama
    $ukm = Ukm::where('kode', $identifier)
            ->orWhere('name', $identifier)
            ->firstOrFail();

    // Return view dan kirim data UKM
    return view('view-ukm', compact('ukm'));
}

    public function admin(){

        $nim = session('nim'); // Ambil NIM dari session

        // Ambil nama mahasiswa berdasarkan NIM dari session
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $name = $mahasiswa ? $mahasiswa->name : null;

        return view('opsi_admin', compact('name'));
    }
    public function add_pengguna(){
        return view('add_pengguna');
    }
    public function add_pengurus(){
        return view('add_pengurus');
    }
    public function chatbot(){
        $nim = session('nim'); // Ambil NIM dari session

        // Ambil nama mahasiswa berdasarkan NIM dari session
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $name = $mahasiswa ? $mahasiswa->name : null;

        return view('chat_bot', compact('name'));
    }
    public function view_informasi()
    {
        $role = session('role');
        $nim = session('nim'); // Ambil nim dari session

        // Cari mahasiswa berdasarkan nim yang ada di session
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $nim = session('nim'); // Ambil NIM dari session
        $name = $mahasiswa ? $mahasiswa->name : null;

        if (!$mahasiswa) {
            // Jika mahasiswa tidak ditemukan
            return view('informasi', compact('role'))->with('error', 'Mahasiswa tidak ditemukan.');
        }

        // Cari semua anggota UKM yang berhubungan dengan mahasiswa
        $anggotaUkm = $mahasiswa->anggotaUkm()->get();

        if ($anggotaUkm->isEmpty()) {
            // Jika mahasiswa belum bergabung ke UKM manapun
            return view('informasi', compact('role'))->with('message', 'Anda belum bergabung ke UKM manapun.');
        }

        // Ambil semua kode_ukm dari anggota_ukm
        $kode_ukm_list = $anggotaUkm->pluck('kode_ukm');

        // Ambil informasi untuk semua UKM yang diikuti oleh mahasiswa
        $informasi = Informasi::whereHas('pengurus', function ($query) use ($kode_ukm_list) {
            $query->whereIn('kode_ukm', $kode_ukm_list);
        })->orderBy('created_at', 'desc')->get();

        if ($informasi->isEmpty()) {
            return view('informasi', compact('role'))->with('message', 'Tidak ada informasi untuk UKM yang Anda masuki.');
        }

        return view('informasi', compact('role', 'informasi', 'name'));
    }

    public function snow_informasi($nama)
    {
        $role = session('role');
        // Cari informasi berdasarkan nama
        $informasi = Informasi::where('judul', $nama)->first();

        if (!$informasi) {
            // Jika tidak ditemukan, arahkan ke halaman lain atau tampilkan pesan error
            return redirect()->route('snow.informasi')->with('error', 'Informasi tidak ditemukan.');
        }

        return view('view_informasi', compact('role', 'informasi'));
    }

    
    public function logout()
    {
        // Menghapus semua session
        Session::flush();

        // Logout jika menggunakan Auth (misalnya untuk guard 'pengurus')
        if (Auth::guard('pengurus')->check()) {
            Auth::guard('pengurus')->logout();
        }

        // Redirect ke halaman login atau halaman lainnya
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
