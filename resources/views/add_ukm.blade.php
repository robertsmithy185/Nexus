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
        <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            crossorigin="anonymous"
        ></script>
        <script
            src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"
        ></script>
        <link
            rel="stylesheet"
            href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
            integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
            crossorigin="anonymous"
        />
        <script
            src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"
        ></script>
        <link
            href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css"
            rel="stylesheet"
        />
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
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
            <div class="section-menu-admin-ukm" id="section-menu">
                <div class="text-admin-addukm">
                    <p>Tambahkan UKM</p>
                </div>
                <div class="form_add_pengguna">
                    <form action="{{ route('ukm.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input_name">
                            <input
                                type="text"
                                name="name"
                                id="name"
                                placeholder="Name UKM"
                            />
                        </div>
                        <div class="input_kode">
                            <input
                                type="text"
                                name="kode"
                                id="kode"
                                placeholder="Kode UKM"
                            />
                        </div>
                        <div class="input_texteditor">
                            <label for="summernote"> Deskripsi </label>
                            <textarea
                                name="texteditor"
                                id="summernote"
                            ></textarea>
                        </div>
                        <div class="input_gambar">
                            <label for="summernote"> Tambahkan Gambar </label>
                            <div class="input_img">
                                <input
                                    type="file"
                                    name="gambar"
                                    id="uploadgambar"
                                    accept="image/*"
                                />
                            </div>
                        </div>
                        <div class="button_submit">
                            <button type="submit" name="button_add_pengguna">
                                Submit
                            </button>
                        </div>
                        @if(session('success'))
                        <div class="success-message">
                            <p>{{ session('success') }}</p>
                        </div>
                        @endif
                        <!-- Menampilkan pesan error -->
                        @if($errors->any())
                        <div class="error-messages">
                            @foreach ($errors->all() as $error)
                            <p>Mahasiswa Tidak Terdaftar</p>
                            @endforeach
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <script>
            $("#summernote").summernote({
                placeholder: "Deskripsikan Mengenai UKM anda",
                tabsize: 2,
                height: 150,
                width: 365,
                toolbar: [
                    ["style", ["bold", "italic", "underline", "clear"]],
                    ["fontsize", ["fontsize"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                ],
            });
        </script>
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
