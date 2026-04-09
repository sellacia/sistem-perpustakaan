<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

    <style>
        body {
            background: linear-gradient(to right, #cfd9df, #e2ebf0);
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            width: 350px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 5px;
        }

        p {
            margin-bottom: 20px;
            color: #666;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #10a36f;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #0d8a5d;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>

</head>

<body>

    <div class="card">

        <h2>Perpustakaan Digital</h2>
        <p>Login untuk akses sistem</p>

        {{-- ERROR --}}
        @if (session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
        @endif

        {{-- VALIDATION ERROR --}}
        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <label>Username</label>
            <input type="text" name="username" value="{{ old('username') }}" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p>Belum punya akun? <a href="/register">Daftar disini</a></p>

    </div>

</body>

</html>
