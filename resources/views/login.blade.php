<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Nexus</title>
        <link
            rel="icon"
            href="/Logo Nexus/Nexus Logo 2.png"
            type="image/x-icon"
        />
        <link rel="stylesheet" href="Style.css" />
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet"
        />
    </head>
    <body>
        <div class="body-container">
            <div class="login-container">
                <div class="login-box">
                    <div class="logo">
                        <img src="/Logo Nexus/Logo Clean.png" alt="Logo 2" />
                        <img src="/Logo Nexus/Nexus Logo.png" alt="Logo 1" />
                    </div>
                    <h2>Login into your account</h2>
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input
                                type="text"
                                name="nim"
                                id="nim"
                                placeholder="Nim"
                                required
                            />
                        </div>
                        <div class="input-group">
                        <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Password"
                                required
                            />
                        </div>
                        @if($errors->any())
                        <div class="error-messages-login">
                            @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                            @endforeach
                        </div>
                        @endif
                        @if(session('success'))
                        <div class="success-message-login">
                            <p>{{ session('success') }}</p>
                        </div>
                        @endif
                        <button class="button-login" type="submit">Log in</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
