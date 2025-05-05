<?php
    session_start();
if ((empty($_SESSION['username'])) and (empty($_SESSION['password']))) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Rekomendasi Wisata Danau Toba</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('modul_user/image/wisata5.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            backdrop-filter: blur(15px);
            background: rgba(244, 245, 247, 0.9);
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            padding: 20px;
            width: 100%;
            max-width: 400px;
        }
        .card-header {
            background: url('img/form-bg.jpg') no-repeat center center;
            background-size: cover;
            border-radius: 15px 15px 0 0;
            height: 120px;
        }
        .card-header h4 {
            color: black;
            margin: 0;
            padding: 20px 0;
            text-align: center;
        }
        .btn-primary {
            width: 100%;
        }
        .card-footer {
            font-size: 0.9rem;
            text-align: center;
        }
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-wrapper input {
            padding-right: 40px; /* Tambahkan ruang untuk ikon mata */
        }
        .password-wrapper .toggle-password {
            position: absolute;
            right: 10px;
            cursor: pointer;
            color: #6c757d;
        }
        .password-wrapper .toggle-password:hover {
            color: #000;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h4><strong>Form Login</strong></h4>
            <p class="text-muted text-center">Silahkan Login Terlebih Dahulu!</p>
        </div>
        <div class="card-body">
            <form action="cek_login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan Username" required
                        value="<?php echo (isset($_COOKIE['username'])) ? $_COOKIE['username'] : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password" required
                            value="<?php echo (isset($_COOKIE['password'])) ? $_COOKIE['password'] : ''; ?>">
                        <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember"
                        <?php echo ((isset($_COOKIE['username'])) && (isset($_COOKIE['password']))) ? 'checked' : ''; ?>>
                    <label for="remember" class="form-check-label">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
        <div class="card-footer">
            <small>Belum punya akun? <a href="daftar.php" class="text-primary">Daftar di sini</a></small>
        </div>    </div>

    <!-- JavaScript -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            // Ubah ikon mata
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
 <!-- Bootstrap JS -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</html>

<?php
    } else{
        echo "<script>window.history.go(-1)</script>";
    }









?>
