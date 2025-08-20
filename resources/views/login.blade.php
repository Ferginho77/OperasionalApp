<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="/img/LogoPertamina.png"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/yourkitid.js" crossorigin="anonymous"></script>
    <title>LOGIN</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }

        /* Full-page SVG background with animation */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: url('/svg/background.svg') no-repeat center center;
            background-size: cover;
            animation: backgroundMove 30s linear infinite;
        }
        @keyframes backgroundMove {
            0% { background-position: center top; }
            50% { background-position: center center; }
            100% { background-position: center top; }
        }

        .card {
            animation: fadeInUp 0.8s ease-out;
            border-radius: 20px;
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.8) !important;
        }
        @keyframes fadeInUp {
            from {opacity: 0; transform: translateY(50px);}
            to {opacity: 1; transform: translateY(0);}
        }

        .btn-primary {
            background: #004aad;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #00347a;
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .logo img {
            animation: bounceIn 1.2s ease;
        }
        @keyframes bounceIn {
            0% {transform: scale(0.5); opacity: 0;}
            60% {transform: scale(1.1); opacity: 1;}
            100% {transform: scale(1);}
        }

        .footer-text {
            font-size: 14px;
            color: #333;
            animation: fadeIn 2s ease;
        }
        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        .form-control:focus {
            border-color: #004aad;
            box-shadow: 0 0 10px rgba(0,74,173,0.5);
            transition: box-shadow 0.3s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="background"></div>

    <main>
        <div class="d-flex justify-content-center align-items-center vh-100 position-relative">
            <div class="card shadow-lg border-0 p-4 p-lg-5" style="max-width: 450px; width: 100%;">
                <div class="text-center logo mb-4">
                    <img src="/img/LogoPertamina.png" alt="Logo" class="img-fluid" style="max-width: 150px;">
                </div>
                <h3 class="text-center mb-4 fw-bold">LOGIN</h3>

                <form method="post" action="{{ route('login.post') }}">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @csrf

                    <div class="form-floating mb-3">
                        <input class="form-control" id="username" pattern="[a-zA-Z0-9_]{4,20}" value="{{ old('username') }}" type="text" name="username" placeholder="Username" required>
                        <label for="username"><i class="fa-solid fa-user me-2"></i>Username</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input class="form-control" id="password" pattern="^[a-zA-Z0-9@#$%^&+=]{3,20}$" type="password" name="password" placeholder="Password" required>
                        <label for="password"><i class="fa-solid fa-lock me-2"></i>Password</label>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg" type="submit">Login <i class="fa-solid fa-right-to-bracket"></i></button>
                    </div>
                </form>

                <p class="text-center footer-text mt-4">© 2025 | PT. Hendarsyah Surya Putra</p>
            </div>
        </div>
    </main>
</body>
</html>
