<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Halaman Login | Buku Tamu DISKOMINFO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Nunito', sans-serif;
            background: radial-gradient(circle, rgba(0, 0, 0, 1) 0%, rgba(148, 187, 233, 1) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 900px;
            height: 500px;
            background-color: white;
            display: flex;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .logo-section {
            text-align: center;
        }

        .logo-section img {
            width: 150px;
            margin-bottom: 10px;
        }

        .logo-section h2 {
            font-size: 24px;
            color: #333;
        }

        .login-form {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
        }

        .login-form h1 {
            font-size: 28px;
            color: #333;
            text-align: center;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .login-form .form-group {
            margin-bottom: 15px;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .login-form button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .login-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <img src="{{ asset('assets/img/diskominfo_kab_bogor-removebg-preview.png') }}" alt="Logo">
            <h2>Selamat Datang</h2>
        </div>
        <div class="login-form">
            <h1>Silahkan Login</h1>
            @if (session('message'))
                <div class="alert alert-warning">
                    {{ session('message') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" required autofocus>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITEKEY') }}"></div>
                </div>
                <button type="submit" id="loginButton">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
