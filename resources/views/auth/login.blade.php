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
            border-radius: 10px;
            width: 350px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #10a36f;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>

</head>

<body>

    <div class="card">

        <h2>Perpustakaan Digital</h2>
        <p>Login Untuk Pinjam Buku</p>

        @if (session('error'))
            <p style="color:red">{{ session('error') }}</p>
        @endif

        <form method="POST" action="/login">

            @csrf

            <label>Username</label>
           <input type="text" name="username">

            <label>Password</label>
            <input type="password" name="password">

            <button type="submit">Login</button>

        </form>

        <p>Belum punya akun? <a href="/register">Daftar disini</a></p>

    </div>

</body>

</html>
