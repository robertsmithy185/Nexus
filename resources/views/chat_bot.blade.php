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
                    @if (session('role') || Auth::guard('pengurus')->check() ||
                    Auth::guard('admin')->check())
                    <a href="{{ route('logout') }}" class="menu-item">
                        <img src="/Icon/out.png" alt="Logo UKM" />
                        <span class="text">Keluar</span>
                    </a>
                    @endif
                </div>
            </div>
            <div class="section-menu-chatbot" id="section-menu">
                <div class="chat-container">
                    <!-- Chat Header -->
                    <div class="chat-header">
                        <h3>Tanya Nexus</h3>
                    </div>

                    <!-- Chat Box -->
                    <div class="chat-box">
                        <!-- Chat Messages -->
                        <!-- <div class="chat-message user-message ">
                        </div> -->
                        <div class="chat-message bot-message">
                            <p>Selamat Datang Di ChatBot Nexus</p>
                        </div>
                    </div>
                    <div id="loading" style="display: none; color:white">Bot sedang memproses...</div>

                    <!-- Chat Input -->
                    <div class="chat-input">
                        <input type="text" placeholder="Tanya Nexus..." />
                        <button>➤</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            const chatBox = document.querySelector(".chat-box");
            const inputField = document.querySelector(".chat-input input");
            const sendButton = document.querySelector(".chat-input button");

            function sendMessage() {
                const userText = inputField.value.trim();
                if (!userText) return;

                // Tampilkan loading
                document.getElementById("loading").style.display = "block";

                // Tambahkan pesan pengguna ke kotak obrolan
                const userMessage = document.createElement("div");
                userMessage.className = "chat-message user-message";
                userMessage.innerHTML = `<p>${userText}</p>`;
                chatBox.appendChild(userMessage);

                // Bersihkan input
                inputField.value = "";

                // Kirimkan pesan ke Flask API
                fetch("http://nexus.ith.ac.id:5000/chatbot", { // URL mengarah ke API Flask
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ message: userText }), // Kirim data sebagai JSON
                })
                    .then((response) => {
                        if (!response.ok) throw new Error("Gagal mengambil respons bot");
                        return response.json();
                    })
                    .then((data) => {
                        // Tambahkan respons bot ke kotak obrolan
                        const botMessage = document.createElement("div");
                        botMessage.className = "chat-message bot-message";
                        botMessage.innerHTML = `<p>${data.response}</p>`;
                        chatBox.appendChild(botMessage);

                        // Scroll ke bagian bawah kotak obrolan
                        chatBox.scrollTop = chatBox.scrollHeight;
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        const errorMessage = document.createElement("div");
                        errorMessage.className = "chat-message bot-message";
                        errorMessage.innerHTML = `<p>Terjadi kesalahan: ${error.message}</p>`;
                        chatBox.appendChild(errorMessage);
                    })
                    .finally(() => {
                        document.getElementById("loading").style.display = "none";
                    });
            }

            // Event listener untuk tombol kirim
            sendButton.addEventListener("click", sendMessage);

            // Event listener untuk tombol Enter di input
            inputField.addEventListener("keyup", (event) => {
                if (event.key === "Enter") sendMessage(); // Jika tombol Enter ditekan
            });
        </script>

        <script>
            document
                .getElementById("toggle-btn")
                .addEventListener("click", function () {
                    document.getElementById("menu").classList.toggle("active");
                });
        </script>
    </body>
</html>
