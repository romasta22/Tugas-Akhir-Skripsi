<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gagal Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 100px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #d9534f;
        }
        p {
            font-size: 16px;
            margin-bottom: 20px;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0275d8;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        a:hover {
            background-color: #025aa5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gagal Login</h1>

        <?php
        if (isset($_GET['error']) && $_GET['error'] == 'wrong_credentials') {
            echo '<p>Username atau password yang Anda masukkan salah. Silakan coba lagi.</p>';
        } else {
            echo '<p>Terjadi kesalahan. Silakan coba lagi.</p>';
        }
        ?>

        <a href="dashboard_user.php?hal=login">Kembali ke Halaman Login</a>
    </div>
</body>
</html>
