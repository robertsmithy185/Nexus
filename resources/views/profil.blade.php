<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Nexus</title>
        <link
            rel="icon"
            href="/Logo Nexus/Nexus Logo 2.png"
            type="image/x-icon"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
            rel="stylesheet"
        />

        <link
            rel="stylesheet"
            type="text/css"
            href="{{ asset('Style.css') }}"
        />
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search"
        />
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=login"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
            rel="stylesheet"
        />
    </head>
    <body>
        <header class="header-home">
            <div class="logo-icon">
                <img src="/Logo Nexus/Logo Clean.png" alt="Logo 2" />
                <img src="/Logo Nexus/Nexus Logo.png" alt="Logo 1" />
            </div>
            <div class="pencarian">
                <form
                    class="search-form"
                    action="{{ route('ukm') }}"
                    method="get"
                >
                    <div class="search-bar">
                        <span class="material-symbols-outlined">search</span>
                        <input
                            type="text"
                            name="search"
                            placeholder="Cari UKM Anda"
                            value="{{ request('search') }}"
                        />
                    </div>
                </form>
            </div>
            @if (!session('role'))
            <a href="{{ route('login') }}" class="join-button">
                <span class="material-symbols-outlined">login</span>
                <div class="join-text">Masuk</div>
            </a>
            @elseif (session('role') === 'pengurus')
            <a href="{{ route('pengurus') }}" class="join-button">
                <span class="material-symbols-outlined">login</span>
                <div class="join-text">Kelola UKM</div>
            </a>
            @elseif (session('role') === 'mahasiswa')
            <a href="{{ route('ukm') }}" class="join-button">
                <span class="material-symbols-outlined">login</span>
                <div class="join-text">Gabung UKM</div>
            </a>
            @elseif (session('role') === 'admin')
            <a href="{{ route('admin_home') }}" class="join-button">
                <span class="material-symbols-outlined">login</span>
                <div class="join-text">Menu Admin</div>
            </a>
            @endif
            <a href="{{ route('chatbot') }}" class="chatbot-button">
                <img src="/Icon/chatbot.png" alt="" />
            </a>
            <a href="{{ route('informasi') }}" class="notif-button">
                <img src="/Icon/notifikasi.png" alt="" />
            </a>
            <a href="{{ session('role') ? route('profil_user', ['name' => session('name')]) : route('login') }}" class="profil-button">
                @if (session('role'))
                    @if (session('role') === 'pengurus')
                        @php
                            // Cek foto profil pengurus berdasarkan nim_pengurus
                            $fotoProfil = \App\Models\Mahasiswa::where('nim', session('nim_pengurus'))->value('foto_profil');
                        @endphp
                        @if ($fotoProfil)
                            <img src="{{ asset('storage/' . $fotoProfil) }}" alt="Profil Pengurus" />
                        @else
                            <img src="{{ asset('Icon/foto.jpg') }}" alt="Avatar Pengurus" />
                        @endif
                    @else
                        @php
                            // Cek foto profil mahasiswa berdasarkan nim
                            $fotoProfil = \App\Models\Mahasiswa::where('nim', session('nim'))->value('foto_profil');
                        @endphp
                        @if ($fotoProfil)
                            <img src="{{ asset('storage/' . $fotoProfil) }}" alt="Profil Mahasiswa" />
                        @else
                            <img src="{{ asset('Icon/profil.png') }}" alt="Avatar Mahasiswa" />
                        @endif
                    @endif
                @else
                    <img src="{{ asset('Icon/profil.png') }}" alt="Avatar Default" />
                @endif
            </a>
        </header>
        <div class="menu-utama">
            <div class="menu-container">
                <button class="toggle-btn" id="toggle-btn">☰</button>
                <div class="menu" id="menu">
                    <a href="{{ route('ukm') }}" class="menu-item">
                        <img src="/Icon/ukm.png" alt="Logo UKM" />
                        <span class="text">Unit Kegiatan Mahasiswa</span>
                    </a>
                    <a href="{{ route('informasi') }}" class="menu-item">
                        <img src="/Icon/info.png" alt="Logo UKM" />
                        <span class="text">Informasi</span>
                    </a>
                    <a href="{{ route('logout') }}" class="menu-item">
                        <img src="/Icon/out.png" alt="Logo UKM" />
                        <span class="text">Keluar</span>
                    </a>
                </div>
            </div>
            <div class="profile-card">
                <!-- Foto Profil -->
                <div class="container-profil-card">
                    <div class="profile-photo">
                    <!-- Tampilkan foto profil atau gambar default jika foto_profil null -->
                        <img src="{{ $mahasiswa->foto_profil ? asset('storage/' . $mahasiswa->foto_profil) : asset('Icon/profil.png') }}" alt="Foto Profil" />
                    </div>

                    <!-- Informasi Profil -->
                    <div class="profile-info">
                        <p class="profile-name">Nama : {{ $mahasiswa->name }}</p>
                        <p class="profile-major">Prodi : {{ $mahasiswa->prodi }}</p>
                    </div>

                    <!-- Tombol Edit -->
                    <div class="edit-icon">
                        <a href="{{ route('edit') }}">
                            <img src="/Icon/edit.png" alt="Edit" />
                            <span>Edit</span>
                        </a>
                    </div>
                </div>
                <div class="info">
                    <p>UKM Saya</p>
                </div>
                <div class="ukm-view-profil">
                    @if ($ukms->isNotEmpty())
                        @foreach ($ukms as $ukm)
                            <div class="ukm-one">
                                <div class="image-ukm">
                                    <img src="{{ $ukm->gambar ? asset('storage/' . $ukm->gambar) : '/item/Image.png' }}" alt="{{ $ukm->name }}" />
                                </div>
                                <div class="judul-ukm">
                                <a href="{{ route('ukm.show', $ukm->name) }}">
                                        <p>{{ $ukm->name }}</p>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>Belum ada UKM yang diikuti.</p>
                    @endif
                </div>
            </div>
        </div>
        <script>
            document
                .getElementById("toggle-btn")
                .addEventListener("click", function () {
                    // Toggle visibility of the menu
                    document.getElementById("menu").classList.toggle("active");
                });
        </script>
    </body>
</html>
