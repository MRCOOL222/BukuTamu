<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }

        .login-form {
            width: 50%;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255, 255, 255, 0.9);
        }

        .login-form h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 25px;
            text-align: center;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
            border-radius: 5%;
        }

        .login-form .form-group {
            margin-bottom: 20px;
        }

        .login-form input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .login-form button {
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .login-form button:hover {
            background-color: #0056b3;
        }

        .welcome-section {
            width: 50%;
            background-image: url('{{ asset('assets/img/megamendung-keren.jpeg') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .welcome-section img {
            width: 350px;
            margin-bottom: 20px;
        }

        .welcome-section h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .welcome-text {
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-block;
            text-align: center;
            font-size: 18px;
            line-height: 1.5;
        }

        @media (max-width: 900px) {
            .login-container {
                flex-direction: column;
                width: 100%;
                height: auto;
            }
            .login-form,
            .welcome-section {
                width: 100%;
                padding: 40px 20px;
            }
            .welcome-section img {
                width: 200px;
            }
        }

    </style>
</head>
<body>
    <div class="login-container">
        <!-- Login Form -->
        <div class="login-form">
            <h1>Login</h1>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                
                <!-- Google reCAPTCHA -->
                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="6LeOX4wqAAAAABFSqoDyUogCRc1sbd1LILexmrr5"></div>
                </div>
                
                <button type="submit">Login</button>
                <p class="text-center mt-3">2024 © Buku Tamu DISKOMINFO Kab. Bogor | All Rights Reserved.</p>
            </form>
        </div>
        <!-- Welcome Section -->
        <div class="welcome-section">
            <img src="{{ asset('assets/img/diskominfo_kab_bogor-removebg-preview.png') }}" alt="Logo">
            <h2>WELCOME BACK!</h2>
            <p class="welcome-text">
                Selamat datang kembali di sistem Buku Tamu DISKOMINFO. Silakan login untuk melanjutkan.
            </p>
        </div>
    </div>
</body>
</html>
