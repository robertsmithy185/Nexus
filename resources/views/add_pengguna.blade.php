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
        </header>
        <div class="menu-utama">
            <div class="menu-container">
                <button class="toggle-btn" id="toggle-btn">☰</button>
                <div class="menu" id="menu">
                    <a href="{{ route('logout') }}" class="menu-item">
                            <img src="/Icon/out.png" alt="Logo UKM" />
                            <span class="text">Keluar</span>
                        </a>
                </div>
            </div>
            <div class="section-menu-admin" id="section-menu">
                <div class="text-admin">
                    <p>Tambahkan Pengguna</p>
                </div>
                <div class="form_add_pengguna">
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf
                        <!-- CSRF Token Laravel -->

                        <div class="input_name">
                            <input
                                type="text"
                                name="name"
                                id="name"
                                placeholder="Name"
                                required
                            />
                        </div>
                        <div class="input_nim">
                            <input
                                type="text"
                                name="nim"
                                id="nim"
                                placeholder="Nim"
                                required
                            />
                        </div>
                        <div class="input_prodi">
                            <input
                                type="text"
                                name="prodi"
                                id="prodi"
                                placeholder="Prodi"
                                required
                            />
                        </div>
                        <div class="input_password">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Password"
                                required
                            />
                        </div>
                        <div class="button_submit">
                            <button type="submit" name="button_add_pengguna">
                                Submit
                            </button>
                        </div>
                    </form>
                    <!-- Menampilkan pesan sukses -->
                    @if(session('success'))
                    <div class="success-message">
                        <p>{{ session('success') }}</p>
                    </div>
                    @endif
                    <!-- Menampilkan pesan error -->
                    @if($errors->any())
                    <div class="error-messages">
                        @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
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
