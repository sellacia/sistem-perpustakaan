<!DOCTYPE html>
<html>

<head>
    <title>Register</title>

    <style>
        body {
            background: linear-gradient(to right, #cfd9df, #e2ebf0);
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            width: 600px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .title {
            text-align: center;
            background: #ededed;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: bold;
            font-size: 18px;
        }

        .form-group {
            display: grid;
            grid-template-columns: 150px 1fr;
            align-items: center;
            margin-bottom: 15px;
        }

        label {
            font-size: 15px;
        }

        input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }

        .btn {
            text-align: center;
            margin-top: 20px;
        }

        button {
            background: #10a36f;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        button:hover {
            background: #0d8a5e;
        }

        .link {
            text-align: center;
            margin-top: 10px;
        }
    </style>

</head>

<body>
    @if ($errors->any())
    <div style="color:red">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

    <div class="container">

        <div class="title">Register</div>

        <form method="POST" action="/register">
            @csrf

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" required>
            </div>

            <div class="form-group">
                <label>No Telepon</label>
                <input type="text" name="no_telp" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <div class="btn">
                <button type="submit">Register</button>
            </div>

        </form>

        <div class="link">
            Sudah punya akun? <a href="/login">Login</a>
        </div>

    </div>

</body>

</html>
