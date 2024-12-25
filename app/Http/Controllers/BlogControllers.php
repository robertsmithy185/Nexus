<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class BlogControllers extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'texteditor' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Ambil nim_pengurus dari session
        $nimPengurus = session('nim_pengurus');
        if (!$nimPengurus) {
            return redirect()->back()->withErrors(['error' => 'Session pengurus tidak ditemukan.']);
        }

        // Simpan gambar ke dalam folder 'uploads/blog'
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('uploads/blog', 'public');
        }

        // Simpan data ke tabel blog
        Blog::create([
            'nim_pengurus' => $nimPengurus,
            'judul' => $request->name,
            'deskripsi' => $request->texteditor,
            'gambar' => $gambarPath,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Blog berhasil ditambahkan.');
    }
}
